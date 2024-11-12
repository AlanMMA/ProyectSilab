<?php

namespace App\Livewire\Laboratorio;

use App\Models\EncargadoModel;
use App\Models\LaboratorioModel;
use Livewire\Component;

class Edit extends Component
{

    public $open = false;
    public $dato, $id_laboratorio;
    public $oldDato; // Almacena el valor original del dato

    protected $rules;

    protected $listeners = ['saveConfirmed' => 'save'];

    public function update($propertyname)
    {
        $this->validateOnly($propertyname);
    }

    public function mount(LaboratorioModel $dato)
    {
        $this->dato = $dato->toArray();
        $this->oldDato = $dato->toArray();
        $this->id_laboratorio = $dato->id; // Asigna el ID del laboratorio
    }

    public function confirmSave()
    {

        $cambios = [];

        $newNombre = $this->dato['nombre'] !== $this->oldDato['nombre'];
        $newLimite = (int) $this->dato['num_max_encargado'] !== (int) $this->oldDato['num_max_encargado'];

        if ($newNombre || $newLimite) {

            /*$cambios[] = "<tr><td><strong>Nombre completo:</strong></td></tr>
            <tr><td>Actual: {$this->oldDato['nombre']}</td></tr>
            <tr><td>Nuevo: {$this->dato['nombre']}</td></tr>
            <tr><td>&nbsp;</td></tr>
            <tr><td><strong>Limite de encargados:</strong></td></tr>
            <tr><td>Actual: {$this->oldDato['num_max_encargado']}</td></tr>
            <tr><td>Nuevo: {$this->dato['num_max_encargado']}</td></tr>
            ";*/

            if ($newNombre && $newLimite) {
                $cambios[] = "<tr><td><strong>Nombre completo:</strong></td></tr>
                <tr><td>Actual: {$this->oldDato['nombre']}</td></tr>
                <tr><td>Nuevo: {$this->dato['nombre']}</td></tr>
                <tr><td>&nbsp;</td></tr>
                <tr><td><strong>Limite de encargados:</strong></td></tr>
                <tr><td>Actual: {$this->oldDato['num_max_encargado']}</td></tr>
                <tr><td>Nuevo: {$this->dato['num_max_encargado']}</td></tr>
                ";
            } else if ($newNombre) {
                $cambios[] = "<tr><td><strong>Nombre completo:</strong></td></tr>
                <tr><td>Actual: {$this->oldDato['nombre']}</td></tr>
                <tr><td>Nuevo: {$this->dato['nombre']}</td></tr>
                ";
            } else if ($newLimite) {
                $cambios[] = "
                <tr><td><strong>Limite de encargados:</strong></td></tr>
                <tr><td>Actual: {$this->oldDato['num_max_encargado']}</td></tr>
                <tr><td>Nuevo: {$this->dato['num_max_encargado']}</td></tr>
                ";
            }
            // Realizar la validación de los cambios
            if ($this->validateChanges($newNombre)) {
                // Solo si la validación pasó, despachamos confirmación
                $mensaje = "<table style='width: 100%; text-align: left;'>" . implode("", $cambios) . "</table>";
                $this->dispatch('showConfirmation', $mensaje);
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
            'dato.num_max_encargado' => 'required|numeric|min:1',
        ];

        // Agregar la validación única de 'nombre' solo si fue modificado y no es igual al original
        if ($newNombre && strtolower($this->dato['nombre']) !== strtolower($this->oldDato['nombre'])) {
            $rules['dato.nombre'] = 'required|max:25|unique:laboratorio,nombre|regex:/^[\pL\s]+$/u';
        } else {
            $rules['dato.nombre'] = 'required|max:25|regex:/^[\pL\s]+$/u';
        }

        // Contar cuántos encargados ya están asignados al laboratorio
        $encargadosActuales = EncargadoModel::where('id_laboratorio', $this->id_laboratorio)->count();
        if ($this->dato['num_max_encargado'] < $encargadosActuales) {
            // Muestra el mensaje con un alert
            $this->dispatch('alert1', 'El número máximo de encargados no puede ser menor al número de encargados actuales.');

            // Restaura el valor original de num_max_encargado
            $this->dato['num_max_encargado'] = $this->oldDato['num_max_encargado'];

            return false;
        }

        // Realiza la validación con las reglas dinámicas
        $this->validate($rules);

        return true;
    }

    public function save()
    {

        $laboratorio = LaboratorioModel::find($this->dato['id']);
        $laboratorio->fill($this->dato);
        $laboratorio->save();

        // Actualiza el valor de oldDato con el nombre nuevo guardado
        $this->oldDato = $laboratorio->toArray();
        $this->dato = $laboratorio->toArray();

        $this->reset(['open']);
        $this->dispatch('render');
        $this->dispatch('alert', 'El laboratorio se ha modificado con exito.');

    }

    public function render()
    {
        return view('livewire.laboratorio.edit');
    }
}
