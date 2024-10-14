<?php

namespace App\Livewire\Material;

use App\Models\CategoriaModel;
use App\Models\EncargadoModel;
use App\Models\MarcaModel;
use App\Models\MaterialModel;
use Livewire\Component;

class Edit extends Component
{

    public $open;
    public $dato;

    protected function rules()
    {
        return [
            'dato.nombre' => 'required|max:20|regex:/^[\p{L}\p{N}\s]+$/u',
            'dato.id_marca' => 'required|numeric',
            'dato.modelo' => 'required|max:20|regex:/^[\pL0-9\s]+$/u',
            'dato.id_categoria' => 'required|numeric',
            'dato.stock' => 'required|numeric',
            'dato.descripcion' => 'required|max:200',
            'dato.localizacion' => 'required|max:50|regex:/^[\pL0-9\s]+$/u',
            'dato.id_encargado' => 'required|numeric',
        ];
    }

    protected $messages = [
        'dato.nombre.regex' => 'El nombre solo puede contener letras y espacios.',
        'dato.modelo.regex' => 'El modelo solo puede contener letras y numeros.',
        'dato.localizacion.regex' => 'La localizacion solo puede contener letras y numeros.',
    ];

    public function update($propertyname)
    {
        $this->validateOnly($propertyname);
    }

    public function mount(MaterialModel $dato)
    {
        $this->dato = $dato->toArray();
    }

    public function save()
    {
        $this->validate();
        $material = MaterialModel::find($this->dato['id']);
        $material->fill($this->dato);
        $material->save();
        $this->reset(['open']);
        $this->dispatch('render');
        $this->dispatch('alert', 'El material se ha modificado con exito.');
    }
    public function render()
    {
        $marcas = MarcaModel::pluck('nombre', 'id');
        $categorias = CategoriaModel::pluck('nombre', 'id');
        $nombres = EncargadoModel::pluck('nombre', 'id')->toArray();
        $apellidos_p = EncargadoModel::pluck('apellido_p', 'id')->toArray();
        $apellidos_m = EncargadoModel::pluck('apellido_m', 'id')->toArray();

        $nombre_completo = [];
        foreach ($nombres as $id => $nombre) {
            $nombre_completo[$id] = "{$nombre} {$apellidos_p[$id]} {$apellidos_m[$id]}";
        }
        return view('livewire.material.edit', compact('marcas', 'categorias', 'nombre_completo'));
    }
}
