<?php

namespace App\Livewire\Marca;

use App\Models\MarcaModel;
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
        $datos = MarcaModel::where('nombre', 'like', '%' . $this->search . '%')
            ->orderBy($this->sort, $this->direc)
            ->paginate($this->cant)
            ->withQueryString();
        return view('livewire.marca.index', compact('datos'));
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
        // Buscar la marca
        $marca = MarcaModel::find($id);
    
        if (!$marca) {
            $this->dispatch('deletionError', 'La marca no existe.');
            return;
        }
    
        // Verificar si la marca está relacionada con materiales
        $materialesRelacionados = DB::table('material')
            ->where('id_marca', $id)
            ->pluck('id');
    
            if ($materialesRelacionados->isNotEmpty()) {
                $idsMostrados = $materialesRelacionados->take(10)->implode(', ');
                $mensajeAdicional = $materialesRelacionados->count() > 10 
                    ? ' y más...' 
                    : '';

                $this->dispatch(
                    'deletionError',
                    'No se puede eliminar la marca, está relacionado con los siguientes materiales: ' . $idsMostrados . $mensajeAdicional
                );
                return;
            }
    
    
    
        // Si no hay relaciones, proceder a eliminar la marca
        try {
            $marca->delete();
            $this->dispatch('deletionSuccess', 'Marca eliminada correctamente.');
        } catch (\Exception $e) {
            $this->dispatch('deletionError', 'Hubo un error al eliminar la marca: ' . $e->getMessage());
        }
    }
    
}
