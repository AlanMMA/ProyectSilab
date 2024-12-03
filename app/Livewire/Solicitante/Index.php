<?php

namespace App\Livewire\Solicitante;

use App\Models\AreaModel;
use App\Models\EncargadoModel;
use App\Models\SolicitanteModel;
use Illuminate\Support\Facades\DB;
use Livewire\Component;
use Livewire\WithPagination;

class Index extends Component
{
    public $search;
    public $sort = 'id';
    public $nombre, $apellido_p, $apellido_m, $id_area, $tipo,
        $numero_control;
    public $direc = 'asc';
    public $cant = '10';
    protected $listeners = ['render' => 'render', 'destroyPost', 'deletionError'];
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
        $datos = SolicitanteModel::where('nombre', 'like', '%' . $this->search . '%')
            ->orWhere('apellido_p', 'like', '%' . $this->search . '%')
            ->orWhere('apellido_m', 'like', '%' . $this->search . '%')
            ->orWhere('tipo', 'like', '%' . $this->search . '%')
            ->orWhereHas('area', function ($query) {
                $query->where('nombre', 'like', '%' . $this->search . '%');
            })
            ->when($this->sort == 'id_area', function ($query) {
                $query->orderBy(
                    AreaModel::select('nombre')
                        ->whereColumn('area.id', 'solicitante.id_area'),
                    $this->direc
                );
            }, function ($query) {
                $query->orderBy($this->sort, $this->direc);
            })
            ->paginate($this->cant)
            ->withQueryString();
        return view('livewire.solicitante.index', compact('datos'));
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
        // Buscar el solicitante
        $solicitante = SolicitanteModel::find($id);
        $encarg = auth()->user()->id_encargado;

        if (!$solicitante) {
            $this->dispatch('deletionError', 'El solicitante no existe.');
            return;
        }

        // Verificar si el solicitante está relacionado con préstamos
        $prestamosRelacionados = DB::table('prestamo')
            ->where('id_solicitante', $id)
            ->pluck('id');

        if ($prestamosRelacionados->isNotEmpty()) {
            $idsMostrados = $prestamosRelacionados->take(10)->implode(', ');
            $mensajeAdicional = $prestamosRelacionados->count() > 10
                ? ' y más...'
                : '';

            $this->dispatch(
                'deletionError',
                'No se puede eliminar al solicitante, cuenta con los siguientes prestamos pendientes: ' . $idsMostrados . $mensajeAdicional
            );
            return;
        }


        // Si no hay relaciones, proceder a eliminar el solicitante
        try {
            $solicitante->delete();
            $this->dispatch('deletionSuccess', 'Solicitante eliminado correctamente.');
        } catch (\Exception $e) {
            $this->dispatch('deletionError', 'Hubo un error al eliminar el solicitante: ' . $e->getMessage());
        }
    }
}
