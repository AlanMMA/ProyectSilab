<?php

namespace App\Livewire\Encargado;

use App\Models\EncargadoModel;
use App\Models\LaboratorioModel;
use App\Models\User;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
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
            'nombre' => 'required|min:4|max:20|regex:/^[\pL\s]+$/u',
            'apellido_p' => 'required|min:4|max:20|regex:/^[\pL\s]+$/u',
            'apellido_m' => 'required|min:4|max:20|regex:/^[\pL\s]+$/u',
            'id_laboratorio' => 'required|numeric',
            'name' => 'required|string|min:3|max:50|regex:/^[a-zA-Z\s]+$/',
            'email' => 'required|email|min:18|max:255|unique:users,email',
            'password' => 'required|string|min:9|max:255|regex:/^(?=.*[A-Z])(?=.*\d)[A-Za-z\d]{9,}$/',
            'id_rol' => 'required|numeric|min:1',
        ];
    }

    protected $messages = [
        'nombre.regex' => 'El nombre solo puede contener letras y espacios.',
        'apellido_p.regex' => 'El apellido paterno solo puede contener letras y espacios.',
        'apellido_m.regex' => 'El apellido materno solo puede contener letras y espacios.',
    ];

    public function update($propertyname)
    {
        $this->validateOnly($propertyname);
    }



    public function save()
    {
        $this->id_rol = 1;
        DB::beginTransaction();
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
        $ocupado = EncargadoModel::where('id_laboratorio', $this->id_laboratorio)->exists();

        if ($ocupado) {
            $this->dispatch('alert', 'Este laboratorio ya está asignado a otro encargado.');

            $this->id_laboratorio = 0;
        }
    }


    public function mount()
    {
        $this->laboratorios = LaboratorioModel::pluck('nombre', 'id')->toArray();
    }



    public function render()
    {
        $laboratorios = LaboratorioModel::pluck('nombre', 'id');
        return view('livewire.encargado.create', compact('laboratorios'));
    }
}
