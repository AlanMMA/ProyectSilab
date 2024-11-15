<?php

namespace App\Livewire\Encargado;

use App\Models\EncargadoModel;
use App\Models\LaboratorioModel;
use Livewire\Component;
use Livewire\WithPagination;

class Index extends Component
{

    public $search;
    public $sort = 'id';
    public $nombre, $apellido_p, $apellido_m, $id_laboratorio, $excludedId;
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

        $datos = EncargadoModel::where('nombre', 'like', '%' . $this->search . '%')
            ->orWhere('apellido_p', 'like', '%' . $this->search . '%')
            ->orWhere('apellido_m', 'like', '%' . $this->search . '%')
            ->orWhereHas('laboratorio', function ($query) {
                $query->where('nombre', 'like', '%' . $this->search . '%');
            })
            ->when($this->sort == 'id_laboratorio', function ($query) {
                $query->orderBy(
                    LaboratorioModel::select('nombre')
                        ->whereColumn('laboratorio.id', 'encargado.id_laboratorio'),
                    $this->direc
                );
            }, function ($query) {
                $query->orderBy($this->sort, $this->direc);
            })
            ->paginate($this->cant)
            ->withQueryString();
        return view('livewire.encargado.index', compact('datos'));
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
        $cat = EncargadoModel::find($id);
        if ($cat) {
            $cat->delete();
        }
    }
}
