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

    protected $rules = [
        'dato.name' => 'required|string|min:3|max:50|regex:/^[a-zA-Z\s]+$/',
        // 'dato.email' => 'required|email|max:255',
        // 'dato.id_rol' => 'required|numeric',
        'dato.email' => 'required|email|max:255|unique:users,email',
        'dato.id_rol' => 'required|numeric',
    ];

    protected $listeners = ['saveConfirmed' => 'save'];

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

        // Verifica si los tres campos de nombre han cambiado
        $nombreModificado = $this->dato['name'] !== $this->oldDato['name'];
        $emailModificado = $this->dato['email'] !== $this->oldDato['email'];
        $rollModificado = $this->dato['id_rol'] !== $this->oldDato['id_rol'];

        // Si hay algún cambio, muestra mensaje de confirmación
        if ($nombreModificado || $emailModificado || $rollModificado) {

            // Realiza la validación
            $this->validate();

            //Despacha el evento sweetalert
            $this->dispatch('showConfirmation');
        } else {
            // Si no hubo cambios, muestra mensaje de que no se realizaron cambios
            $this->reset(['open']);
            $this->dispatch('alert', 'No se realizaron cambios.');
        }
    }

    public function save()
    {

        $categoria = User::find($this->dato['id']);
        $categoria->fill($this->dato);
        $categoria->save();

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
