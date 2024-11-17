<?php

namespace App\Livewire\Usuario;

use App\Models\Alumnos_ServicioModel;
use App\Models\EncargadoModel;
use App\Models\User;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\DB;
use Livewire\Component;
use Livewire\WithPagination;

class Index extends Component
{
    public $search;
    public $sort = 'id';
    public $direc = 'asc';
    public $cant = '10';
    public $UserId, $AlumnoId;
    protected $listeners = ['render' => 'render', 'destroyPost'];
    public $mostrarModal = false;
    use WithPagination;
    public $encargados, $encargados2;
    public $SelectEncargado = 0;

    public function mount()
    {
        $this->sort = 'id';
        $this->direc = 'asc';
        $this->encargados = EncargadoModel::all();
    }
    public function updatingSearch()
    {
        $this->resetPage();
    }

    // public function render()
    // {
    //     $this->UserId = auth()->user()->id_encargado;
    //     $datos = User::where('id_encargado', $this->UserId)
    //     ->where('id_rol', 2)
    //     ->where(function ($query) {
    //         $query->where('name', 'like', '%' . $this->search . '%')
    //               ->orWhere('email', 'like', '%' . $this->search . '%')
    //               ->orWhereHas('rol', function ($query) {
    //                   $query->where('nombre', 'like', '%' . $this->search . '%');
    //               });
    //     })
    //     ->orderBy($this->sort, $this->direc)
    //     ->paginate($this->cant)
    //     ->withQueryString();

    //     return view('livewire.usuario.index', compact('datos'));
    // }

    public function updatedSelectEncargado($value)
    {
        $this->encargados2 = EncargadoModel::find($value);
    }

    public function render()
    {
        $this->UserId = auth()->user()->id_encargado;

        if (auth()->user()->id_rol == 7 && $this->SelectEncargado == 0) {
            $datos = new LengthAwarePaginator([], 0, $this->cant);
        } else {
            $encargadoId = auth()->user()->id_rol == 7 && $this->SelectEncargado > 0
            ? $this->SelectEncargado
            : $this->UserId;
            $datos = User::where('id_encargado', $encargadoId)
                ->where('id_rol', 2)
                ->where(function ($query) {
                    $query->where('name', 'like', '%' . $this->search . '%')
                        ->orWhere('email', 'like', '%' . $this->search . '%')
                        ->orWhereHas('rol', function ($query) {
                            $query->where('nombre', 'like', '%' . $this->search . '%');
                        });
                })
                ->when($this->sort == 'id_estado', function ($query) {
                    $query->join('estadousuario as estado', 'estado.id', '=', 'users.id_estado')
                        ->orderBy('estado.nombre', $this->direc);
                })
                ->when($this->sort == 'no_control', function ($query) {
                    $query->join('alumnos_servicio', 'alumnos_servicio.id', '=', 'users.id_ss')
                        ->orderBy('alumnos_servicio.no_control', $this->direc);
                })
                ->when(!$this->sort || !in_array($this->sort, ['id_estado', 'no_control']), function ($query) {
                    $query->orderBy($this->sort, $this->direc);
                })
                ->paginate($this->cant)
                ->withQueryString();
        }

        return view('livewire.usuario.index', compact('datos'));
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

    public function mostrarDetalle($id)
    {
        $this->AlumnoId = $id;
        $this->mostrarModal = true;
        $this->dispatch('open-modal');
    }

    public function cerrarModal()
    {
        $this->mostrarModal = false;
    }

    public function destroyPost($id)
    {

        DB::beginTransaction();
        $cat = User::find($id);
        $cat2 = $cat->id_ss;

        try {
            $alumnoSS = Alumnos_ServicioModel::where('id', $cat2)->first();

            if ($cat) {
                $cat->delete();
            }
            if ($alumnoSS) {
                $alumnoSS->delete();
            }

            DB::commit();
        } catch (\Exception $e) {
            DB::rollBack();
            throw $e;
        }
    }
}
