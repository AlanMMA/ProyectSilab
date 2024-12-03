<?php

namespace App\Livewire\Localizacion;

use App\Models\localizacion;
use Illuminate\Support\Facades\DB;
use Livewire\Component;
use Livewire\WithPagination;

class Index extends Component
{
    public $search;
    public $sort = 'id';
    public $direc = 'asc';
    public $cant = '10';
    public $usuario;
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
        // Buscar la localización
        $localizacion = localizacion::find($id);
    
        if (!$localizacion) {
            $this->dispatch('deletionError', 'La localización no existe.');
            return;
        }
    
        // Verificar si la localización está relacionada con materiales
        $materialesRelacionados = DB::table('material')
            ->where('id_localizacion', $id)
            ->pluck('id');
    
            if ($materialesRelacionados->isNotEmpty()) {
                $idsMostrados = $materialesRelacionados->take(10)->implode(', ');
                $mensajeAdicional = $materialesRelacionados->count() > 10 
                    ? ' y más...' 
                    : '';

                $this->dispatch(
                    'deletionError',
                    'No se puede eliminar la localización, está relacionado con los siguientes materiales: ' . $idsMostrados . $mensajeAdicional
                );
                return;
            }
    
    
        // Si no hay relaciones, proceder a eliminar la localización
        try {
            $localizacion->delete();
            $this->dispatch('deletionSuccess', 'Localización eliminada correctamente.');
        } catch (\Exception $e) {
            $this->dispatch('deletionError', 'Hubo un error al eliminar la localización: ' . $e->getMessage());
        }
    }
    


    public function render()
    {           
        $usuario = auth()->user()->id_encargado;
        $datos = localizacion::where('nombre', 'like', '%' . $this->search . '%')
            ->where('id_encargado', $usuario)
            ->orderBy($this->sort, $this->direc)
            ->paginate($this->cant)
            ->withQueryString();
        return view('livewire.localizacion.index', compact('datos'));
    }
}
