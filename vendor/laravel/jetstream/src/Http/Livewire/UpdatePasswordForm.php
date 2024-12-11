<?php

namespace Laravel\Jetstream\Http\Livewire;

use Dotenv\Exception\ValidationException;
use Illuminate\Support\Facades\Auth;
use Laravel\Fortify\Contracts\UpdatesUserPasswords;
use Livewire\Component;

class UpdatePasswordForm extends Component
{
    public $showPassword = false;
    public $showPassword2 = false;
    public $showPassword3 = false;

    /**
     * The component's state.
     *
     * @var array
     */
    public $state = [
        'current_password' => '',
        'password' => '',
        'password_confirmation' => '',
    ];

    protected $listeners = ['updatePassword' => 'updatePassword'];

    public function validatePassword()
    {
        $this->resetErrorBag();
        
        // Validaciones
        $this->validate([
            'state.current_password' => ['required', 'string'],
            'state.password' => [
                'required',
                'string',
                'min:9', // Longitud mínima de 9 caracteres
                'max:255', // Longitud máxima de 255 caracteres
                'confirmed', // Confirma que coincide con el campo "password_confirmation"
                'regex:/^(?=.*[A-Z])(?=.*\d)[A-Za-z\d]{9,}$/', // Al menos una letra mayúscula y un número
            ],
        ], [
            'state.password.regex' => 'La contraseña debe contener al menos una letra mayúscula y un número.',
            'state.password.min' => 'La contraseña debe tener al menos 9 caracteres.',
            'state.password.max' => 'La contraseña no puede tener más de 255 caracteres.',
            'state.password.confirmed' => 'Las contraseñas no coinciden.',
        ]
        );

        // Validar que la contraseña actual sea correcta
        if (!Auth::guard('web')->validate([
            'email' => Auth::user()->email,
            'password' => $this->state['current_password'],
        ])) {
            $this->addError('state.current_password', __('La contraseña ingresada es incorrecta.'));
            return;
        }

        // Emitir evento para mostrar la alerta de confirmación
        $this->dispatch('showConfirmation');
    }

    /**
     * Update the user's password.
     *
     * @param  \Laravel\Fortify\Contracts\UpdatesUserPasswords  $updater
     * @return void
     */

    public function updatePassword(UpdatesUserPasswords $updater)
    {
        $this->resetErrorBag();

        try {
            $updater->update(Auth::user(), $this->state);

            if (request()->hasSession()) {
                request()->session()->put([
                    'password_hash_' . Auth::getDefaultDriver() => Auth::user()->getAuthPassword(),
                ]);
            }

            // Reset the state after successful update
            $this->state = [
                'current_password' => '',
                'password' => '',
                'password_confirmation' => '',
            ];

            $this->showPassword = false;
            $this->showPassword2 = false;
            $this->showPassword3 = false;

            // Dispatch the "saved" event to show success
            $this->dispatch('saved');
        } catch (ValidationException $e) {
            $this->addError('state.current_password', __('La contraseña ingresada es incorrecta.'));
        }
    }

    /**
     * Get the current user of the application.
     *
     * @return mixed
     */
    public function getUserProperty()
    {
        return Auth::user();
    }

    public function togglePasswordVisibility()
    {
        $this->showPassword = !$this->showPassword;
    }

    public function togglePasswordVisibility2()
    {
        $this->showPassword2 = !$this->showPassword2;
    }

    public function togglePasswordVisibility3()
    {
        $this->showPassword3 = !$this->showPassword3;
    }

    /**
     * Render the component.
     *
     * @return \Illuminate\View\View
     */
    public function render()
    {
        return view('profile.update-password-form');
    }
}
