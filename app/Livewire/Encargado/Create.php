<?php

namespace App\Livewire\Encargado;

use App\Models\EncargadoModel;
use App\Models\LaboratorioModel;
use App\Models\User;
use Illuminate\Support\Facades\DB;
use Livewire\Component;

class Create extends Component
{

    public $open;
    public $nombre, $apellido_p, $apellido_m, $id_laboratorio, $idNext, $laboratorios;
    public $showPassword = false;
    public $showPassword2 = false;
    public $name = '';
    public $email, $id_rol = 0;
    public $id_encargado = 0;
    public $password = '';
    public $password_confirmation = '';

    protected function rules()
    {
        return [
            'nombre' => 'required|min:3|max:25|regex:/^[\pL\s]+$/u',
            'apellido_p' => 'required|min:3|max:20|regex:/^[\pL\s]+$/u',
            'apellido_m' => 'required|min:3|max:20|regex:/^[\pL\s]+$/u',
            'id_laboratorio' => 'required|numeric|gt:0',
            'name' => 'required|string|min:8|max:50',
            'email' => 'required|email|min:16|max:255|unique:users,email',
            'password' => 'required|string|min:9|max:255',
            'password_confirmation' => 'required|same:password',
            'id_rol' => 'required|numeric|min:1',

        ];
    }

    protected $messages = [
        'name.required' => 'El campo "name" es obligatorio.',
        'password.required' => 'El campo "password" es obligatorio.',
        'name.min' => 'El campo "name" debe tener al menos 8 caracteres.',
        'password.min' => 'El campo "password" debe tener al menos 9 caracteres.',
    ];

    protected $listeners = ['saveConfirmed2' => 'save'];


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
        $this->id_rol = 1;
        DB::beginTransaction();

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

        // Obtiene el nombre del laboratorio a partir del ID seleccionado
        $laboratorioNombre = $this->laboratorios[$this->id_laboratorio] ?? 'No asignado';

        // Si la validación es exitosa, dispara el evento para mostrar SweetAlert
        $this->dispatch('showConfirmation2', [
            'newDatos' => [
                'nombre' => $this->nombre,
                'apellido_p' => $this->apellido_p,
                'apellido_m' => $this->apellido_m,
                'id_laboratorio' => $this->id_laboratorio,
                'name' => $this->name, // Incluye el nombre de usuario
                'email' => $this->email, // Incluye el correo electrónico
            ],
            'laboratorio_nombre' => $laboratorioNombre, // Enviar el nombre del laboratorio
        ]);
    }

    public function save()
    {
        /*$this->id_rol = 1;
        DB::beginTransaction();*/
        $this->validate();
        try {

            $encargado = EncargadoModel::create([
                'nombre' => $this->nombre,
                'apellido_p' => $this->apellido_p,
                'apellido_m' => $this->apellido_m,
                'id_laboratorio' => $this->id_laboratorio,
            ]);

            $idUp = $encargado->id;

            if ($encargado) {

                User::create([
                    'name' => $this->name,
                    'email' => $this->email,
                    'password' => bcrypt($this->password),
                    'id_rol' => $this->id_rol,
                    'id_encargado' => $idUp,
                    'id_estado' => 1
                ]);

                DB::commit();

                $this->reset(['open', 'nombre', 'apellido_p', 'apellido_m', 'name', 'email', 'password', 'password_confirmation']);
                $this->dispatch('render');
                $this->dispatch('alert', 'El encargado y el usuario se han guardado con éxito.');
            } else {

                DB::rollBack();
                $this->dispatch('errorGuardado', 'No se pudo crear el encargado.');
            }
        } catch (\Exception $e) {
            DB::rollBack();
            $this->dispatch('errorGuardado', 'Hubo un error al guardar los datos: ' . $e->getMessage());
        }
    }

    public function togglePasswordVisibility()
    {
        $this->showPassword = !$this->showPassword;
    }

    public function togglePasswordVisibility2()
    {
        $this->showPassword2 = !$this->showPassword2;
    }

    public function verificarLaboratorio()
    {

        if ($this->id_laboratorio == 0) {
            // Si el laboratorio no está seleccionado, no hacemos nada
            return;
        }

        // Obtiene el laboratorio seleccionado
        $laboratorio = LaboratorioModel::find($this->id_laboratorio);

        // Verifica si el laboratorio existe
        if (!$laboratorio) {
            $this->dispatch('alert1', 'Laboratorio no encontrado.');
            $this->id_laboratorio = 0;
            return;
        }

        // Obtiene el número máximo de encargados permitido para este laboratorio
        $maxEncargados = $laboratorio->num_max_encargado;

        // Cuenta cuántos encargados ya están asignados al laboratorio
        $encargadosActuales = EncargadoModel::where('id_laboratorio', $this->id_laboratorio)->count();

        if ($encargadosActuales >= $maxEncargados) {
            // Si el límite se alcanza o excede, muestra un mensaje de alerta
            $this->dispatch('alert1', "El laboratorio ya ha alcanzado el número máximo de encargados permitido ({$maxEncargados}).");
            $this->id_laboratorio = 0;
        }
    }

    public function mount()
    {
        $this->laboratorios = LaboratorioModel::pluck('nombre', 'id')->toArray();
    }

    public function render()
    {
        $this->laboratorios = LaboratorioModel::pluck('nombre', 'id');
        return view('livewire.encargado.create', [
            'laboratorios' => $this->laboratorios,
        ]);
    }
}
