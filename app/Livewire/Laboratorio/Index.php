<?php

namespace App\Livewire\Laboratorio;

use App\Models\LaboratorioModel;
use Illuminate\Support\Facades\DB;
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
        $datos = LaboratorioModel::withCount('encargados') // Agrega el conteo de encargados
            ->where('nombre', 'like', '%' . $this->search . '%')
            ->orderBy($this->sort, $this->direc)
            ->paginate($this->cant)
            ->withQueryString();

        return view('livewire.laboratorio.index', compact('datos'));
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
        // Buscar el laboratorio
        $laboratorio = LaboratorioModel::find($id);

        if (!$laboratorio) {
            $this->dispatch('deletionError', 'El laboratorio no existe.');
            return;
        }

        // Verificar si el laboratorio está relacionado con encargados
        $encargadosRelacionados = DB::table('encargado')
            ->where('id_laboratorio', $id)
            ->pluck('id');

        if ($encargadosRelacionados->isNotEmpty()) {
            $idsMostrados = $encargadosRelacionados->take(10)->implode(', ');
            $mensajeAdicional = $encargadosRelacionados->count() > 10
                ? ' y más...'
                : '';

            $this->dispatch(
                'deletionError',
                'No se puede eliminar el laboratorio, está relacionado con los siguientes encargados: ' . $idsMostrados . $mensajeAdicional
            );
            return;
        }

        // Si no hay relaciones, proceder a eliminar el laboratorio
        try {
            $laboratorio->delete();
            $this->dispatch('deletionSuccess', 'Laboratorio eliminado correctamente.');
        } catch (\Exception $e) {
            $this->dispatch('deletionError', 'Hubo un error al eliminar el laboratorio: ' . $e->getMessage());
        }
    }
}
