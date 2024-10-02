<?php

namespace App\Livewire\Solicitante;

use App\Models\AreaModel;
use App\Models\SolicitanteModel;
use Livewire\Component;
use Livewire\WithPagination;

class Index extends Component
{
    public $search;
    public $sort = 'id';
    public $nombre, $apellido_p, $apellido_m, $id_area, $tipo,
        $numero_control;
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
        $datos = SolicitanteModel::where('nombre', 'like', '%' . $this->search . '%')
            ->orWhere('apellido_p', 'like', '%' . $this->search . '%')
            ->orWhere('apellido_m', 'like', '%' . $this->search . '%')
            ->orWhere('tipo', 'like', '%' . $this->search . '%')
            ->orWhereHas('area', function ($query) {
                $query->where('nombre', 'like', '%' . $this->search . '%');
            })
            ->when($this->sort == 'id_area', function ($query) {
                $query->orderBy(
                    AreaModel::select('nombre')
                        ->whereColumn('area.id', 'solicitante.id_area'),
                    $this->direc
                );
            }, function ($query) {
                $query->orderBy($this->sort, $this->direc);
            })
            ->paginate($this->cant)
            ->withQueryString();
        return view('livewire.solicitante.index', compact('datos'));
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
        $cat = SolicitanteModel::find($id);

        if ($cat) {
            $cat->delete();
        }
    }
}
