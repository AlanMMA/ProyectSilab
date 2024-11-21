<?php

namespace Laravel\Jetstream\Http\Livewire;

use Dotenv\Exception\ValidationException;
use Illuminate\Support\Facades\Auth;
use Laravel\Fortify\Contracts\UpdatesUserPasswords;
use Livewire\Component;

class UpdatePasswordForm extends Component
{
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
            'state.password' => ['required', 'string', 'min:9', 'confirmed'],
        ]);

        // Validar que la contraseña actual sea correcta
        if (!Auth::guard('web')->validate([
            'email' => Auth::user()->email,
            'password' => $this->state['current_password'],
        ])) {
            $this->addError('state.current_password', __('The current password is incorrect.'));
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

            // Dispatch the "saved" event to show success
            $this->dispatch('saved');
        } catch (ValidationException $e) {
            $this->addError('state.current_password', __('The current password is incorrect.'));
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
