<?php

namespace App\Livewire\Rol;

use App\Models\AreaModel;
use App\Models\RolModel;
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
        $datos = RolModel::where('nombre', 'like', '%' . $this->search . '%')
        ->orderBy($this->sort, $this->direc)
        ->paginate($this->cant)
        ->withQueryString();
        return view('livewire.rol.index', compact('datos'));
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
        // Buscar el rol
        $rol = RolModel::find($id);
    
        if (!$rol) {
            $this->dispatch('deletionError', 'El rol no existe.');
            return;
        }
    
        // Verificar si el rol está relacionado con usuarios
        $usuariosRelacionados = DB::table('users')
            ->where('id_rol', $id)
            ->pluck('id');
    
            if ($usuariosRelacionados->isNotEmpty()) {
                $idsMostrados = $usuariosRelacionados->take(10)->implode(', ');
                $mensajeAdicional = $usuariosRelacionados->count() > 10 
                    ? ' y más...' 
                    : '';

                $this->dispatch(
                    'deletionError',
                    'No se puede eliminar el rol: está relacionado con los siguientes usuarios: ' . $idsMostrados . $mensajeAdicional
                );
                return;
            }
    
        // Si no hay relaciones, proceder a eliminar el rol
        try {
            $rol->delete();
            $this->dispatch('deletionSuccess', 'Rol eliminado correctamente.');
        } catch (\Exception $e) {
            $this->dispatch('deletionError', 'Hubo un error al eliminar el rol: ' . $e->getMessage());
        }
    }
    
}
