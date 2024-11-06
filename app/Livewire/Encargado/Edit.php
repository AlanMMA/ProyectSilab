<?php

namespace App\Livewire\Encargado;

use App\Models\EncargadoModel;
use App\Models\LaboratorioModel;
use Livewire\Component;

class Edit extends Component
{

    public $dato, $id_laboratorio, $laboratorios;
    public $open = false;
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

        // Cargar nombres de laboratorios
        $this->laboratorios = LaboratorioModel::pluck('nombre', 'id')->toArray();
    }

    public function confirmSave()
    {
        $this->validate();

        // Construimos el arreglo de cambios
        $cambios = [];

        // Verifica si los tres campos de nombre han cambiado
        $nombreModificado = $this->dato['nombre'] !== $this->oldDato['nombre'];
        $apellidoPModificado = $this->dato['apellido_p'] !== $this->oldDato['apellido_p'];
        $apellidoMModificado = $this->dato['apellido_m'] !== $this->oldDato['apellido_m'];

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

        // Verifica el cambio en laboratorio
        if ($this->dato['id_laboratorio'] !== $this->oldDato['id_laboratorio']) {
            $oldLab = $this->laboratorios[$this->oldDato['id_laboratorio']] ?? 'No asignado';
            $newLab = $this->laboratorios[$this->dato['id_laboratorio']] ?? 'No asignado';
            $cambios[] = "<tr><td><strong>Laboratorio asignado</strong></td></tr>
                  <tr><td>Actual: {$oldLab}</td></tr>
                  <tr><td>Nuevo: {$newLab}</td></tr>
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
        $encargado = EncargadoModel::find($this->dato['id']);
        $encargado->fill($this->dato);
        $encargado->save();

        // Actualiza el valor de oldDato con el nombre nuevo guardado
        $this->oldDato = $encargado->toArray();

        $this->reset(['open']);
        $this->dispatch('render');
        $this->dispatch('alert', 'El encargado se ha modificado con exito.');
    }

    public function verificarLaboratorio()
    {
        // Verificar si se seleccionó la opción "Seleccione un laboratorio" (valor 0 o null)
        if (empty($this->dato['id_laboratorio']) || $this->dato['id_laboratorio'] == 0) {
            return;
        }

        // Si el laboratorio no ha cambiado, no hacemos nada
        if ($this->dato['id_laboratorio'] == EncargadoModel::find($this->dato['id'])->id_laboratorio) {
            return;
        }

        // Obtener el laboratorio seleccionado
        $laboratorio = LaboratorioModel::find($this->dato['id_laboratorio']);

        if (!$laboratorio) {
            $this->dispatch('alert2', 'Laboratorio no encontrado.');
            return;
        }

        // Contar el número de encargados actuales en el laboratorio seleccionado
        $cantidadEncargados = EncargadoModel::where('id_laboratorio', $this->dato['id_laboratorio'])->count();

        // Comparar con el número máximo de encargados permitido
        if ($cantidadEncargados >= $laboratorio->num_max_encargado) {
            $this->dispatch('alert2', "No se puede agregar más encargados a este laboratorio. Límite alcanzado: {$laboratorio->num_max_encargado}");
            // Revertir al laboratorio anterior
            $this->dato['id_laboratorio'] = EncargadoModel::find($this->dato['id'])->id_laboratorio;
            return; // Salir del método
        }

    }

    public function render()
    {
        $laboratorios = LaboratorioModel::pluck('nombre', 'id');
        return view('livewire.encargado.edit', compact('laboratorios'));
    }
}
