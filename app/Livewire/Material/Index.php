<?php

namespace App\Livewire\Material;

use App\Models\EncargadoModel;
use App\Models\MaterialModel;
use Illuminate\Pagination\LengthAwarePaginator;
use Livewire\Component;
use Livewire\WithPagination;

class Index extends Component
{

    public $search, $UserId, $encargados;
    public $sort = 'id';
    public $direc = 'asc';
    public $cant = '10';
    public $SelectEncargado = 0;
    protected $listeners = ['render' => 'render', 'destroyPost'];
    use WithPagination;

    public function mount()
    {
        $this->sort = 'id';
        $this->direc = 'asc';
        $this->UserId = auth()->user()->id_encargado;
        $this->encargados = EncargadoModel::all();
    }

    public function updatingSearch()
    {
        $this->resetPage();
    }

    public function render()
    {
        // Si el usuario es jefe y SelectEncargado es -1, mostrar todos los materiales.
        if (auth()->user()->id_rol == 7 && $this->SelectEncargado == -1) {
            $datos = MaterialModel::where(function ($query) {
                $query->where('nombre', 'like', '%' . $this->search . '%')
                    ->orWhere('modelo', 'like', '%' . $this->search . '%')
                    ->orWhere('stock', 'like', '%' . $this->search . '%')
                    ->orWhere('descripcion', 'like', '%' . $this->search . '%')
                    ->orWhere('localizacion', 'like', '%' . $this->search . '%')
                    ->orWhereHas('marca', function ($query) {
                        $query->where('nombre', 'like', '%' . $this->search . '%');
                    })
                    ->orWhereHas('categoria', function ($query) {
                        $query->where('nombre', 'like', '%' . $this->search . '%');
                    })
                    ->orWhereHas('encargado', function ($query) {
                        $query->where('nombre', 'like', '%' . $this->search . '%')
                            ->orWhere('apellido_p', 'like', '%' . $this->search . '%')
                            ->orWhere('apellido_m', 'like', '%' . $this->search . '%');
                    });
            })
                ->orderBy($this->sort, $this->direc)
                ->paginate($this->cant)
                ->withQueryString();
        } elseif (auth()->user()->id_rol == 7 && $this->SelectEncargado == 0) {
            // Si es jefe y no seleccionó ningún encargado, devolver datos vacíos.
            $datos = new LengthAwarePaginator([], 0, $this->cant);
        } else {
            // Filtrar materiales por encargado según el usuario logueado o el seleccionado.
            $encargadoId = auth()->user()->id_rol == 7 && $this->SelectEncargado > 0
            ? $this->SelectEncargado
            : $this->UserId;

            $datos = MaterialModel::where('id_encargado', $encargadoId)
                ->where(function ($query) {
                    $query->where('nombre', 'like', '%' . $this->search . '%')
                        ->orWhere('modelo', 'like', '%' . $this->search . '%')
                        ->orWhere('stock', 'like', '%' . $this->search . '%')
                        ->orWhere('descripcion', 'like', '%' . $this->search . '%')
                        ->orWhere('localizacion', 'like', '%' . $this->search . '%')
                        ->orWhereHas('marca', function ($query) {
                            $query->where('nombre', 'like', '%' . $this->search . '%');
                        })
                        ->orWhereHas('categoria', function ($query) {
                            $query->where('nombre', 'like', '%' . $this->search . '%');
                        })
                        ->orWhereHas('encargado', function ($query) {
                            $query->where('nombre', 'like', '%' . $this->search . '%')
                                ->orWhere('apellido_p', 'like', '%' . $this->search . '%')
                                ->orWhere('apellido_m', 'like', '%' . $this->search . '%');
                        });
                })
                ->orderBy($this->sort, $this->direc)
                ->paginate($this->cant)
                ->withQueryString();
        }

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
