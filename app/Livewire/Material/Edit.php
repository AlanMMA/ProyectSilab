<?php

namespace App\Livewire\Material;

use App\Models\CategoriaModel;
use App\Models\EncargadoModel;
use App\Models\localizacion;
use App\Models\MarcaModel;
use App\Models\MaterialModel;
use Livewire\Component;

class Edit extends Component
{

    public $open;
    public $dato;
    public $oldDato;

    protected function rules()
    {
        return [
            'dato.nombre' => 'required|min:3|max:20|regex:/^[\p{L}\p{N}\s]+$/u',
            'dato.id_marca' => 'required|numeric|min:1',
            'dato.modelo' => 'required|min:3|max:20|regex:/^[\pL0-9\s-]+$/u',
            'dato.id_categoria' => 'required|numeric|min:1',
            'dato.descripcion' => 'required|min:3|max:200',
            'dato.id_localizacion' => 'required|numeric|min:1',
            'dato.id_encargado' => 'required|numeric|min:1',
        ];
    }

    protected $listeners = ['saveConfirmed' => 'save'];

    protected $messages = [
        'dato.nombre.regex' => 'El nombre solo puede contener letras y espacios.',
        'dato.modelo.regex' => 'El modelo solo puede contener letras y numeros.',
        'dato.id_localizacion.regex' => 'La id_localizacion solo puede contener letras y numeros.',
    ];

    public function update($propertyname)
    {
        $this->validateOnly($propertyname);
    }

    public function mount(MaterialModel $dato)
    {
        $this->dato = $dato->toArray();
        $this->oldDato = $dato->toArray();
    }

    public function openModal()
    {
        $this->resetDatos(); // Llama a resetDatos cada vez que se abre el modal
        $this->open = true;
    }

    // Nueva función para restablecer los datos al abrir el modal
    public function resetDatos()
    {
        $material = MaterialModel::find($this->dato['id']);
        $this->dato = $material->toArray();
    }

    public function confirmSave()
    {
        // Realiza la validación
        $this->validate();

        // Verifica si los tres campos de nombre han cambiado
        $newNombre = $this->dato['nombre'] !== $this->oldDato['nombre'];
        $newMarca = $this->dato['id_marca'] !== $this->oldDato['id_marca'];
        $newModelo = $this->dato['modelo'] !== $this->oldDato['modelo'];
        $newCategoria = $this->dato['id_categoria'] !== $this->oldDato['id_categoria'];
        $newStock = $this->dato['stock'] !== $this->oldDato['stock'];
        $newDescripcion = $this->dato['descripcion'] !== $this->oldDato['descripcion'];
        $newLocalizcion = $this->dato['id_localizacion'] !== $this->oldDato['id_localizacion'];
        $newEncargado = $this->dato['id_encargado'] !== $this->oldDato['id_encargado'];

        // Si hay algún cambio, muestra mensaje de confirmación
        if ($newNombre || $newMarca || $newModelo || $newCategoria || $newStock || $newDescripcion || $newLocalizcion || $newEncargado) {
            $this->dispatch('showConfirmation');
        } else {
            // Si no hubo cambios, muestra mensaje de que no se realizaron cambios
            $this->reset(['open']);
            $this->dispatch('alert', 'No se realizaron cambios.');
        }
    }

    public function save()
    {
        $material = MaterialModel::find($this->dato['id']);
        $material->fill($this->dato);
        $material->save();

        $this->oldDato = $material->toArray();

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
        $datt = auth()->user()->id_encargado;
        $localizaciones = localizacion::where('id_encargado', $datt)->get();

        $nombre_completo = [];
        foreach ($nombres as $id => $nombre) {
            $nombre_completo[$id] = "{$nombre} {$apellidos_p[$id]} {$apellidos_m[$id]}";
        }
        return view('livewire.material.edit', compact('marcas', 'categorias', 'nombre_completo', 'localizaciones'));
    }
}
