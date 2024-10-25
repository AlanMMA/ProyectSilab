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
    public $initialDato;

    protected function rules()
    {
        return [
            'dato.nombre' => 'required|min:4|max:15|regex:/^[\pL\s]+$/u',
            'dato.apellido_p' => 'required|min:4|max:15|regex:/^[\pL\s]+$/u',
            'dato.apellido_m' => 'required|min:4|max:15|regex:/^[\pL\s]+$/u',
            'dato.id_area' => 'required|numeric|min:1',
            'dato.tipo' => 'required|in:docente,alumno',
            'dato.numero_control' => $this->dato['tipo'] === 'alumno' ? 'required|max:9' : 'nullable|max:9',
        ];
    }

    protected $listeners = ['saveConfirmed' => 'save'];

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
        $this->initialDato = $dato->toArray();
    }

    public function loadData()
    {
        $solicitante = SolicitanteModel::find($this->dato['id']);
        $this->dato = $solicitante->toArray();
        $this->resetErrorBag();
        $this->open = true;
    }

    public function resetForm()
    {
        $this->dato = $this->initialDato;
        $this->open = false;
        $this->resetErrorBag();
    }

    public function save()
    {

        $this->validate();
        $solicitante = SolicitanteModel::find($this->dato['id']);
        if ($this->dato['tipo'] === 'docente') {
            $this->dato['numero_control'] = null;
        }
        $solicitante->fill($this->dato);
        $solicitante->save();
        $this->reset(['open']);
        $this->dispatch('render');
        $this->dispatch('alert', 'La solicitante se ha modificado con exito.');
        $this->dato = $solicitante->toArray();
    }
    public function render()
    {
        $areas = AreaModel::pluck('nombre', 'id');
        return view('livewire.solicitante.edit', compact('areas'));
    }
}
