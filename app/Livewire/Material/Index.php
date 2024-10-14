<?php

namespace App\Livewire\Material;

use App\Models\CategoriaModel;
use App\Models\EncargadoModel;
use App\Models\MarcaModel;
use App\Models\MaterialModel;
use Livewire\Component;
use Livewire\WithPagination;

class Index extends Component
{

    public $search;
    public $sort = 'id';
    public $direc = 'asc';
    public $cant = '10';
    protected $listeners = ['render' => 'render', 'destroyPost'];
    use WithPagination;

    public function mount()
    {
        $this->sort = 'id';
        $this->direc = 'asc';
    }

    public function updatingSearch()
    {
        $this->resetPage();
    }

    public function render()
    {
        $datos = MaterialModel::where('nombre', 'like', '%' . $this->search . '%') // Buscar por nombre
            ->orWhere('modelo', 'like', '%' . $this->search . '%') // Buscar por modelo
            ->orWhere('stock', 'like', '%' . $this->search . '%') // Buscar por stock
            ->orWhere('descripcion', 'like', '%' . $this->search . '%') // Buscar por descripción
            ->orWhere('localizacion', 'like', '%' . $this->search . '%') // Buscar por localización
            ->orWhereHas('marca', function ($query) {
                $query->where('nombre', 'like', '%' . $this->search . '%'); // Relación con marca
            })
            ->orWhereHas('categoria', function ($query) {
                $query->where('nombre', 'like', '%' . $this->search . '%'); // Relación con categoría
            })
            ->orWhereHas('encargado', function ($query) {
                $query->where('nombre', 'like', '%' . $this->search . '%') // Relación con encargado
                    ->orWhere('apellido_p', 'like', '%' . $this->search . '%')
                    ->orWhere('apellido_m', 'like', '%' . $this->search . '%');
            })
            ->when($this->sort == 'id_marca', function ($query) {
                $query->orderBy(
                    MarcaModel::select('nombre')
                        ->whereColumn('marca.id', 'material.id_marca'), // Ordenar por marca
                    $this->direc
                );
            })
            ->when($this->sort == 'id_categoria', function ($query) {
                $query->orderBy(
                    CategoriaModel::select('nombre')
                        ->whereColumn('categoria.id', 'material.id_categoria'), // Ordenar por categoría
                    $this->direc
                );
            })
            ->when($this->sort == 'id_encargado', function ($query) {
                $query->orderBy(
                    EncargadoModel::select('nombre')
                        ->whereColumn('encargado.id', 'material.id_encargado'), // Ordenar por encargado
                    $this->direc
                );
            }, function ($query) {
                $query->orderBy($this->sort, $this->direc);
            })
            ->when(in_array($this->sort, ['nombre', 'modelo', 'stock', 'descripcion', 'localizacion']), function ($query) {
                $query->orderBy($this->sort, $this->direc); // Ordenar por cualquier columna propia de la tabla
            })
            ->paginate($this->cant)
            ->withQueryString();

        return view('livewire.material.index', compact('datos'));
    }
    public function order($sort)
    {
        if ($this->sort == $sort) {
            if ($this->direc == 'desc') {
                $this->direc = 'asc';
            } else {
                $this->direc = 'desc';
            }
        } else {
            $this->sort = $sort;
            $this->direc = 'asc';
        }
    }
    public function destroyPost($id)
    {
        $cat = MaterialModel::find($id);
        if ($cat) {
            $cat->delete();
        }
    }
}
