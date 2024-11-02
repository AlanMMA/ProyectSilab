<?php

namespace App\Livewire\Prestamo;

use App\Models\DetallePrestamoModel;
use App\Models\PrestamoModel;
use Livewire\Component;
use Livewire\WithPagination;

class Detalle extends Component
{

    public $prestamo;
    public $detalles;
    public $search;
    public $sort = 'id';
    public $direc = 'asc';
    public $cant = '10';

    use WithPagination;

    public function mount($id = null)
    {
        $this->sort = 'id';
        $this->direc = 'asc';

        if ($id) {
            $this->cargarDetalles($id);
        }
    }

    public function cargarDetalles($id)
    {
        $this->prestamo = PrestamoModel::with('solicitante')
            ->where('id', $id)
            ->firstOrFail();

        // Verifica si el orden es por el campo de la relación 'material'
        if ($this->sort == 'material_nombre') {
            $this->detalles = DetallePrestamoModel::with('material')
                ->where('id_prestamo', $id)
                ->join('material', 'detalle_prestamo.id_material', '=', 'material.id')
                ->orderBy('material.nombre', $this->direc) // Ordenar por el nombre del material
                ->select('detalle_prestamo.*') // Asegurar que se seleccionen los campos de detalle_prestamo
                ->get();
        } else {
            // Ordenar normalmente por columnas de detalle_prestamo
            $this->detalles = DetallePrestamoModel::with('material')
                ->where('id_prestamo', $id)
                ->orderBy($this->sort, $this->direc) // Aplicar ordenación aquí
                ->get();
        }
    }

    public function updatingSearch()
    {
        $this->resetPage();
    }

    public function render()
    {
        return view('livewire.prestamo.detalle');
    }

    public function order($sort)
    {
        if ($this->sort == $sort) {
            $this->direc = $this->direc == 'desc' ? 'asc' : 'desc';
        } else {
            $this->sort = $sort;
            $this->direc = 'asc';
        }

        // Recarga los detalles con el nuevo orden
        $this->cargarDetalles($this->prestamo->id);
    }
}
