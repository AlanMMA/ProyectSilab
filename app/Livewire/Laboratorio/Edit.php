<?php

namespace App\Livewire\Laboratorio;

use App\Models\LaboratorioModel;
use Livewire\Component;

class Edit extends Component
{

    public $open = false;
    public $dato;
    public $oldDato; // Almacena el valor original del dato

    protected $rules = [
        'dato.nombre' => 'required|max:25|unique:laboratorio,nombre|regex:/^[\pL\s]+$/u',
    ];

    protected $listeners = ['saveConfirmed' => 'save'];

    public function mount(LaboratorioModel $dato)
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

        $laboratorio = LaboratorioModel::find($this->dato['id']);
        $laboratorio->fill($this->dato);
        $laboratorio->save();

        // Actualiza el valor de oldDato con el nombre nuevo guardado
        $this->oldDato = $laboratorio->nombre;

        $this->reset(['open']);
        $this->dispatch('render');
        $this->dispatch('alert', 'El laboratorio se ha modificado con exito.');

    }

    public function render()
    {
        return view('livewire.laboratorio.edit');
    }
}
