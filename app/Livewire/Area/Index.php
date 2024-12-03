<?php

namespace App\Livewire\Area;

use App\Models\AreaModel;
use Illuminate\Support\Facades\DB;
use Livewire\Component;
use Livewire\WithPagination;

class Index extends Component
{
    public $search;
    public $sort = 'id';
    public $direc = 'asc';
    public $cant = '10';
    protected $listeners = ['render' => 'render', 'destroyPost'];
    use WithPagination;


    public function mount(){
        $this->sort = 'id';
        $this->direc = 'asc';
    }
    public function updatingSearch(){
        $this->resetPage();
    }

    public function render()
    {
        $datos = AreaModel::where('nombre', 'like', '%' . $this->search . '%')
        ->orderBy($this->sort, $this->direc)
        ->paginate($this->cant)
        ->withQueryString();
        return view('livewire.area.index', compact('datos'));
    }

    public function order($sort){
        if ($this->sort == $sort) {
            if ($this->direc == 'desc') {
                $this->direc = 'asc';
            } else {
                $this->direc='desc';
            }
        } else {
            $this->sort = $sort;
            $this->direc = 'asc';
        }
        
    }

    public function destroyPost($id)
    {
        // Buscar el área
        $area = AreaModel::find($id);
    
        if (!$area) {
            $this->dispatch('deletionError', 'El área no existe.');
            return;
        }
    
        // Verificar si el área está relacionada con solicitantes
        $solicitantesRelacionados = DB::table('solicitante')
            ->where('id_area', $id)
            ->pluck('id');
    
            if ($solicitantesRelacionados->isNotEmpty()) {
                $idsMostrados = $solicitantesRelacionados->take(10)->implode(', ');
                $mensajeAdicional = $solicitantesRelacionados->count() > 10 
                    ? ' y más...' 
                    : '';

                $this->dispatch(
                    'deletionError',
                    'No se puede eliminar el area, está relacionado con los siguientes solicitantes: ' . $idsMostrados . $mensajeAdicional
                );
                return;
            }
    
        // Si no hay relaciones, proceder a eliminar el área
        try {
            $area->delete();
            $this->dispatch('deletionSuccess', 'Área eliminada correctamente.');
        } catch (\Exception $e) {
            $this->dispatch('deletionError', 'Hubo un error al eliminar el área: ' . $e->getMessage());
        }
    }
    

}
