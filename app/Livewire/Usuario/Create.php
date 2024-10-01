<?php

namespace App\Livewire\Usuario;

use App\Models\RolModel;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;

class Create extends Component
{

    public $open;
    public $name = '';
    public $email, $id_rol, $id_encargado;
    public $password = '';
    public $showPassword = false;


    protected $rules = [
        'name' => 'required|string|min:3|max:50|regex:/^[a-zA-Z\s]+$/',
        'email' => 'required|email|min:18|max:255|unique:users,email',
        'password' => 'required|string|min:9|max:255|regex:/^(?=.*[A-Z])(?=.*\d)[A-Za-z\d]{9,}$/',
        'id_rol' => 'required|numeric',
        'id_encargado' => 'required|numeric'
    ];

    public function update($propertyname)
    {
        $this->validateOnly($propertyname);
    }
    public function save()
    {

        $this->validate();
        User::create([
            'name' => $this->name,
            'email' => $this->email,
            'password' => bcrypt($this->password),
            'id_rol' => $this->id_rol,
            // 'id_encargado' => User::with('encargado')->find(Auth::id())
            'id_encargado' => $this->id_encargado
        ]);

        $this->reset(['open', 'name', 'email', 'password', 'id_rol']);
        $this->dispatch('render');
        $this->dispatch('alert', 'La categoria se ha guardado con exito.');
    }

    public function render()
    {
        $roles = RolModel::pluck('nombre', 'id');
        $user = User::with('encargado')->find(Auth::id());

        $this->id_encargado = $user->id_encargado;

        $nombreE = $user->encargado ? $user->encargado->nombre : 'No asignado';
        $apellido_p = $user->encargado ? $user->encargado->apellido_p : '';
        $apellido_m = $user->encargado ? $user->encargado->apellido_m : '';


        return view('livewire.usuario.create', compact('roles', 'nombreE', 'apellido_p', 'apellido_m'));
    }

    public function togglePasswordVisibility()
    {
        $this->showPassword = !$this->showPassword;
    }
}
