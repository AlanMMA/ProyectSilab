<?php

namespace App\Livewire\Area;

use App\Models\AreaModel;
use Livewire\Component;

class Edit extends Component
{

    public $open;
    public $dato;

    protected $rules = [
        'dato.nombre' => 'required|max:10|unique:area,nombre'
    ];
    

    public function mount(AreaModel $dato){
        $this->dato = $dato->toArray();
    }   

    public function save(){
        
        $this->validate(); 
        $categoria = AreaModel::find($this->dato['id']);
        $categoria->fill($this->dato);
        $categoria->save();
        $this->reset(['open']);
        $this->dispatch('render');
        $this->dispatch('alert', 'La categoria se ha modificado con exito.');

    }

    public function render()
    {
        return view('livewire.area.edit');
    }
}
