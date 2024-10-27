<?php

namespace App\Livewire\Encargado;

use App\Models\EncargadoModel;
use App\Models\LaboratorioModel;
use Livewire\Component;

class Create extends Component
{

    public $open;
    public $nombre, $apellido_p, $apellido_m, $id_laboratorio;
    public $laboratorios;
    //public $tipo = 'docente';

    protected function rules()
    {
        return [
            'nombre' => 'required|min:3|max:20|regex:/^[\pL\s]+$/u',
            'apellido_p' => 'required|min:3|max:20|regex:/^[\pL\s]+$/u',
            'apellido_m' => 'required|min:3|max:20|regex:/^[\pL\s]+$/u',
            'id_laboratorio' => 'required|numeric',
        ];
    }

    protected $listeners = ['saveConfirmed2' => 'save'];

    protected $messages = [
        'nombre.regex' => 'El nombre solo puede contener letras y espacios.',
        'apellido_p.regex' => 'El apellido paterno solo puede contener letras y espacios.',
        'apellido_m.regex' => 'El apellido materno solo puede contener letras y espacios.',
    ];

    public function update($propertyname)
    {
        $this->validateOnly($propertyname);
    }

    public function confirmSave2()
    {
        // Realiza la validación
        $this->validate();

        // Obtiene el nombre del laboratorio a partir del ID seleccionado
        $laboratorioNombre = $this->laboratorios[$this->id_laboratorio] ?? 'No asignado';

        // Si la validación es exitosa, dispara el evento para mostrar SweetAlert
        $this->dispatch('showConfirmation2', [
            'newDatos' => [
                'nombre' => $this->nombre,
                'apellido_p' => $this->apellido_p,
                'apellido_m' => $this->apellido_m,
                'id_laboratorio' => $this->id_laboratorio,
            ],
            'laboratorio_nombre' => $laboratorioNombre, // Enviar el nombre del laboratorio
        ]);
    }

    public function save()
    {
        $this->validate();

        EncargadoModel::create([
            'nombre' => $this->nombre,
            'apellido_p' => $this->apellido_p,
            'apellido_m' => $this->apellido_m,
            'id_laboratorio' => $this->id_laboratorio,
        ]);

        $this->reset(['open', 'nombre', 'apellido_p', 'apellido_m']);
        $this->dispatch('render');
        $this->dispatch('alert', 'El encargado se ha guardado con exito.');
    }

    public function render()
    {
        $this->laboratorios = LaboratorioModel::pluck('nombre', 'id');
        return view('livewire.encargado.create', [
            'laboratorios' => $this->laboratorios,
        ]);
    }
}
