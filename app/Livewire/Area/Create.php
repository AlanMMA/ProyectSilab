<?php

namespace App\Livewire\Area;

use App\Models\AreaModel;
use Livewire\Component;

class Create extends Component
{
    public $open;
    public $nombre = '';

    protected $rules = [
        'nombre' => 'required|max:15|unique:area',
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

        AreaModel::create([
            'nombre' => $this->nombre,
        ]);

        $this->reset(['open', 'nombre']);
        $this->dispatch('render');
        $this->dispatch('alert', 'El área se ha guardado con exito.');
    }

    public function render()
    {
        return view('livewire.area.create');
    }
}
