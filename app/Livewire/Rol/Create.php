<?php

namespace App\Livewire\Rol;

use App\Models\RolModel;
use Livewire\Component;

class Create extends Component
{
    public $open;
    public $nombre;

    protected $rules = [
        'nombre' => 'required|max:10'
    ];

    public function update($propertyname){
        $this->validateOnly($propertyname);
    }
    public function save(){

        $this->validate();

        RolModel::create([
             'nombre' => $this->nombre
            ]);

        $this->reset(['open', 'nombre']);
        $this->dispatch('render');
        $this->dispatch('alert', 'La categoria se ha guardado con exito.');
        }

    public function render()
    {
        return view('livewire.rol.create');
    }
}
