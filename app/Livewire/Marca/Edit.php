<?php

namespace App\Livewire\Marca;

use App\Models\MarcaModel;
use Livewire\Component;

class Edit extends Component
{
    public $open;
    public $dato;

    protected $rules = [
        'dato.nombre' => 'required|max:20|unique:laboratorio,nombre|regex:/^[\pL\s]+$/u',
    ];

    protected $listeners = ['saveConfirmed' => 'save'];

    public function mount(MarcaModel $dato)
    {
        $this->dato = $dato->toArray();
    }

    public function save()
    {

        $this->validate();
        $laboratorio = MarcaModel::find($this->dato['id']);
        $laboratorio->fill($this->dato);
        $laboratorio->save();
        $this->reset(['open']);
        $this->dispatch('render');
        $this->dispatch('alert', 'La marca se ha modificado con exito.');

    }
    public function render()
    {
        return view('livewire.marca.edit');
    }
}
