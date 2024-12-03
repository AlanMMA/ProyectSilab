<?php

namespace App\Livewire\Categoria;

use App\Models\CategoriaModel;
use Illuminate\Support\Facades\DB;
use Livewire\Component;
use Livewire\WithoutUrlPagination;
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
        $datos = CategoriaModel::where('nombre', 'like', '%' . $this->search . '%')
        ->orderBy($this->sort, $this->direc)
        ->paginate($this->cant)
        ->withQueryString();
        return view('livewire.categoria.index', compact('datos'));
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

    //     if ($this->sort === $sort) {
    //         $this->direc = $this->direc === 'desc' ? 'asc' : 'desc';
    //     } else {
    //         $this->sort = $sort;
    //         $this->direc = 'asc';
    //     }
        
    }

    public function destroyPost($id)
    {
        // Buscar la categoría
        $categoria = CategoriaModel::find($id);
    
        if (!$categoria) {
            $this->dispatch('deletionError', 'La categoría no existe.');
            return;
        }
    
        // Verificar si la categoría está relacionada con materiales
        $materialesRelacionados = DB::table('material')
            ->where('id_categoria', $id)
            ->pluck('id');
    
            if ($materialesRelacionados->isNotEmpty()) {
                $idsMostrados = $materialesRelacionados->take(10)->implode(', ');
                $mensajeAdicional = $materialesRelacionados->count() > 10 
                    ? ' y más...' 
                    : '';

                $this->dispatch(
                    'deletionError',
                    'No se puede eliminar la categoria, está relacionado con los siguientes materiales: ' . $idsMostrados . $mensajeAdicional
                );
                return;
            }
    
        // Si no hay relaciones, proceder a eliminar la categoría
        try {
            $categoria->delete();
            $this->dispatch('deletionSuccess', 'Categoría eliminada correctamente.');
        } catch (\Exception $e) {
            $this->dispatch('deletionError', 'Hubo un error al eliminar la categoría: ' . $e->getMessage());
        }
    }
    
}
