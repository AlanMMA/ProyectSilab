<?php

namespace App\Livewire\Area;

use App\Models\AreaModel;
use Livewire\Component;

class Edit extends Component
{
    public $open = false;
    public $dato;
    public $oldDato; // Almacena el valor original del dato
    public $oldDato2;

    protected $rules = [
        'dato.nombre' => 'required|max:15|unique:area,nombre',
    ];

    protected $listeners = ['saveConfirmed' => 'save'];

    public function mount(AreaModel $dato)
    {
        $this->dato = $dato->toArray();
        $this->oldDato = $dato->nombre; // Almacena el nombre original
        $this->oldDato2 = $dato->toArray();
    }

    public function confirmSave()
    {

        $newNombre = $this->dato['nombre'] !== $this->oldDato2['nombre'];

        if ($newNombre) {

            // Realiza la validación
            $this->validate();

            // Despacha el evento de SweetAlert con el nombre original (oldDato)
            $this->dispatch('showConfirmation', $this->oldDato, $this->dato['nombre']);
        } else {
            // Si no hubo cambios, muestra mensaje de que no se realizaron cambios
            $this->reset(['open']);
            $this->dispatch('alert', 'No se realizaron cambios.');
        }
    }

    public function save()
    {
        // Encuentra el área a editar en la base de datos
        $categoria = AreaModel::find($this->dato['id']);
        $categoria->fill($this->dato);
        $categoria->save();

        // Actualiza el valor de oldDato con el nombre nuevo guardado
        $this->oldDato = $categoria->nombre;
        $this->oldDato2 = $categoria->toArray();

        // Cierra el modal y despacha eventos necesarios
        $this->reset(['open']);
        $this->dispatch('render');
        $this->dispatch('alert', 'El área se ha modificado con éxito.');
    }

    public function render()
    {
        return view('livewire.area.edit');
    }
}
