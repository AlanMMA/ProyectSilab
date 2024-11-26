<?php

namespace App\Livewire\Localizacion;

use App\Models\localizacion;
use Livewire\Component;

class Create extends Component
{
    public $open;
    public $nombre = '';

    protected $rules = [
        'nombre' => 'required|min:3|max:20|unique:localizacion|regex:/^[\pL\s]+$/u',
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
        $usuario = auth()->user()->id_encargado;
        localizacion::create([
            'nombre' => $this->nombre,
            'id_encargado' => $usuario
        ]);

        $this->reset(['open', 'nombre']);
        $this->dispatch('render');
        $this->dispatch('alert', 'La marca se ha guardado con exito.');
    }

    public function render()
    {
        return view('livewire.localizacion.create');
    }
}
