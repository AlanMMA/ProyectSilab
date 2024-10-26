<?php

namespace App\Livewire\Marca;

use App\Models\MarcaModel;
use Livewire\Component;

class Edit extends Component
{
    public $open = false;
    public $dato;
    public $oldDato; // Almacena el valor original del dato

    protected $rules = [
        'dato.nombre' => 'required|max:20|unique:marca,nombre|regex:/^[\pL\s]+$/u',
    ];

    protected $listeners = ['saveConfirmed' => 'save'];

    public function mount(MarcaModel $dato)
    {
        $this->dato = $dato->toArray();
        $this->oldDato = $dato->nombre; // Almacena el nombre original
    }

    public function confirmSave()
    {
        // Realiza la validación
        $this->validate();

        // Despacha el evento de SweetAlert con el nombre original (oldDato)
        $this->dispatch('showConfirmation', $this->oldDato, $this->dato['nombre']);
    }

    public function save()
    {

        $marca = MarcaModel::find($this->dato['id']);
        $marca->fill($this->dato);
        $marca->save();

        // Actualiza el valor de oldDato con el nombre nuevo guardado
        $this->oldDato = $marca->nombre;

        $this->reset(['open']);
        $this->dispatch('render');
        $this->dispatch('alert', 'La marca se ha modificado con exito.');

    }
    public function render()
    {
        return view('livewire.marca.edit');
    }
}
