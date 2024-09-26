<?php

namespace App\Livewire\Rol;

use App\Models\RolModel;
use Livewire\Component;

class Edit extends Component
{
    public $open;
    public $dato;

    protected $rules = [
        'dato.nombre' => 'required|max:10'
    ];
    

    public function mount(RolModel $dato){
        $this->dato = $dato->toArray();
    }   


    public function save(){
        
        $this->validate(); 
        $categoria = RolModel::find($this->dato['id']);
        $categoria->fill($this->dato);
        $categoria->save();
        $this->reset(['open']);
        $this->dispatch('render');
        $this->dispatch('alert', 'La categoria se ha modificado con exito.');

    }

    public function render()
    {
        return view('livewire.rol.edit');
    }
}
