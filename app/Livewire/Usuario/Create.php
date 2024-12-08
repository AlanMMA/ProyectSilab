<?php

namespace App\Livewire\Usuario;

use App\Models\Alumnos_ServicioModel;
use App\Models\EncargadoModel;
use App\Models\RolModel;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Livewire\Component;

class Create extends Component
{

    public $open;
    public $name = '';
    public $email, $id_rol = 0;
    public $id_encargado = 0;
    public $password = '';
    public $showPassword = false, $showPassword2 = false;
    public $nombreE, $apellido_p, $apellido_m;
    public $nombre, $no_control, $apellido_pS, $apellido_mS, $control;
    public $password_confirmation = '';

    protected $rules = [
        'name' => 'required|string|min:8|max:50',
        'email' => 'required|email|min:16|max:255|unique:users,email',
        'password' => 'required|string|min:9|max:255',
        'password_confirmation' => 'required|same:password',
        'id_rol' => 'required|numeric|gt:0',
        'id_encargado' => 'required|numeric|gt:0',
        'no_control' => 'required|string|min:8|max:10|regex:/^[a-zA-Z0-9]+$/',
        'nombre' => 'required|min:3|max:25|regex:/^[\pL\s]+$/u',
        'apellido_pS' => 'required|min:3|max:20|regex:/^[\pL\s]+$/u',
        'apellido_mS' => 'required|min:3|max:20|regex:/^[\pL\s]+$/u',
    ];

    protected $messages = [
        'name.required' => 'El campo "name" es obligatorio.',
        'password.required' => 'El campo "password" es obligatorio.',
        'name.min' => 'El campo "name" debe tener al menos 8 caracteres.',
        'password.min' => 'El campo "password" debe tener al menos 9 caracteres.',
    ];

    protected $listeners = ['saveConfirmed2' => 'save2'];

    public function update($propertyname)
    {
        $this->validateOnly($propertyname);
        if (in_array($propertyname, ['name', 'password'])) {
            $this->validateRegex($propertyname, $this->$propertyname);
        }
    }

    private function validateRegex($field, $value)
    {
        // Resetea los errores del campo específico
        $this->resetErrorBag($field);

        // Reglas personalizadas
        if (!preg_match('/[A-Z]/', $value)) {
            $this->addError($field, "El campo \"$field\" debe contener al menos una letra mayúscula.");
        }

        if (!preg_match('/\d/', $value)) {
            $this->addError($field, "El campo \"$field\" debe contener al menos un número.");
        }

        if (strlen($value) < 10) {
            $this->addError($field, "El campo \"$field\" debe tener al menos 9 caracteres.");
        }


        if (!preg_match('/^[A-Za-z\d]+$/', $value)) {
            $this->addError($field, "El campo \"$field\" solo puede contener letras y números.");
        }
    }

    private function validateCustomRules()
    {
        $customErrors = [];

        // Validación personalizada para "name"
        if (!preg_match('/[A-Z]/', $this->name)) {
            $customErrors['name'][] = 'El campo "name" debe contener al menos una letra mayúscula.';
        }

        if (!preg_match('/\d/', $this->name)) {
            $customErrors['name'][] = 'El campo "name" debe contener al menos un número.';
        }

        // Validación personalizada para "password"
        if (!preg_match('/[A-Z]/', $this->password)) {
            $customErrors['password'][] = 'El campo "password" debe contener al menos una letra mayúscula.';
        }

        if (!preg_match('/\d/', $this->password)) {
            $customErrors['password'][] = 'El campo "password" debe contener al menos un número.';
        }

        return $customErrors;
    }



    public function confirmSave2()
    {

        $this->id_rol = 2;

        // Realiza la validación
        $this->validate();

        $customErrors = $this->validateCustomRules();

        // Si hay errores personalizados, evita continuar
        if (!empty($customErrors)) {
            foreach ($customErrors as $field => $errorMessages) {
                foreach ($errorMessages as $message) {
                    $this->addError($field, $message);
                }
            }
            return; // Detener la ejecución si hay errores
        }

        // Si la validación es exitosa, dispara el evento para mostrar SweetAlert
        $this->dispatch('showConfirmation2', [
            'newDatos' => [
                'nombre' => $this->nombre,
                'apellido_pS' => $this->apellido_pS,
                'apellido_mS' => $this->apellido_mS,
                'no_control' => $this->no_control,
                'name' => $this->name, // Incluye el nombre de alumno
                'email' => $this->email, // Incluye el correo electrónico
            ],
        ]);
    }

    // public function save()
    // {
    //     $this->validate();
    //     User::create([
    //         'name' => $this->name,
    //         'email' => $this->email,
    //         'password' => bcrypt($this->password),
    //         'id_rol' => $this->id_rol,
    //         'id_encargado' => $this->id_encargado,
    //     ]);

    //     $this->reset(['open', 'name', 'email', 'password', 'id_rol']);
    //     $this->dispatch('render');
    //     $this->dispatch('alert', 'El usuario se ha guardado con exito.');
    // }

    public function save()
    {
        $this->id_rol = 2;
        DB::beginTransaction();

        $this->validate();

        $this->dispatch('showConfirmation2', [
            'newDatos' => [
                'no_control' => $this->no_control,
                'nombre' => $this->nombre,
                'apellido_pS' => $this->apellido_pS,
                'apellido_mS' => $this->apellido_mS
            ]
        ]);
    }

    public function save2()
    {

        $this->validate();
        try {
            $datosAlumno = Alumnos_ServicioModel::create([
                'nombre' => $this->nombre,
                'no_control' => $this->no_control,
                'apellido_pS' => $this->apellido_pS,
                'apellido_mS' => $this->apellido_mS,
            ]);
            $control = $datosAlumno->id;

            if ($datosAlumno) {
                User::create([
                    'name' => $this->name,
                    'email' => $this->email,
                    'password' => bcrypt($this->password),
                    'id_rol' => $this->id_rol,
                    'id_encargado' => $this->id_encargado,
                    'id_ss' => $control,
                    'id_estado' => 1
                ]);

                DB::commit();

                $this->reset(['open', 'name', 'email', 'password', 'id_rol', 'nombre', 'no_control', 'apellido_pS', 'apellido_mS', 'password_confirmation']);
                $this->dispatch('render');
                $this->dispatch('alert', 'El usuario se ha guardado con exito.');
            } else {
                DB::rollBack();
                $this->dispatch('errorGuardado', 'No se pudo crear el usuario.');
            }
        } catch (\Exception $e) {
            DB::rollBack();
            $this->dispatch('errorGuardado', 'Hubo un error al guardar los datos: ' . $e->getMessage());
        }
    }

    public function mount()
    {

        $this->loadUserData();
    }

    public function updRol($value)
    {

        if ($value == 1) {
            $this->id_encargado = 0;
        } else {
            $this->id_encargado = 0;
        }
    }

    public function loadUserData()
    {
        $user = User::with('encargado')->find(Auth::id());
        $this->id_encargado = $user->id_encargado;
        $this->nombreE = $user->encargado ? $user->encargado->nombre : 'No asignado';
        $this->apellido_p = $user->encargado ? $user->encargado->apellido_p : '';
        $this->apellido_m = $user->encargado ? $user->encargado->apellido_m : '';
    }

    public function render()
    {
        $roles = RolModel::pluck('nombre', 'id');
        $encargados = EncargadoModel::all();

        return view('livewire.usuario.create', compact('roles', 'encargados'));
    }

    public function togglePasswordVisibility()
    {
        $this->showPassword = !$this->showPassword;
    }

    public function togglePasswordVisibility2()
    {
        $this->showPassword2 = !$this->showPassword2;
    }
}


// Siguiendo esta logica:
// "    public function confirmSave2()
//     {
//         $this->id_rol = 1;
//         DB::beginTransaction();

//         // Realiza la validación
//         $this->validate();

//         // Obtiene el nombre del laboratorio a partir del ID seleccionado
//         $laboratorioNombre = $this->laboratorios[$this->id_laboratorio] ?? 'No asignado';

//         // Si la validación es exitosa, dispara el evento para mostrar SweetAlert
//         $this->dispatch('showConfirmation2', [
//             'newDatos' => [
//                 'nombre' => $this->nombre,
//                 'apellido_p' => $this->apellido_p,
//                 'apellido_m' => $this->apellido_m,
//                 'id_laboratorio' => $this->id_laboratorio,
//             ],
//             'laboratorio_nombre' => $laboratorioNombre, // Enviar el nombre del laboratorio
//         ]);
//     }

//     public function save()
//     {
//         /*$this->id_rol = 1;
//         DB::beginTransaction();*/
//         $this->validate();
//         try {

//             $encargado = EncargadoModel::create([
//                 'nombre' => $this->nombre,
//                 'apellido_p' => $this->apellido_p,
//                 'apellido_m' => $this->apellido_m,
//                 'id_laboratorio' => $this->id_laboratorio,
//             ]);

//             $idUp = $encargado->id;

//             if ($encargado) {

//                 User::create([
//                     'name' => $this->name,
//                     'email' => $this->email,
//                     'password' => bcrypt($this->password),
//                     'id_rol' => $this->id_rol,
//                     'id_encargado' => $idUp,
//                 ]);

//                 DB::commit();

//                 $this->reset(['open', 'nombre', 'apellido_p', 'apellido_m', 'name', 'email', 'password']);
//                 $this->dispatch('render');
//                 $this->dispatch('alert', 'El encargado y el usuario se han guardado con éxito.');
//             } else {

//                 DB::rollBack();
//                 $this->dispatch('errorGuardado', 'No se pudo crear el encargado.');
//             }
//         } catch (\Exception $e) {
//             DB::rollBack();
//             $this->dispatch('errorGuardado', 'Hubo un error al guardar los datos: ' . $e->getMessage());
//         }
//     } "

// para guardar datos en 2 tablas diferentes, pero con estas tablas:
// "    public function save()
//     {
//         $this->validate();
//         User::create([
//             'name' => $this->name,
//             'email' => $this->email,
//             'password' => bcrypt($this->password),
//             'id_rol' => $this->id_rol,
//             'id_encargado' => $this->id_encargado,
//         ]);

//         $this->reset(['open', 'name', 'email', 'password', 'id_rol']);
//         $this->dispatch('render');
//         $this->dispatch('alert', 'El usuario se ha guardado con exito.');
//     }"

// "    public function save()
//     {
//         $this->validate();
//         User::create([
//             'name' => $this->name,
//             'email' => $this->email,
//             'password' => bcrypt($this->password),
//             'id_rol' => $this->id_rol,
//             'id_encargado' => $this->id_encargado,
//         ]);

//         $this->reset(['open', 'name', 'email', 'password', 'id_rol']);
//         $this->dispatch('render');
//         $this->dispatch('alert', 'El usuario se ha guardado con exito.');
//     }

//     public function save2(){
//         $this->validate();
//         Alumnos_ServicioModel::create([
//             'nombre' =>$this->nombre,
//             'no_control' => $this->no_control,
//             'apellido_pS' => $this->apellido_pS,
//             'apellido_mS' => $this->apellido_mS
//         ]);
//     } "