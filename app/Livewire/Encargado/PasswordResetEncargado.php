<?php

namespace App\Livewire\Encargado;

use App\Models\User;
use Livewire\Component;

class PasswordResetEncargado extends Component
{
    public $open = false;
    public $showPassword, $showPassword2, $password, $password_confirmation;
    public $dato, $result;
    public $listeners = ['saveConfirmed' => 'save'];
    protected $rules = [
        'password' => 'required|string|min:9|max:255|regex:/^(?=.*[A-Z])(?=.*\d)[A-Za-z\d]{9,}$/',
        'password_confirmation' => 'same:password',
    ];

    public function update($propertyname)
    {
        $this->validateOnly($propertyname);
    }

    protected $messages = [
        'password.required' => 'La contraseña es obligatoria.',
        'password.min' => 'La contraseña debe tener al menos 9 caracteres.',
        'password.max' => 'La contraseña no puede tener más de 255 caracteres.',
        'password.regex' => 'La contraseña debe contener al menos una letra mayúscula y un número.',
        'password_confirmation.same' => 'Las contraseñas no coinciden.',
    ];

    public function mount($dato)
    {
        $this->result = User::where('id_encargado', $dato['id'])->first();
        $this->result = $this->result ?? null;
    }

    public function openModal()
    {
        $this->reset(['password', 'password_confirmation']); // Llama a resetDatos cada vez que se abre el modal
        $this->open = true;
        $this->resetErrorBag(['password', 'password_confirmation']);
    }


    public function togglePasswordVisibility()
    {
        $this->showPassword = !$this->showPassword;
    }

    public function togglePasswordVisibility2()
    {
        $this->showPassword2 = !$this->showPassword2;
    }

    public function resetPass()
    {
        $this->validate();
        $this->dispatch('Confirm');
    }

    public function save()
    {
        $this->validate();
        $usuario = User::find($this->result['id']);
        $usuario->password = bcrypt($this->password);
        $usuario->save();
        $this->reset(['open', 'password', 'password_confirmation']);
        $this->dispatch('render');
        $this->dispatch('alert', 'Contraseña modificado con exito.');
    }

    public function render()
    {
        return view('livewire.encargado.password-reset-encargado');
    }
}
