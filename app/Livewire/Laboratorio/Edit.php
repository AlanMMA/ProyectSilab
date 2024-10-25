<?php

namespace App\Livewire\Laboratorio;

use App\Models\LaboratorioModel;
use Livewire\Component;

class Edit extends Component
{

    public $open;
    public $dato;

    protected $rules = [
        'dato.nombre' => 'required|max:25|unique:laboratorio,nombre|regex:/^[\pL\s]+$/u',
    ];

    protected $listeners = ['saveConfirmed' => 'save'];

    public function mount(LaboratorioModel $dato)
    {
        $this->dato = $dato->toArray();
    }

    public function save()
    {

        $this->validate();
        $laboratorio = LaboratorioModel::find($this->dato['id']);
        $laboratorio->fill($this->dato);
        $laboratorio->save();
        $this->reset(['open']);
        $this->dispatch('render');
        $this->dispatch('alert', 'El laboratorio se ha modificado con exito.');

    }

    public function render()
    {
        return view('livewire.laboratorio.edit');
    }
}
