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
            'name' => 'required|string|min:5|max:50|regex:/^[\pL\s]+$/u',
            'email' => 'required|email|min:16|max:255|unique:users,email',
            'password' => 'required|string|min:9|max:255|regex:/^(?=.*[A-Z])(?=.*\d)[A-Za-z\d]{9,}$/',
            'password_confirmation' => 'same:password',
            'id_rol' => 'required|numeric|min:1',
        ];
    }

    protected $listeners = ['saveConfirmed2' => 'save'];

    // protected function messages()
    // {
    //     return [
    //         'nombre.required' => 'El nombre es obligatorio.',
    //         'nombre.max' => 'El nombre no puede tener más de 25 caracteres.',
    //         'nombre.min' => 'El nombre debe tener al menos 3 caracteres.',

    //         'apellido_p.required' => 'El apellido paterno es obligatorio.',
    //         'apellido_p.max' => 'El apellido paterno no puede tener más de 20 caracteres.',
    //         'apellido_p.min' => 'El apellido paterno debe tener al menos 3 caracteres.',

    //         'apellido_m.required' => 'El apellido materno es obligatorio.',
    //         'apellido_m.max' => 'El apellido materno no puede tener más de 20 caracteres.',
    //         'apellido_m.min' => 'El apellido materno debe tener al menos 3 caracteres.',

    //         'id_laboratorio.required' => 'El laboratorio es obligatorio.',
    //         'id_laboratorio.numeric' => 'El id del laboratorio debe ser numerico.',
    //         'id_laboratorio.gt' => 'El numero debe ser mayor a 0.',

    //         'name.required' => 'El usuario es obligatorio.',
    //         'name.max' => 'El nombre no puede tener más de 50 caracteres.',
    //         'name.min' => 'El nombre debe tener al menos 5 caracteres.',
            
    //     ];
    // }

    public function update($propertyname)
    {
        $this->validateOnly($propertyname);
    }

    public function confirmSave2()
    {
        $this->id_rol = 1;
        DB::beginTransaction();

        // Realiza la validación
        $this->validate();

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
