<?php

namespace App\Livewire\Laboratorio;

use App\Models\LaboratorioModel;
use Livewire\Component;

class Create extends Component
{

    public $open;
    public $nombre;

    protected $rules = [
        'nombre' => 'required|max:25|unique:laboratorio|regex:/^[\pL\s]+$/u',
    ];

    public function update($propertyname)
    {
        $this->validateOnly($propertyname);
    }

    public function save()
    {

        $this->validate();

        LaboratorioModel::create([
            'nombre' => $this->nombre,
        ]);

        $this->reset(['open', 'nombre']);
        $this->dispatch('render');
        $this->dispatch('alert', 'El laboratorio se ha guardado con exito.');
    }

    public function render()
    {
        return view('livewire.laboratorio.create');
    }
}
