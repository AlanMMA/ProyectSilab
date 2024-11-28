<?php

namespace App\Livewire\Profile;

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Livewire\Component;

class ClaveUp extends Component
{
    public $state = [
        'current_SK' => '',
        'SK' => '',
        'SK_confirm' => '',
    ];

    public function validatePassword()
    {

        $this->validate([
            'state.current_SK' => 'required',
            'state.SK' => 'required|string|min:4|same:state.SK_confirm',
        ], [
            'state.SK.same' => 'The new security key confirmation does not match.',
        ]);

        // Obtener el usuario autenticado
        $user = Auth::user();

        // OPCION 1: Actualizar directamente la clave de seguridad SIN ENCRIPTAR.

        // if (!$user || $user->clave_seguridad !== $this->state['current_SK']) {
        //     $this->addError('state.current_SK', 'The current security key is incorrect.');
        //     return;
        // }

        // $updated = DB::table('users')
        //     ->where('id', $user->id)
        //     ->update(['clave_seguridad' => $this->state['SK']]);



        
        // OPCION 2: Actualizar directamente la clave de seguridad CON ENCRIPTAR.

        if (!$user || !Hash::check($this->state['current_SK'], $user->clave_seguridad)) {
            $this->addError('state.current_SK', 'The current security key is incorrect.');
            return;
        }

        $hashedSK = Hash::make($this->state['SK']);

        $updated = DB::table('users')
            ->where('id', $user->id)
            ->update(['clave_seguridad' => $hashedSK]);




        // Verificar si la actualización fue exitosa
        if ($updated) {
            // Emitir un mensaje de éxito
            $this->dispatch('saved');

            // Limpiar los campos
            $this->reset('state');
        } else {
            // Si no se actualizó, agregar un error
            $this->addError('state.SK', 'Failed to update the security key.');
        }
    }



    public function render()
    {
        return view('livewire.profile.clave-up');
    }
}
