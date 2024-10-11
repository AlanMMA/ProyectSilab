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

    protected $rules = [
        'dato.name' => 'required|string|min:3|max:50|regex:/^[a-zA-Z\s]+$/',
        'dato.email' => 'required|email|max:255',
        'dato.id_rol' => 'required|numeric',
    ];
    

    public function mount(User $dato){
        $this->loadUserData();
        $this->dato = $dato->toArray();
    }   

    public function loadUserData()
    {

        $user = User::with('encargado')->find(Auth::id());
        
        $this->id_encargado = $user->id_encargado;
        $this->nombreE = $user->encargado ? $user->encargado->nombre : 'No asignado';
        $this->apellido_p = $user->encargado ? $user->encargado->apellido_p : '';
        $this->apellido_m = $user->encargado ? $user->encargado->apellido_m : '';
    }


    public function save(){
        
        $this->validate(); 
        $categoria = User::find($this->dato['id']);
        $categoria->fill($this->dato);
        $categoria->save();
        $this->reset(['open', 'name', 'email', 'id_rol']);
        $this->dispatch('render');
        $this->dispatch('alert', 'La categoria se ha modificado con exito.');

    }

    public function render()
    {
        $roles = RolModel::pluck('nombre', 'id');
        $encargados = EncargadoModel::all();

        return view('livewire.usuario.edit', compact('roles', 'encargados'));
    }
}
