<?php

namespace App\Livewire\Rol;

use App\Models\RolModel;
use Livewire\Component;

class Create extends Component
{
    public $open;
    public $nombre = '';

    protected $rules = [
        'nombre' => 'required|max:35|unique:roles',
    ];

    protected $listeners = ['saveConfirmed2' => 'save'];

    public function update($propertyname)
    {
        $this->validateOnly($propertyname);
    }

    public function confirmSave2()
    {
        // Realiza la validación
        $this->validate();

        // Si la validación es exitosa, dispara el evento para mostrar SweetAlert
        $this->dispatch('showConfirmation2', $this->nombre);
    }

    public function save()
    {

        $this->validate();

        RolModel::create([
            'nombre' => $this->nombre,
        ]);

        $this->reset(['open', 'nombre']);
        $this->dispatch('render');
        $this->dispatch('alert', 'El rol se ha guardado con exito.');
    }

    public function render()
    {
        return view('livewire.rol.create');
    }
}
