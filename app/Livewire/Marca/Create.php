<?php

namespace App\Livewire\Marca;

use App\Models\MarcaModel;
use Livewire\Component;

class Create extends Component
{

    public $open;
    public $nombre;

    protected $rules = [
        'nombre' => 'required|max:20|unique:laboratorio|regex:/^[\pL\s]+$/u',
    ];

    public function update($propertyname)
    {
        $this->validateOnly($propertyname);
    }

    public function save()
    {

        $this->validate();

        MarcaModel::create([
            'nombre' => $this->nombre,
        ]);

        $this->reset(['open', 'nombre']);
        $this->dispatch('render');
        $this->dispatch('alert', 'La marca se ha guardado con exito.');
    }

    public function render()
    {
        return view('livewire.marca.create');
    }
}
