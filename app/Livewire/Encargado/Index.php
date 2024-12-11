<?php

namespace App\Livewire\Encargado;

use App\Models\Alumnos_ServicioModel;
use App\Models\EncargadoModel;
use App\Models\LaboratorioModel;
use App\Models\User;
use Livewire\Component;
use Livewire\WithPagination;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class Index extends Component
{

    public $search;
    public $sort = 'id';
    public $nombre, $apellido_p, $apellido_m, $id_laboratorio, $excludedId;
    public $direc = 'asc';
    public $cant = '10';
    protected $listeners = ['render' => 'render', 'destroyPost'];
    use WithPagination;

    public function mount()
    {
        $this->sort = 'id';
        $this->direc = 'asc';
    }
    public function updatingSearch()
    {
        $this->resetPage();
    }

    public function render()
    {

        $datos = EncargadoModel::where('nombre', 'like', '%' . $this->search . '%')
            ->orWhere('apellido_p', 'like', '%' . $this->search . '%')
            ->orWhere('apellido_m', 'like', '%' . $this->search . '%')
            ->orWhereHas('laboratorio', function ($query) {
                $query->where('nombre', 'like', '%' . $this->search . '%');
            })
            ->when($this->sort == 'id_laboratorio', function ($query) {
                $query->orderBy(
                    LaboratorioModel::select('nombre')
                        ->whereColumn('laboratorio.id', 'encargado.id_laboratorio'),
                    $this->direc
                );
            }, function ($query) {
                $query->orderBy($this->sort, $this->direc);
            })
            ->paginate($this->cant)
            ->withQueryString();
        return view('livewire.encargado.index', compact('datos'));
    }


    public function order($sort)
    {
        if ($this->sort == $sort) {
            if ($this->direc == 'desc') {
                $this->direc = 'asc';
            } else {
                $this->direc = 'desc';
            }
        } else {
            $this->sort = $sort;
            $this->direc = 'asc';
        }
    }

    public function destroyPost($id)
    {
        DB::beginTransaction();

        try {
            // 1. Obtener los IDs `id_ss` de la tabla `users`
            $idsSS = DB::table('users')
                ->where('id_encargado', $id)
                ->where('id_rol', 2)
                ->pluck('id_ss');
            Log::info('Paso 1: IDs obtenidos de la tabla users', ['id_encargado' => $id, 'idsSS' => $idsSS->toArray()]);

            // 2. Eliminar registros de la tabla `users` donde `id_encargado` sea igual al ID proporcionado
            DB::table('users')->where('id_encargado', $id)->delete();
            Log::info('Paso 2: Registros eliminados de la tabla users', ['id_encargado' => $id]);

            // 3. Verificar si hay IDs `id_ss` válidos antes de intentar eliminar registros de `alumnos_servicio`
            if ($idsSS->isNotEmpty()) {
                DB::table('alumnos_servicio')->whereIn('id', $idsSS)->delete();
                Log::info('Paso 3: Registros eliminados de la tabla alumnos_servicio', ['idsSS' => $idsSS->toArray()]);
            } else {
                Log::info('Paso 3: No hay IDs válidos en la tabla alumnos_servicio relacionados con el encargado.');
            }

            // 4. Obtener todos los IDs de la tabla `prestamo` asociados al `id_encargado`
            $prestamosIds = DB::table('prestamo')
                ->where('id_encargado', $id)
                ->pluck('id');
            Log::info('Paso 4: IDs obtenidos de la tabla prestamo', ['prestamosIds' => $prestamosIds->toArray()]);

            // 5. Verificar si hay préstamos asociados al encargado
            if ($prestamosIds->isNotEmpty()) {
                // Eliminar registros de la tabla `detalle_prestamo` asociados a los préstamos obtenidos
                DB::table('detalle_prestamo')->whereIn('id_prestamo', $prestamosIds)->delete();
                Log::info('Paso 5: Registros eliminados de la tabla detalle_prestamo', ['prestamosIds' => $prestamosIds->toArray()]);

                // Eliminar registros de la tabla `prestamo` asociados al encargado
                DB::table('prestamo')->where('id_encargado', $id)->delete();
                Log::info('Paso 6: Registros eliminados de la tabla prestamo', ['id_encargado' => $id]);
            } else {
                Log::info('Paso 5-6: No hay préstamos relacionados con el encargado.');
            }

            // 6. Verificar si hay materiales asociados al encargado antes de eliminar de la tabla `material`
            // $materialDeleted = DB::table('material')->where('id_encargado', $id)->delete();
            // Log::info('Paso 7: Registros eliminados de la tabla material', ['id_encargado' => $id, 'eliminados' => $materialDeleted]);

            $localizacionDeleted = DB::table('localizacion')->where('id_encargado', $id)->delete();
            Log::info('Paso 7: Registros eliminados de la tabla material', ['id_encargado' => $id, 'eliminados' => $localizacionDeleted]);
            // 7. Verificar si el encargado existe antes de eliminar de la tabla `encargado`
            $encargadoDeleted = DB::table('encargado')->where('id', $id)->delete();
            Log::info('Paso 8: Encargado eliminado de la tabla encargado', ['id' => $id, 'eliminados' => $encargadoDeleted]);

            // Confirmar la transacción
            DB::commit();
            Log::info('Paso 9: Transacción completada con éxito');

            return response()->json(['message' => 'Encargado eliminado exitosamente.'], 200);
        } catch (\Exception $e) {
            // Revertir la transacción en caso de error
            DB::rollBack();
            Log::critical('Error al procesar la solicitud.', [
                'id_encargado' => $id,
                'exception' => $e->getMessage()
            ]);

            return response()->json([
                'error' => 'Error al procesar la solicitud: ' . $e->getMessage()
            ], 500);
        }
    }
}
