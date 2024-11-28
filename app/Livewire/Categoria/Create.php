<?php

namespace App\Livewire\Categoria;

use App\Models\CategoriaModel;
use Livewire\Component;

class Create extends Component
{
    public $open;
    public $nombre = '';

    protected $rules = [
        'nombre' => 'required|min:4|max:50|unique:categoria',
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

        CategoriaModel::create([
            'nombre' => $this->nombre,
        ]);

        $this->reset(['open', 'nombre']);
        $this->dispatch('render');
        $this->dispatch('alert', 'La categoria se ha guardado con exito.');
    }

    public function render()
    {
        return view('livewire.categoria.create');
    }
}
