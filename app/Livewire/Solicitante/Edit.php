<?php

namespace App\Livewire\Solicitante;

use App\Models\AreaModel;
use App\Models\SolicitanteModel;
use Livewire\Component;

class Edit extends Component
{
    public $open;
    public $dato;
    public $name, $email, $id_rol, $tipo, $areas;
    public $initialDato;
    public $oldDato;

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
        $this->oldDato = $dato->toArray();
        $this->areas = AreaModel::pluck('nombre', 'id')->toArray();
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

    public function confirmSave()
    {
        // Realiza la validación
        $this->validate();

        // Construimos el arreglo de cambios
        $cambios = [];

        // Verifica si los tres campos de nombre han cambiado
        $nombreModificado = $this->dato['nombre'] !== $this->oldDato['nombre'];
        $apellidoPModificado = $this->dato['apellido_p'] !== $this->oldDato['apellido_p'];
        $apellidoMModificado = $this->dato['apellido_m'] !== $this->oldDato['apellido_m'];
        $tipoModificado = $this->dato['tipo'] !== $this->oldDato['tipo'];
        $noControlModificado = $this->dato['numero_control'] !== $this->oldDato['numero_control'];

        // Si los tres campos de nombre fueron modificados, concatenarlos en una sola línea
        if ($nombreModificado && $apellidoPModificado && $apellidoMModificado) {
            $cambios[] = "<tr><td><strong>Nombre completo</strong></td></tr>
                  <tr><td>Actual: {$this->oldDato['nombre']} {$this->oldDato['apellido_p']} {$this->oldDato['apellido_m']}</td></tr>
                  <tr><td>Nuevo: {$this->dato['nombre']} {$this->dato['apellido_p']} {$this->dato['apellido_m']}</td></tr>
                  <tr><td>&nbsp;</td></tr>";
        } else if ($apellidoPModificado && $apellidoMModificado) {
            $cambios[] = "<tr><td><strong>Apellidos</strong></td></tr>
                  <tr><td>Actual: {$this->oldDato['apellido_p']} {$this->oldDato['apellido_m']}</td></tr>
                  <tr><td>Nuevo: {$this->dato['apellido_p']} {$this->dato['apellido_m']}</td></tr>
                  <tr><td>&nbsp;</td></tr>";
        } else {
            // Solo el nombre fue modificado
            if ($nombreModificado) {
                $cambios[] = "<tr><td><strong>Nombre</strong></td></tr>
                      <tr><td>Actual: {$this->oldDato['nombre']}</td></tr>
                      <tr><td>Nuevo: {$this->dato['nombre']}</td></tr>
                      <tr><td>&nbsp;</td></tr>";
            }

            // Solo el apellido paterno fue modificado
            if ($apellidoPModificado) {
                $cambios[] = "<tr><td><strong>Apellido Paterno</strong></td></tr>
                      <tr><td>Actual: {$this->oldDato['apellido_p']}</td></tr>
                      <tr><td>Nuevo: {$this->dato['apellido_p']}</td></tr>
                      <tr><td>&nbsp;</td></tr>";
            }

            // Solo el apellido materno fue modificado
            if ($apellidoMModificado) {
                $cambios[] = "<tr><td><strong>Apellido Materno</strong></td></tr>
                      <tr><td>Actual: {$this->oldDato['apellido_m']}</td></tr>
                      <tr><td>Nuevo: {$this->dato['apellido_m']}</td></tr>
                      <tr><td>&nbsp;</td></tr>";
            }
        }

        // Verifica el cambio en area
        if ((int) $this->dato['id_area'] !== (int) $this->oldDato['id_area']) {
            $oldArea = $this->areas[$this->oldDato['id_area']] ?? 'No asignado';
            $newArea = $this->areas[$this->dato['id_area']] ?? 'No asignado';
            $cambios[] = "<tr><td><strong>Area asignada</strong></td></tr>
                  <tr><td>Actual: {$oldArea}</td></tr>
                  <tr><td>Nuevo: {$newArea}</td></tr>
                  <tr><td>&nbsp;</td></tr>";
        }

        if ($tipoModificado) {
            $cambios[] = "<tr><td><strong>Tipo</strong></td></tr>
                  <tr><td>Actual: {$this->oldDato['tipo']}</td></tr>
                  <tr><td>Nuevo: {$this->dato['tipo']}</td></tr>
                  <tr><td>&nbsp;</td></tr>";
        }

        if ($noControlModificado) {
            $cambios[] = "<tr><td><strong>No. Control</strong></td></tr>
                  <tr><td>Actual: {$this->oldDato['numero_control']}</td></tr>
                  <tr><td>Nuevo: {$this->dato['numero_control']}</td></tr>
                  <tr><td>&nbsp;</td></tr>";
        }

        // Enviar cambios solo si hay modificaciones
        if (!empty($cambios)) {
            // Enviar los cambios como un mensaje HTML en forma de tabla
            $mensaje = "<table style='width: 100%; text-align: left;'>" . implode("", $cambios) . "</table>";
            $this->dispatch('showConfirmation', $mensaje);
        } else {
            $this->reset(['open']);
            $this->dispatch('alert', 'No se realizaron cambios.');
        }
    }

    public function save()
    {

        $solicitante = SolicitanteModel::find($this->dato['id']);
        if ($this->dato['tipo'] === 'docente') {
            $this->dato['numero_control'] = null;
        }
        $solicitante->fill($this->dato);
        $solicitante->save();

        $this->oldDato = $solicitante->toArray();

        $this->reset(['open']);
        $this->dispatch('render');
        $this->dispatch('alert', 'El solicitante se ha modificado con exito.');
        $this->dato = $solicitante->toArray();
    }
    public function render()
    {
        $areas = AreaModel::pluck('nombre', 'id');
        return view('livewire.solicitante.edit', compact('areas'));
    }
}
