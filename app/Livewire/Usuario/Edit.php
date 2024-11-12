<?php

namespace App\Livewire\Usuario;

use App\Models\EncargadoModel;
use App\Models\RolModel;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;

class Edit extends Component
{
    public $open;
    public $dato;
    public $name, $email, $id_rol;
    public $id_encargado;
    public $nombreE, $apellido_p, $apellido_m;
    public $oldDato;

    protected $rules;
    protected $listeners = ['saveConfirmed' => 'save'];

    public function update($propertyname)
    {
        $this->validateOnly($propertyname);
    }

    public function mount(User $dato)
    {
        $this->loadUserData();
        $this->dato = $dato->toArray();
        $this->oldDato = $dato->toArray();
    }

    public function loadUserData()
    {

        $user = User::with('encargado')->find(Auth::id());

        $this->id_encargado = $user->id_encargado;
        $this->nombreE = $user->encargado ? $user->encargado->nombre : 'No asignado';
        $this->apellido_p = $user->encargado ? $user->encargado->apellido_p : '';
        $this->apellido_m = $user->encargado ? $user->encargado->apellido_m : '';
    }

    public function confirmSave()
    {

        $cambios = [];

        // Verifica si los 2 campos de alumnos han cambiado
        $nombreModificado = $this->dato['name'] !== $this->oldDato['name'];
        $emailModificado = $this->dato['email'] !== $this->oldDato['email'];

        if ($nombreModificado || $emailModificado) {

            if ($nombreModificado && $emailModificado) {
                $cambios[] = "<tr><td><strong>Nombre:</strong></td></tr>
                <tr><td>Actual: {$this->oldDato['name']}</td></tr>
                <tr><td>Nuevo: {$this->dato['name']}</td></tr>
                <tr><td>&nbsp;</td></tr>
                <tr><td><strong>Email:</strong></td></tr>
                <tr><td>Actual: {$this->oldDato['email']}</td></tr>
                <tr><td>Nuevo: {$this->dato['email']}</td></tr>
                ";
            } else if ($nombreModificado) {
                $cambios[] = "<tr><td><strong>Nombre:</strong></td></tr>
                <tr><td>Actual: {$this->oldDato['name']}</td></tr>
                <tr><td>Nuevo: {$this->dato['name']}</td></tr>
                ";
            } else if ($emailModificado) {
                $cambios[] = "
                <tr><td><strong>Email:</strong></td></tr>
                <tr><td>Actual: {$this->oldDato['email']}</td></tr>
                <tr><td>Nuevo: {$this->dato['email']}</td></tr>
                ";
            }

            // Realizar la validación de los cambios
            if ($this->validateChanges($emailModificado)) {
                $mensaje = "<table style='width: 100%; text-align: left;'>" . implode("", $cambios) . "</table>";
                // Solo si la validación pasó, despachamos confirmación
                $this->dispatch('showConfirmation', $mensaje);
            }
        } else {
            // Si no hubo cambios, muestra mensaje de que no se realizaron cambios
            $this->reset(['open']);
            $this->dispatch('alert', 'No se realizaron cambios.');
        }

    }

    private function validateChanges($emailModificado)
    {
        $rules = [
            'dato.name' => 'required|string|min:3|max:50|regex:/^[a-zA-Z\s]+$/',
        ];

        // Agregar la validación única de 'nombre' solo si fue modificado y no es igual al original
        if ($emailModificado && $this->dato['email'] !== $this->oldDato['email']) {
            $rules['dato.email'] = 'required|email|max:255|unique:users,email';
        } else {
            $rules['dato.email'] = 'required|email|max:255';
        }

        // Realiza la validación con las reglas dinámicas
        $this->validate($rules);

        return true;
    }

    public function save()
    {

        $categoria = User::find($this->dato['id']);
        $categoria->fill($this->dato);
        $categoria->save();

        // Actualiza el valor de oldDato con el nombre nuevo guardado
        $this->dato = $categoria->toArray();
        $this->oldDato = $categoria->toArray();

        $this->reset(['open', 'name']);
        //$this->reset(['open', 'name', 'email', 'id_rol']);
        $this->dispatch('render');
        $this->dispatch('alert', 'El usuario se ha modificado con exito.');

    }

    public function render()
    {
        $roles = RolModel::pluck('nombre', 'id');
        $encargados = EncargadoModel::all();

        return view('livewire.usuario.edit', compact('roles', 'encargados'));
    }
}
