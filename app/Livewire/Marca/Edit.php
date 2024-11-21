<?php

namespace App\Livewire\Marca;

use App\Models\MarcaModel;
use Livewire\Component;

class Edit extends Component
{
    public $open = false;
    public $dato;
    public $oldDato; // Almacena el valor original del dato
    public $oldDato2;

    protected $listeners = ['saveConfirmed' => 'save'];

    public function mount(MarcaModel $dato)
    {
        $this->dato = $dato->toArray();
        $this->oldDato = $dato->nombre; // Almacena el nombre original
        $this->oldDato2 = $dato->toArray();
    }

    public function openModal()
    {
        $this->resetDatos(); // Llama a resetDatos cada vez que se abre el modal
        $this->open = true;
    }

    // Nueva función para restablecer los datos al abrir el modal
    public function resetDatos()
    {
        $marca = MarcaModel::find($this->dato['id']);
        $this->dato = $marca->toArray();
    }

    public function confirmSave()
    {

        $newNombre = $this->dato['nombre'] !== $this->oldDato2['nombre'];

        if ($newNombre) {
            // Realizar la validación de los cambios
            if ($this->validateChanges($newNombre)) {
                // Solo si la validación pasó, despachamos confirmación
                $this->dispatch('showConfirmation', $this->oldDato, $this->dato['nombre']);
            }

        } else {
            // Si no hubo cambios, muestra mensaje de que no se realizaron cambios
            $this->reset(['open']);
            $this->dispatch('alert', 'No se realizaron cambios.');
        }

    }

    private function validateChanges($newNombre)
    {

        // Agregar la validación única de 'nombre' solo si fue modificado y no es igual al original
        if ($newNombre && strtolower($this->dato['nombre']) !== strtolower($this->oldDato2['nombre'])) {
            $rules['dato.nombre'] = 'required|max:20|unique:marca,nombre|regex:/^[\pL\s]+$/u';
        } else {
            $rules['dato.nombre'] = 'required|max:20|regex:/^[\pL\s]+$/u';
        }

        // Realiza la validación con las reglas dinámicas
        $this->validate($rules);

        return true;
    }

    public function save()
    {

        $marca = MarcaModel::find($this->dato['id']);
        $marca->fill($this->dato);
        $marca->save();

        // Actualiza el valor de oldDato con el nombre nuevo guardado
        $this->oldDato = $marca->nombre;
        $this->oldDato2 = $marca->toArray();

        $this->reset(['open']);
        $this->dispatch('render');
        $this->dispatch('alert', 'La marca se ha modificado con exito.');

    }
    
    public function render()
    {
        return view('livewire.marca.edit');
    }
}
