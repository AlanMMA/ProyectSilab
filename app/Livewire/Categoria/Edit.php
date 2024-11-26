<?php

namespace App\Livewire\Categoria;

use App\Models\CategoriaModel;
use Livewire\Component;

class Edit extends Component
{
    public $open = false;
    public $dato;
    public $oldDato; // Almacena el valor original del dato
    public $oldDato2;

    protected $listeners = ['saveConfirmed' => 'save'];

    protected $rules = [
        'dato.nombre' => 'required|min:3|max:15',
    ];

    public function update($propertyname)
    {
        $this->validateOnly($propertyname);
        
    }

    public function mount(CategoriaModel $dato)
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

    public function resetDatos()
    {
        $categoria = CategoriaModel::find($this->dato['id']);
        $this->dato = $categoria->toArray();
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

        $rules = [
            'dato.nombre' => 'required|max:15',
        ];
        // Agregar la validación única de 'nombre' solo si fue modificado y no es igual al original
        if ($newNombre && strtolower($this->dato['nombre']) !== strtolower($this->oldDato2['nombre'])) {
            $rules['dato.nombre'] = 'required|min:3|max:15|unique:categoria,nombre,' . $this->dato['id'];
        }

        // Realiza la validación con las reglas dinámicas
        $this->validate($rules);

        return true;
    }

    public function save()
    {

        $categoria = CategoriaModel::find($this->dato['id']);
        $categoria->fill($this->dato);
        $categoria->save();

        // Actualiza el valor de oldDato con el nombre nuevo guardado
        $this->oldDato = $categoria->nombre;
        $this->oldDato2 = $categoria->toArray();

        $this->reset(['open']);
        $this->dispatch('render');
        $this->dispatch('alert', 'La categoria se ha modificado con exito.');

    }

    public function render()
    {
        return view('livewire.categoria.edit');
    }
}
