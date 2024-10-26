<?php

namespace App\Livewire\Encargado;

use App\Models\EncargadoModel;
use App\Models\LaboratorioModel;
use Livewire\Component;

class Edit extends Component
{

    public $open = false;
    public $dato;
    public $oldDato; // Almacena el valor original del dato

    protected function rules()
    {
        return [
            'dato.nombre' => 'required|min:3|max:20|regex:/^[\pL\s]+$/u',
            'dato.apellido_p' => 'required|min:3|max:20|regex:/^[\pL\s]+$/u',
            'dato.apellido_m' => 'required|min:3|max:20|regex:/^[\pL\s]+$/u',
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
        // Convierte el modelo en un arreglo para acceder a todas sus propiedades
        $this->dato = $dato->toArray();

        // Almacena los datos originales para la comparación
        $this->oldDato = $dato->toArray();
    }

    public function confirmSave()
    {
        $this->validate();

        // Envía los datos originales y los actuales como arreglos
        $this->dispatch('showConfirmation', [
            'oldDatos' => $this->oldDato,
            'newDatos' => $this->dato,
        ]);
    }

    public function save()
    {
        $encargado = EncargadoModel::find($this->dato['id']);
        $encargado->fill($this->dato);
        $encargado->save();

        // Actualiza el valor de oldDato con el nombre nuevo guardado
        $this->oldDato = $encargado->toArray();

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
