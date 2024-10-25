<?php

namespace App\Livewire\Categoria;

use App\Models\CategoriaModel;
use Livewire\Component;

class Edit extends Component
{
    public $open;
    public $dato;

    protected $rules = [
        'dato.nombre' => 'required|max:10',
    ];

    protected $listeners = ['saveConfirmed' => 'save'];

    public function mount(CategoriaModel $dato)
    {
        $this->dato = $dato->toArray();
    }

    public function save()
    {

        $this->validate();
        $categoria = CategoriaModel::find($this->dato['id']);
        $categoria->fill($this->dato);
        $categoria->save();
        $this->reset(['open']);
        $this->dispatch('render');
        $this->dispatch('alert', 'La categoria se ha modificado con exito.');

    }

    public function render()
    {
        return view('livewire.categoria.edit');
    }
}
