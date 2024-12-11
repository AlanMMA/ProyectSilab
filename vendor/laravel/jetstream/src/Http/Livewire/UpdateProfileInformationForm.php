<?php

namespace Laravel\Jetstream\Http\Livewire;

use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Laravel\Fortify\Contracts\UpdatesUserProfileInformation;
use Livewire\Component;
use Livewire\WithFileUploads;

class UpdateProfileInformationForm extends Component
{
    use WithFileUploads;

    /**
     * The component's state.
     *
     * @var array
     */
    public $state = [];

    public $dato, $oldDato, $email;
    public $supervisor_name;
    public $supervisor_patsur;
    public $supervisor_matsur;

    /**
     * The new avatar for the user.
     *
     * @var mixed
     */
    public $photo;

    /**
     * Determine if the verification email was sent.
     *
     * @var bool
     */
    public $verificationLinkSent = false;

    /**
     * Prepare the component.
     *
     * @return void
     */
    public function mount(User $dato)
    {
        $user = Auth::user();
        $this->dato = $dato->toArray();
        $this->oldDato = $dato->toArray();

        $this->state = array_merge([
            'email' => $user->email,
        ], $user->withoutRelations()->toArray());

        // Verificar si el usuario es un encargado o un alumno de SS
        if ($user->id_rol == 7) {
            // Usuario es un encargado
            $this->supervisor_name = $user->encargado->nombre ?? '';
            $this->supervisor_patsur = $user->encargado->apellido_p ?? '';
            $this->supervisor_matsur = $user->encargado->apellido_m ?? '';
        } elseif ($user->id_rol == 1) {
            // Usuario es un encargado
            $this->supervisor_name = $user->encargado->nombre ?? '';
            $this->supervisor_patsur = $user->encargado->apellido_p ?? '';
            $this->supervisor_matsur = $user->encargado->apellido_m ?? '';
        } elseif ($user->id_rol == 2) {
            // Usuario es un alumno de servicio social
            $this->supervisor_name = $user->alumnos->nombre ?? '';
            $this->supervisor_patsur = $user->alumnos->apellido_pS ?? '';
            $this->supervisor_matsur = $user->alumnos->apellido_mS ?? '';
        } else {
            // Usuario no tiene relación con encargado ni SS
            $this->supervisor_name = 'Sin relación';
            $this->supervisor_patsur = '';
            $this->supervisor_matsur = '';

        }
    }

    // Evento para confirmación
    protected $listeners = ['saveProfileChanges' => 'updateProfileInformation'];

    /**
     * Update the user's profile information.
     *
     * @param  \Laravel\Fortify\Contracts\UpdatesUserProfileInformation  $updater
     * @return void
     */
    public function updateProfileInformation(UpdatesUserProfileInformation $updater)
    {
        $this->resetErrorBag();

        $data = $this->photo
        ? array_merge($this->state, ['photo' => $this->photo])
        : $this->state;

        $updater->update(Auth::user(), $data);

        $user = Auth::user();

        // Guardar los datos dependiendo del rol del usuario
        if ($user->id_rol == 7) {
            // Es un encargado
            $user->encargado()->update([
                'nombre' => $this->supervisor_name,
                'apellido_p' => $this->supervisor_patsur,
                'apellido_m' => $this->supervisor_matsur,
            ]);
        } elseif ($user->id_rol == 1) {
            // Es un encargado
            $user->encargado()->update([
                'nombre' => $this->supervisor_name,
                'apellido_p' => $this->supervisor_patsur,
                'apellido_m' => $this->supervisor_matsur,
            ]);
        } elseif ($user->id_rol == 2) {
            // Es un alumno de SS
            $user->alumnos()->update([
                'nombre' => $this->supervisor_name,
                'apellido_pS' => $this->supervisor_patsur,
                'apellido_mS' => $this->supervisor_matsur,
            ]);
        }

        if (isset($this->photo)) {
            return redirect()->route('profile.show');
        }

        $this->dispatch('saved');
        $this->dispatch('refresh-navigation-menu');
    }

    // En tu componente Livewire
    public function confirmSave()
    {
        // Verificar si el 'email' ha sido modificado
        $emailModificado = isset($this->dato['email']) && isset($this->oldDato['email']) && $this->dato['email'] !== $this->oldDato['email'];

        // Validar los cambios, pasando si el email ha sido modificado
        if ($this->validateChanges($emailModificado)) {
            // Si pasa la validación, muestra la alerta de SweetAlert
            $this->dispatch('showConfirmationAlert');
        }
    }

    private function validateChanges($emailModificado)
    {
        // Reglas básicas para los otros campos
        $rules = [
            'state.name' => 'required|string|min:9|max:50',
            'supervisor_name' => 'required|min:3|max:25|regex:/^[\pL\s]+$/u',
            'supervisor_patsur' => 'required|min:3|max:20|regex:/^[\pL\s]+$/u',
            'supervisor_matsur' => 'required|min:3|max:20|regex:/^[\pL\s]+$/u',
        ];

        // Si el email ha sido modificado, agrega la validación única
        if ($emailModificado) {
            $rules['state.email'] = 'required|email|min:16|max:255|unique:users,email';
        } else {
            $rules['state.email'] = 'required|email|min:16|max:255';
        }

        // Realiza la validación con las reglas dinámicas
        $this->validate($rules);

        return true;
    }

    /**
     * Delete user's profile photo.
     *
     * @return void
     */
    public function deleteProfilePhoto()
    {
        Auth::user()->deleteProfilePhoto();

        $this->dispatch('refresh-navigation-menu');
    }

    /**
     * Sent the email verification.
     *
     * @return void
     */
    public function sendEmailVerification()
    {
        Auth::user()->sendEmailVerificationNotification();

        $this->verificationLinkSent = true;
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
        return view('profile.update-profile-information-form');
    }
}
