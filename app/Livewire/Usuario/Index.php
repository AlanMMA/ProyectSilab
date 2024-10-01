<?php

namespace App\Livewire\Usuario;

use App\Models\User;
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
        $datos = User::where('name', 'like', '%' . $this->search . '%')
            ->orWhere('email', 'like', '%' . $this->search . '%')
            ->orWhereHas('rol', function ($query) {
                $query->where('nombre', 'like', '%' . $this->search . '%');
            })
            ->orderBy($this->sort, $this->direc)
            ->paginate($this->cant)
            ->withQueryString();
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

    public function destroyPost($id)
    {
        $cat = User::find($id);

        if ($cat) {
            $cat->delete();
        }
    }
}
