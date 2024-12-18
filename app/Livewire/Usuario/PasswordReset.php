<?php

namespace App\Livewire\Usuario;

use App\Models\User;
use Livewire\Component;

class PasswordReset extends Component
{
    public $open;
    public $showPassword, $showPassword2, $password, $password_confirmation;
    public $dato;
    public $listeners = ['saveConfirmed' => 'save'];
    protected $rules = [
        'password' => 'required|string|min:9|max:255|regex:/^(?=.*[A-Z])(?=.*\d)[A-Za-z\d]{9,}$/',
        'password_confirmation' => 'same:password',
    ];

    public function update($propertyname)
    {
        $this->validateOnly($propertyname);
    }

    public function mount($dato){

        $this->dato = User::find($dato['id']);
    }


    public function togglePasswordVisibility()
    {
        $this->showPassword = !$this->showPassword;
    }

    public function togglePasswordVisibility2()
    {
        $this->showPassword2 = !$this->showPassword2;
    }

    public function resetPass(){
        $this->validate();
        $this->dispatch('Confirm');
    }

    public function save(){
        $this->validate();
        $usuario = User::find($this->dato['id']);
        $usuario->password = bcrypt($this->password);
        $usuario->save();
        $this->reset(['open', 'password', 'password_confirmation']);
        $this->dispatch('render');
        $this->dispatch('alert', 'Contraseña modificado con exito.');
    }

    public function render()
    {
        return view('livewire.usuario.password-reset');
    }
}
