<?php

namespace App\Livewire\Material;

use App\Models\CategoriaModel;
use App\Models\EncargadoModel;
use App\Models\localizacion;
use App\Models\MarcaModel;
use App\Models\MaterialModel;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\DB;
use Livewire\Component;
use Livewire\WithPagination;

class Index extends Component
{

    public $search, $UserId, $encargados, $encargados2;
    public $sort = 'id';
    public $direc = 'asc';
    public $cant = '10';
    public $SelectEncargado = 0;
    protected $listeners = ['render' => 'render', 'destroyPost'];
    use WithPagination;

    public function mount()
    {
        $this->sort = 'id';
        $this->direc = 'asc';
        $this->UserId = auth()->user()->id_encargado;
        $this->encargados = EncargadoModel::all();
    }

    public function updatedSelectEncargado($value)
    {
        $this->encargados2 = EncargadoModel::find($value);
    }

    public function updatingSearch()
    {
        $this->resetPage();
    }

    public function render()
    {
        // Si el usuario es jefe y SelectEncargado es -1, mostrar todos los materiales.
        if (auth()->user()->id_rol == 7 && $this->SelectEncargado == -1) {

            /*$datos = MaterialModel::where(function ($query) {
            $query->where('nombre', 'like', '%' . $this->search . '%')
            ->orWhere('modelo', 'like', '%' . $this->search . '%')
            ->orWhere('stock', 'like', '%' . $this->search . '%')
            ->orWhere('descripcion', 'like', '%' . $this->search . '%')
            ->orWhere('localizacion', 'like', '%' . $this->search . '%')
            ->orWhereHas('marca', function ($query) {
            $query->where('nombre', 'like', '%' . $this->search . '%');
            })
            ->orWhereHas('categoria', function ($query) {
            $query->where('nombre', 'like', '%' . $this->search . '%');
            })
            ->orWhereHas('encargado', function ($query) {
            $query->where('nombre', 'like', '%' . $this->search . '%')
            ->orWhere('apellido_p', 'like', '%' . $this->search . '%')
            ->orWhere('apellido_m', 'like', '%' . $this->search . '%');
            });
            })
            ->orderBy($this->sort, $this->direc)
            ->paginate($this->cant)
            ->withQueryString();*/

            $datos = MaterialModel::where('nombre', 'like', '%' . $this->search . '%')
                ->orWhere('modelo', 'like', '%' . $this->search . '%')
                ->orWhere('stock', 'like', '%' . $this->search . '%')
                ->orWhere('descripcion', 'like', '%' . $this->search . '%')
                ->orWhereHas('marca', function ($query) {
                    $query->where('nombre', 'like', '%' . $this->search . '%');
                })
                ->orWhereHas('categoria', function ($query) {
                    $query->where('nombre', 'like', '%' . $this->search . '%');
                })
                ->orWhereHas('localizacion', function ($query) {
                    $query->where('nombre', 'like', '%' . $this->search . '%');
                })
                ->orWhereHas('encargado', function ($query) {
                    $query->where('nombre', 'like', '%' . $this->search . '%');
                })
                ->when($this->sort == 'id_marca', function ($query) {
                    $query->orderBy(
                        MarcaModel::select('nombre')
                            ->whereColumn('marca.id', 'material.id_marca'),
                        $this->direc
                    );
                })
            // Ordenar por nombre del encargado utilizando `id_encargado`
                ->when($this->sort == 'id_encargado', function ($query) {
                    $query->orderBy(
                        EncargadoModel::select('nombre')
                            ->whereColumn('encargado.id', 'material.id_encargado'),
                        $this->direc
                    );
                })
            // Ordenar por categoría utilizando `id_categoria`
                ->when($this->sort == 'id_categoria', function ($query) {
                    $query->orderBy(
                        CategoriaModel::select('nombre')
                            ->whereColumn('categoria.id', 'material.id_categoria'),
                        $this->direc
                    );
                })
            // Ordenar por nombre de localizacion utilizando `id_localizacion`
                ->when($this->sort == 'id_localizacion', function ($query) {
                    $query->orderBy(
                        localizacion::select('nombre')
                            ->whereColumn('localizacion.id', 'material.id_localizacion'),
                        $this->direc
                    );
                })
            // Para cualquier otro campo de ordenación
                ->when($this->sort != 'id_marca' && $this->sort != 'id_encargado' && $this->sort != 'id_categoria' && $this->sort != 'id_localizacion', function ($query) {
                    $query->orderBy($this->sort, $this->direc);
                })
                ->paginate($this->cant)
                ->withQueryString();

        } elseif (auth()->user()->id_rol == 7 && $this->SelectEncargado == 0) {
            // Si es jefe y no seleccionó ningún encargado, devolver datos vacíos.
            $datos = new LengthAwarePaginator([], 0, $this->cant);
        } else {
            // Filtrar materiales por encargado según el usuario logueado o el seleccionado.
            $encargadoId = auth()->user()->id_rol == 7 && $this->SelectEncargado > 0
            ? $this->SelectEncargado
            : $this->UserId;

            // Consulta para cargar los datos.
            $datos = MaterialModel::where('id_encargado', $encargadoId)
                ->where(function ($query) {
                    $query->where('nombre', 'like', '%' . $this->search . '%')
                        ->orWhere('modelo', 'like', '%' . $this->search . '%')
                        ->orWhere('stock', 'like', '%' . $this->search . '%')
                        ->orWhere('descripcion', 'like', '%' . $this->search . '%')
                        ->orWhereHas('marca', function ($query) {
                            $query->where('nombre', 'like', '%' . $this->search . '%');
                        })
                        ->orWhereHas('categoria', function ($query) {
                            $query->where('nombre', 'like', '%' . $this->search . '%');
                        })
                        ->orWhereHas('localizacion', function ($query) {
                            $query->where('nombre', 'like', '%' . $this->search . '%');
                        })
                        ->orWhereHas('encargado', function ($query) {
                            $query->where('nombre', 'like', '%' . $this->search . '%')
                                ->orWhere('apellido_p', 'like', '%' . $this->search . '%')
                                ->orWhere('apellido_m', 'like', '%' . $this->search . '%');
                        });
                })
                ->when($this->sort == 'id_marca', function ($query) {
                    $query->orderBy(
                        MarcaModel::select('nombre')
                            ->whereColumn('marca.id', 'material.id_marca'),
                        $this->direc
                    );
                })
                ->when($this->sort == 'id_categoria', function ($query) {
                    $query->orderBy(
                        CategoriaModel::select('nombre')
                            ->whereColumn('categoria.id', 'material.id_categoria'),
                        $this->direc
                    );
                })
                ->when($this->sort == 'id_localizacion', function ($query) {
                    $query->orderBy(
                        localizacion::select('nombre')
                            ->whereColumn('localizacion.id', 'material.id_localizacion'),
                        $this->direc
                    );
                })
                ->when($this->sort == 'id_encargado', function ($query) {
                    $query->orderBy(
                        EncargadoModel::select('nombre')
                            ->whereColumn('encargado.id', 'material.id_encargado'),
                        $this->direc
                    );
                }, function ($query) {
                    $query->orderBy($this->sort, $this->direc);
                })
                ->when(in_array($this->sort, ['nombre', 'modelo', 'stock', 'descripcion']), function ($query) {
                    $query->orderBy($this->sort, $this->direc);
                })
                ->paginate($this->cant)
                ->withQueryString();
        }
        return view('livewire.material.index', compact('datos'));
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
    // public function destroyPost($id)
    // {
    //     $cat = MaterialModel::find($id);
    //     if ($cat) {
    //         $cat->delete();
    //     }
    // }

    public function destroyPost($id)
    {

        DB::beginTransaction();

        try {
            // Obtenemos el nombre del material que se va a borrar
            $material = DB::table('material')->where('id', $id)->first();

            if (!$material) {
                return response()->json(['error' => 'Material no encontrado.'], 404);
                DB::rollBack();
            }

            // Primero, obtenemos los registros de `detalle_prestamo` que están vinculados al `id_material`
            $detallePrestamos = DB::table('detalle_prestamo')
                ->where('id_material', $id)
                ->get();

            // Si hay registros en detalle_prestamo, actualizamos el campo 'observacion'
            foreach ($detallePrestamos as $detalle) {
                // Concatenamos el nombre del material a la observación
                $nuevaObservacion = $detalle->observacion . ' - Material Prestado: ' . $material->nombre;

                // Actualizamos la observación y desvinculamos el material
                DB::table('detalle_prestamo')
                    ->where('id', $detalle->id)
                    ->update([
                        'id_material' => null,
                        'observacion' => $nuevaObservacion,
                    ]);
            }

            // Ahora eliminamos el material en la tabla `material`
            DB::table('material')
                ->where('id', $id)
                ->delete();

            DB::commit();
            return response()->json(['message' => 'Material eliminado correctamente.'], 200);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                'error' => 'Error al eliminar el material: ' . $e->getMessage(),
            ], 500);
        }
    }
}
