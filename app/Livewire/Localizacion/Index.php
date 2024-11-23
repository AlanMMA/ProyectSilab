<?php

namespace App\Livewire\Localizacion;

use App\Models\localizacion;
use Livewire\Component;
use Livewire\WithPagination;

class Index extends Component
{
    public $search;
    public $sort = 'id';
    public $direc = 'asc';
    public $cant = '10';
    public $usuario;
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
        $cat = localizacion::find($id);

        if ($cat) {
            $cat->delete();
        }
    }


    public function render()
    {           
        $usuario = auth()->user()->id_encargado;
        $datos = localizacion::where('nombre', 'like', '%' . $this->search . '%')
            ->where('id_encargado', $usuario)
            ->orderBy($this->sort, $this->direc)
            ->paginate($this->cant)
            ->withQueryString();
        return view('livewire.localizacion.index', compact('datos'));
    }
}
