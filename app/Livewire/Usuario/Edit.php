<?php

namespace App\Livewire\Usuario;

use App\Models\RolModel;
use App\Models\User;
use Livewire\Component;

class Edit extends Component
{
    public $open;
    public $dato;
    public $name, $email, $id_rol;

    protected $rules = [
        'dato.name' => 'required|string|min:3|max:50|regex:/^[a-zA-Z\s]+$/',
        'dato.email' => 'required|email|max:255',
        // 'password' => 'required|string|min:8|max:255|regex:/^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*[@$!%*?&#])[A-Za-z\d@$!%*?&#]{8,}$/',
        'dato.id_rol' => 'required|numeric',
    ];
    

    public function mount(User $dato){
        $this->dato = $dato->toArray();
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

        return view('livewire.usuario.edit', compact('roles'));
    }
}
