<?php

namespace App\Livewire\Categoria;

use App\Models\CategoriaModel;
use Livewire\Component;

class Edit extends Component
{
    public $open = false;
    public $dato;
    public $oldDato; // Almacena el valor original del dato

    protected $rules = [
        'dato.nombre' => 'required|max:10|unique:categoria,nombre',
    ];

    protected $listeners = ['saveConfirmed' => 'save'];

    public function mount(CategoriaModel $dato)
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

        $categoria = CategoriaModel::find($this->dato['id']);
        $categoria->fill($this->dato);
        $categoria->save();

        // Actualiza el valor de oldDato con el nombre nuevo guardado
        $this->oldDato = $categoria->nombre;

        $this->reset(['open']);
        $this->dispatch('render');
        $this->dispatch('alert', 'La categoria se ha modificado con exito.');

    }

    public function render()
    {
        return view('livewire.categoria.edit');
    }
}
