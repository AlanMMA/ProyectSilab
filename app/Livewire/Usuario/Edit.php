<?php

namespace App\Livewire\Usuario;

use App\Models\Alumnos_ServicioModel;
use App\Models\EncargadoModel;
use App\Models\EstadoUsuarioModel;
use App\Models\RolModel;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Livewire\Component;

class Edit extends Component
{
    public $open;
    public $dato;
    public $nombre, $apellido_pS, $apellido_mS;
    public $id_encargado, $id_estado, $password;
    public $nombreE, $apellido_p, $apellido_m;
    public $oldresult, $oldresult2, $result, $result2;
    public $olddato, $showPassword, $showPassword2, $password_confirmation;

    protected $rules = [
        'result.no_control' => 'required|string|min:8|max:10|regex:/^[a-zA-Z0-9]+$/',
        'result.nombre' => 'required|string|min:3|max:50|regex:/^[\pL\s]+$/u',
        'result.apellido_pS' => 'required|string|min:3|max:15|regex:/^[\pL\s]+$/u',
        'result.apellido_mS' => 'required|string|min:3|max:15|regex:/^[\pL\s]+$/u',
        'dato.id_estado' => 'required|min:1',
    ];

    protected $listeners = ['saveConfirmed' => 'save'];

    public function update($propertyname)
    {
        $this->validateOnly($propertyname);
    }


    public function mount()
    {
        $this->dato = User::find($this->dato['id'])->toArray(); // Datos de la tabla User
        $this->result = Alumnos_ServicioModel::find($this->dato['id_ss'])->toArray(); // Datos de Alumnos_ServicioModel
        $this->oldresult = $this->result;
        $this->olddato = $this->dato;

    }


    public function confirmSave()
    {
        try {
            $this->validate();

            // Verifica si hay errores después de la validación

            // Construimos el arreglo de cambios
            $cambios = [];

            // Verifica si los tres campos de nombre han cambiado
            $no_controlModificado = $this->result['no_control'] !== $this->oldresult['no_control'];
            $nombreModificado = $this->result['nombre'] !== $this->oldresult['nombre'];
            $apellidoPModificado = $this->result['apellido_pS'] !== $this->oldresult['apellido_pS'];
            $apellidoMModificado = $this->result['apellido_mS'] !== $this->oldresult['apellido_mS'];
            $estadoModificado = $this->dato['id_estado'] !== $this->olddato['id_estado'];


            // Si los tres campos de nombre fueron modificados, concatenarlos en una sola línea
            if ($nombreModificado && $apellidoPModificado && $apellidoMModificado) {
                $cambios[] = "<tr><td><strong>Nombre completo</strong></td></tr>
                  <tr><td>Actual: {$this->oldresult['nombre']} {$this->oldresult['apellido_pS']} {$this->oldresult['apellido_mS']}</td></tr>
                  <tr><td>Nuevo: {$this->result['nombre']} {$this->result['apellido_pS']} {$this->result['apellido_mS']}</td></tr>
                  <tr><td>&nbsp;</td></tr>";
            } else if ($apellidoPModificado && $apellidoMModificado) {
                $cambios[] = "<tr><td><strong>Apellidos</strong></td></tr>
                  <tr><td>Actual: {$this->oldresult['apellido_pS']} {$this->oldresult['apellido_mS']}</td></tr>
                  <tr><td>Nuevo: {$this->result['apellido_pS']} {$this->result['apellido_mS']}</td></tr>
                  <tr><td>&nbsp;</td></tr>";
            } else {
                // Solo el nombre fue modificado
                if ($nombreModificado) {
                    $cambios[] = "<tr><td><strong>Nombre</strong></td></tr>
                      <tr><td>Actual: {$this->oldresult['nombre']}</td></tr>
                      <tr><td>Nuevo: {$this->result['nombre']}</td></tr>
                      <tr><td>&nbsp;</td></tr>";
                }

                // Solo el apellido paterno fue modificado
                if ($apellidoPModificado) {
                    $cambios[] = "<tr><td><strong>Apellido Paterno</strong></td></tr>
                      <tr><td>Actual: {$this->oldresult['apellido_pS']}</td></tr>
                      <tr><td>Nuevo: {$this->result['apellido_pS']}</td></tr>
                      <tr><td>&nbsp;</td></tr>";
                }

                // Solo el apellido materno fue modificado
                if ($apellidoMModificado) {
                    $cambios[] = "<tr><td><strong>Apellido Materno</strong></td></tr>
                      <tr><td>Actual: {$this->oldresult['apellido_mS']}</td></tr>
                      <tr><td>Nuevo: {$this->result['apellido_mS']}</td></tr>
                      <tr><td>&nbsp;</td></tr>";
                }
            }

            // Verifica el cambio en no_control
            if ($no_controlModificado) {
                $cambios[] = "<tr><td><strong>Número de Control</strong></td></tr>
                  <tr><td>Actual: {$this->oldresult['no_control']}</td></tr>
                  <tr><td>Nuevo: {$this->result['no_control']}</td></tr>
                  <tr><td>&nbsp;</td></tr>";
            }
            if ($estadoModificado) {
                $nombreEstadoActual = EstadoUsuarioModel::find($this->olddato['id_estado'])->nombre ?? 'Desconocido';
                $nombreEstadoNuevo = EstadoUsuarioModel::find($this->dato['id_estado'])->nombre ?? 'Desconocido';
                $cambios[] = "<tr><td><strong>Estado</strong></td></tr>
                  <tr><td>Actual: {$nombreEstadoActual}</td></tr>
                  <tr><td>Nuevo: {$nombreEstadoNuevo}</td></tr>
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
        } catch (\Illuminate\Validation\ValidationException $e) {
            // Mostrar errores si la validación falla
            dd($e->errors());
        }
    }

    public function save()
    {

        DB::transaction(function () {
            // Actualizar en la tabla Alumnos_ServicioModel
            $alumno = Alumnos_ServicioModel::find($this->result['id']);
            $alumno->fill($this->result);
            $alumno->save();
            $this->oldresult = $alumno->toArray();

            // Actualizar en la tabla User para id_estado
            $user = User::find($this->dato['id']);

            if ($user && $this->dato['id_estado'] !== $this->olddato['id_estado']) {
                $user->id_estado = $this->dato['id_estado'];
                $user->save();
                $this->olddato = $user->toArray();
            }

            $this->reset(['open']);
            $this->dispatch('render');
            $this->dispatch('alert', 'El usuario se ha modificado con éxito.');
        });
        $this->dispatch('alert', 'El usuario se ha modificado con exito.');
    }

    // public function save(){
    //     DB::transaction();

    //     try {
    //         // Actualización en la tabla "alumnosservicio"
    //         $alumno = Alumnos_ServicioModel::find($this->result['id']);
    //         if ($alumno) {
    //             $alumno->fill($this->result);
    //             $alumno->save();
    //             $this->oldresult = $alumno->toArray();
    //         }

    //         $user = User::find($this->dato['id']);
    //         if ($user) {

    //             if ($this->dato['id_estado'] !== $this->olddato['id_estado']) {
    //                 $user->id_estado = $this->dato['id_estado'];
    //                 $user->save();
    //                 $this->olddato = $user->toArray();
    //             }
    //         }

    //         DB::commit();

    //         $this->reset(['open']);
    //         $this->dispatch('render');
    //         $this->dispatch('alert', 'Los datos se han modificado con éxito.');

    //     } catch (\Exception $e) {

    //         DB::rollBack();

    //         $this->dispatch('alert', 'Error al modificar los datos: ' . $e->getMessage());
    //     }
    // }

    public function togglePasswordVisibility()
    {
        $this->showPassword = !$this->showPassword;
    }

    public function togglePasswordVisibility2()
    {
        $this->showPassword2 = !$this->showPassword2;
    }



    public function render()
    {
        $roles = RolModel::pluck('nombre', 'id');
        $encargados = EncargadoModel::all();
        $estados = EstadoUsuarioModel::all();

        return view('livewire.usuario.edit', compact('roles', 'encargados', 'estados'));
    }
}
