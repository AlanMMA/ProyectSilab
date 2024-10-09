<?php

namespace App\Livewire\Area;

use App\Models\AreaModel;
use Livewire\Component;

class Create extends Component
{

    public $open;
    public $nombre = '';

    protected $rules = [
        'nombre' => 'required|max:10|unique:area'
    ];

    public function update($propertyname){
        $this->validateOnly($propertyname);
    }
    public function save(){

        $this->validate();

        AreaModel::create([
             'nombre' => $this->nombre
            ]);

        $this->reset(['open', 'nombre']);
        $this->dispatch('render');
        $this->dispatch('alert', 'La categoria se ha guardado con exito.');
        }

    public function render()
    {
        return view('livewire.area.create');
    }
}
