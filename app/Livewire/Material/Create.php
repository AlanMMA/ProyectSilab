<?php

namespace App\Livewire\Material;

use App\Models\CategoriaModel;
use App\Models\EncargadoModel;
use App\Models\MarcaModel;
use App\Models\MaterialModel;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;

class Create extends Component
{

    public $open;
    public $nombre, $id_marca, $modelo, $id_categoria, $stock, $descripcion, $localizacion, $id_encargado;

    protected function rules()
    {
        return [
            'nombre' => 'required|max:20|regex:/^[\p{L}\p{N}\s]+$/u',
            'id_marca' => 'required|numeric',
            'modelo' => 'required|max:20|regex:/^[\pL0-9\s]+$/u',
            'id_categoria' => 'required|numeric',
            'stock' => 'required|numeric',
            'descripcion' => 'required|max:200',
            'localizacion' => 'required|max:50|regex:/^[\pL0-9\s]+$/u',
            'id_encargado' => 'required|numeric',
        ];
    }

    protected $messages = [
        'nombre.regex' => 'El nombre solo puede contener letras y espacios.',
        'modelo.regex' => 'El modelo solo puede contener letras y numeros.',
        'localizacion.regex' => 'La localizacion solo puede contener letras y numeros.',
    ];

    public function update($propertyname)
    {
        $this->validateOnly($propertyname);
    }

    public function save()
    {
        $this->validate();
        MaterialModel::create([
            'nombre' => $this->nombre,
            'id_marca' => $this->id_marca,
            'modelo' => $this->modelo,
            'id_categoria' => $this->id_categoria,
            'stock' => $this->stock,
            'descripcion' => $this->descripcion,
            'localizacion' => $this->localizacion,
            'id_encargado' => $this->id_encargado,
        ]);

        $this->reset(['open', 'nombre', 'modelo', 'stock', 'descripcion', 'localizacion']);
        $this->dispatch('render');
        $this->dispatch('alert', 'El material se ha guardado con exito.');
    }

    public function render()
    {
        $user = User::with('encargado')->find(Auth::id());
        $this->id_encargado = $user->id_encargado;
        $nombreE = $user->encargado ? $user->encargado->nombre : 'No asignado';
        $apellido_p = $user->encargado ? $user->encargado->apellido_p : '';
        $apellido_m = $user->encargado ? $user->encargado->apellido_m : '';
        $marcas = MarcaModel::pluck('nombre', 'id');
        $categorias = CategoriaModel::pluck('nombre', 'id');
        $encargados = EncargadoModel::pluck('nombre', 'id');
        return view('livewire.material.create', compact('marcas', 'categorias', 'nombreE', 'apellido_p', 'apellido_m'));
    }

}