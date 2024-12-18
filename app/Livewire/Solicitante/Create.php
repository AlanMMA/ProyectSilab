<?php

namespace App\Livewire\Solicitante;

use App\Models\AreaModel;
use App\Models\SolicitanteModel;
use Livewire\Component;

class Create extends Component
{
    public $open;
    public $nombre, $apellido_p, $apellido_m, $id_area2, $numero_control, $areas;
    public $tipo;

    protected function rules()
    {
        return [
            'nombre' => 'required|min:4|max:15|regex:/^[\pL\s]+$/u',
            'apellido_p' => 'required|min:4|max:15|regex:/^[\pL\s]+$/u',
            'apellido_m' => 'required|min:4|max:15|regex:/^[\pL\s]+$/u',
            'id_area2' => 'required|numeric|min:1',
            'tipo' => 'required|in:Docente,Alumno',
            'numero_control' => $this->tipo === 'Alumno' ? 'required|min:7|max:9' : 'nullable|min:7|max:9',
        ];
    }

    protected $messages = [
        'nombre.regex' => 'El nombre solo puede contener letras y espacios.',
        'apellido_p.regex' => 'El apellido paterno solo puede contener letras y espacios.',
        'apellido_m.regex' => 'El apellido materno solo puede contener letras y espacios.',
        'tipo.in' => 'El tipo debe ser "Docente" o "Alumno".',
        'numero_control.required' => 'El número de control es requerido cuando el tipo es "Alumno".',
    ];

    public function update($propertyname)
    {
        $this->validateOnly($propertyname);
    }

    protected $listeners = ['saveConfirmed2' => 'save'];

    public function confirmSave2()
    {
        // Realiza la validación
        $this->validate();

        // Obtiene el nombre del laboratorio a partir del ID seleccionado
        $areaNombre = $this->areas[$this->id_area2] ?? 'No asignado';

        // Si la validación es exitosa, dispara el evento para mostrar SweetAlert
        $this->dispatch('showConfirmation2', [
            'newDatos' => [
                'nombre' => $this->nombre,
                'apellido_p' => $this->apellido_p,
                'apellido_m' => $this->apellido_m,
                'id_area' => $this->id_area2,
                'tipo' => $this->tipo,
                'numero_control' => $this->numero_control,
            ],
            'area_nombre' => $areaNombre, // Enviar el nombre del laboratorio
        ]);
    }

    public function save()
    {

        $this->validate();

        SolicitanteModel::create([
            'nombre' => $this->nombre,
            'apellido_p' => $this->apellido_p,
            'apellido_m' => $this->apellido_m,
            'id_area' => $this->id_area2,
            'tipo' => $this->tipo,
            'numero_control' => $this->numero_control,
        ]);

        $this->reset(['open', 'nombre', 'apellido_p', 'apellido_m', 'numero_control']);
        $this->dispatch('render');
        $this->dispatch('alert', 'El solicitante se ha guardado con exito.');
    }

    public function mount()
    {
        $this->areas = AreaModel::pluck('nombre', 'id')->toArray();
    }

    public function render()
    {
        $areas = AreaModel::pluck('nombre', 'id');
        return view('livewire.solicitante.create', compact('areas'));
    }
}
