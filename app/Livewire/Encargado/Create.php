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
    public $name = '';
    public $email, $id_rol = 0;
    public $id_encargado = 0;
    public $password = '';

    protected function rules()
    {
        return [
            'nombre' => 'required|min:3|max:25|regex:/^[\pL\s]+$/u',
            'apellido_p' => 'required|min:3|max:20|regex:/^[\pL\s]+$/u',
            'apellido_m' => 'required|min:3|max:20|regex:/^[\pL\s]+$/u',
            'id_laboratorio' => 'required|numeric|gt:0',
            'name' => 'required|string|min:3|max:50|regex:/^[a-zA-Z\s]+$/',
            'email' => 'required|email|min:16|max:255|unique:users,email',
            'password' => 'required|string|min:9|max:255|regex:/^(?=.*[A-Z])(?=.*\d)[A-Za-z\d]{9,}$/',
            'id_rol' => 'required|numeric|min:1',
        ];
    }

    protected $listeners = ['saveConfirmed2' => 'save'];

    protected $messages = [
        'nombre.regex' => 'El nombre solo puede contener letras y espacios.',
        'apellido_p.regex' => 'El apellido paterno solo puede contener letras y espacios.',
        'apellido_m.regex' => 'El apellido materno solo puede contener letras y espacios.',
        'id_laboratorio.gt' => 'Por favor, seleccione un laboratorio.',
    ];

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
                ]);

                DB::commit();

                $this->reset(['open', 'nombre', 'apellido_p', 'apellido_m', 'name', 'email', 'password']);
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
