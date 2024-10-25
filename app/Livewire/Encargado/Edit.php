<?php

namespace App\Livewire\Encargado;

use App\Models\EncargadoModel;
use App\Models\LaboratorioModel;
use Livewire\Component;

class Edit extends Component
{

    public $open;
    public $dato;

    protected function rules()
    {
        return [
            'dato.nombre' => 'required|min:4|max:20|regex:/^[\pL\s]+$/u',
            'dato.apellido_p' => 'required|min:4|max:20|regex:/^[\pL\s]+$/u',
            'dato.apellido_m' => 'required|min:4|max:20|regex:/^[\pL\s]+$/u',
            'dato.id_laboratorio' => 'required|numeric',
        ];
    }

    protected $listeners = ['saveConfirmed' => 'save'];

    protected $messages = [
        'nombre.regex' => 'El nombre solo puede contener letras y espacios.',
        'apellido_p.regex' => 'El apellido paterno solo puede contener letras y espacios.',
        'apellido_m.regex' => 'El apellido materno solo puede contener letras y espacios.',
    ];

    public function update($propertyname)
    {
        $this->validateOnly($propertyname);
    }
    public function mount(EncargadoModel $dato)
    {
        $this->dato = $dato->toArray();
    }

    public function save()
    {
        $this->validate();
        $categoria = EncargadoModel::find($this->dato['id']);
        $categoria->fill($this->dato);
        $categoria->save();
        $this->reset(['open']);
        $this->dispatch('render');
        $this->dispatch('alert', 'El encargado se ha modificado con exito.');
    }

    public function render()
    {
        $laboratorios = LaboratorioModel::pluck('nombre', 'id');
        return view('livewire.encargado.edit', compact('laboratorios'));
    }
}
