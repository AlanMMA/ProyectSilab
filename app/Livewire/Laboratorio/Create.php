<?php

namespace App\Livewire\Laboratorio;

use App\Models\LaboratorioModel;
use Livewire\Component;

class Create extends Component
{

    public $open;
    public $nombre = '', $limite_enc = 0;

    protected $rules = [
        'nombre' => 'required|max:25|unique:laboratorio|regex:/^[\pL\s]+$/u',
    ];

    protected $listeners = ['saveConfirmed2' => 'save'];

    public function update($propertyname)
    {
        $this->validateOnly($propertyname);
    }

    public function confirmSave2()
    {

        $this->limite_enc = 1;

        // Realiza la validación
        $this->validate();

        // Si la validación es exitosa, dispara el evento para mostrar SweetAlert
        $this->dispatch('showConfirmation2', $this->nombre);
    }

    public function save()
    {

        $this->validate();

        LaboratorioModel::create([
            'nombre' => $this->nombre,
            'num_max_encargado' => $this->limite_enc,
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
