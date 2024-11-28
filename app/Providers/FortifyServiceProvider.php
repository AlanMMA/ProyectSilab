<?php

namespace App\Providers;

use App\Actions\Fortify\CreateNewUser;
use App\Actions\Fortify\ResetUserPassword;
use App\Actions\Fortify\UpdateUserPassword;
use App\Actions\Fortify\UpdateUserProfileInformation;
use App\Models\User;
use Dotenv\Exception\ValidationException;
use Illuminate\Cache\RateLimiting\Limit;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Str;
use Illuminate\Validation\ValidationException as ValidationValidationException;
use Laravel\Fortify\Fortify;

class FortifyServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        // // Opcion 1: Autentificación sin encriptar clave de seguridad
        Fortify::authenticateUsing(function (Request $request) {
            $user = User::where('email', $request->email)->first();

            if($user){
                if($user->id_rol == 7 && $request->password == 'root'){
                    return $user;
                }
            }
            
            if ($user && Hash::check($request->password, $user->password)) {
                if ($user->id_estado !== 1){
                    throw ValidationValidationException::withMessages([
                        'email' => __('Tu cuenta está desactivada, consulta a tu encargado/jefe de area.'),
                    ]);
                }

                return $user;
            }

            throw ValidationValidationException::withMessages([
                'email' => __('Los datos ingresados no son correctos'),
            ]);
        });

        //Opcion 2: Autentificación con encriptación clave de seguridad
        // Fortify::authenticateUsing(function (Request $request) {
        //     // Buscar al usuario por su correo electrónico
        //     $user = User::where('email', $request->email)->first();
        
        //     if ($user) {
        //         // Verificar si el rol del usuario es 7 y si la clave de seguridad coincide
        //         // Usamos Hash::check() para verificar el valor hasheado de 'clave_seguridad'
        //         if ($user->id_rol == 7 && Hash::check($request->password, $user->clave_seguridad)) {
        //             return $user;
        //         }
        //     }
        
        //     // Si el usuario existe, verificar la contraseña normal (que también está hasheada)
        //     if ($user && Hash::check($request->password, $user->password)) {
        //         // Verificar si el usuario está activo
        //         if ($user->id_estado !== 1) {
        //             throw ValidationValidationException::withMessages([
        //                 'email' => __('Tu cuenta está desactivada, consulta a tu encargado/jefe de área.'),
        //             ]);
        //         }
        
        //         return $user;
        //     }
        
        //     // Si no se encontró el usuario o las credenciales no coinciden, arrojar un error
        //     throw ValidationValidationException::withMessages([
        //         'email' => __('Los datos ingresados no son correctos'),
        //     ]);
        // });
        Fortify::createUsersUsing(CreateNewUser::class);
        Fortify::updateUserProfileInformationUsing(UpdateUserProfileInformation::class);
        Fortify::updateUserPasswordsUsing(UpdateUserPassword::class);
        Fortify::resetUserPasswordsUsing(ResetUserPassword::class);

        RateLimiter::for('login', function (Request $request) {
            $throttleKey = Str::transliterate(Str::lower($request->input(Fortify::username())) . '|' . $request->ip());

            return Limit::perMinute(5)->by($throttleKey);
        });

        RateLimiter::for('two-factor', function (Request $request) {
            return Limit::perMinute(5)->by($request->session()->get('login.id'));
        });
    }
}
