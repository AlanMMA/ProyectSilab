<?php

namespace App\Livewire\Solicitante;

use App\Models\AreaModel;
use App\Models\SolicitanteModel;
use Livewire\Component;

class Create extends Component
{
    public $open;
    public $nombre, $apellido_p, $apellido_m, $id_area, $numero_control;
    public $tipo = 'docente';
    protected function rules()
    {
        return [
            'nombre' => 'required|min:4|max:15|regex:/^[\pL\s]+$/u',
            'apellido_p' => 'required|min:4|max:15|regex:/^[\pL\s]+$/u',
            'apellido_m' => 'required|min:4|max:15|regex:/^[\pL\s]+$/u',
            'id_area' => 'required|numeric',
            'tipo' => 'required|in:docente,alumno',
            'numero_control' => $this->tipo === 'alumno' ? 'required|max:8' : 'nullable|max:8',
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
    public function save()
    {

        $this->validate();

        SolicitanteModel::create([
            'nombre' => $this->nombre,
            'apellido_p' => $this->apellido_p,
            'apellido_m' => $this->apellido_m,
            'id_area' => $this->id_area,
            'tipo' => $this->tipo,
            'numero_control' => $this->numero_control
        ]);

        $this->reset(['open', 'nombre', 'apellido_p','apellido_m', 'numero_control']);
        $this->dispatch('render');
        $this->dispatch('alert', 'La categoria se ha guardado con exito.');
    }

    public function render()
    {
        $areas = AreaModel::pluck('nombre', 'id');
        return view('livewire.solicitante.create', compact('areas'));
    }
}
