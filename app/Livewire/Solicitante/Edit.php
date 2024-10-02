<?php

namespace App\Livewire\Solicitante;

use App\Models\AreaModel;
use App\Models\SolicitanteModel;
use Livewire\Component;

class Edit extends Component
{
    public $open;
    public $dato;
    public $name, $email, $id_rol, $tipo;

    protected function rules()
    {
        return [
            'dato.nombre' => 'required|min:4|max:15|regex:/^[\pL\s]+$/u',
            'dato.apellido_p' => 'required|min:4|max:15|regex:/^[\pL\s]+$/u',
            'dato.apellido_m' => 'required|min:4|max:15|regex:/^[\pL\s]+$/u',
            'dato.id_area' => 'required|numeric',
            'dato.tipo' => 'required|in:docente,alumno',
            'dato.numero_control' => $this->dato['tipo'] === 'alumno' ? 'required|max:9' : 'nullable|max:9',
        ];
    }

    protected $messages = [
        'nombre.regex' => 'El nombre solo puede contener letras y espacios.',
        'apellido_p.regex' => 'El apellido paterno solo puede contener letras y espacios.',
        'apellido_m.regex' => 'El apellido materno solo puede contener letras y espacios.',
        'tipo.in' => 'El tipo debe ser "docente" o "alumno".',
        'numero_control.required' => 'El número de control es requerido cuando el tipo es "alumno".',
    ];

    public function update($propertyname)
    {
        $this->validateOnly($propertyname);
    }


    public function mount(SolicitanteModel $dato)
    {
        $this->dato = $dato->toArray();
        $this->tipo = $this->dato['tipo'];
    }


    public function save()
    {

        $this->validate();
        $categoria = SolicitanteModel::find($this->dato['id']);
        $categoria->fill($this->dato);
        $categoria->save();
        $this->reset(['open']);
        $this->dispatch('render');
        $this->dispatch('alert', 'La categoria se ha modificado con exito.');
    }
    public function render()
    {
        $areas = AreaModel::pluck('nombre', 'id');
        return view('livewire.solicitante.edit', compact('areas'));
    }
}
