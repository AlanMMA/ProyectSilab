<?php

namespace App\Livewire\Prestamo;

use App\Models\PrestamoModel;
use Livewire\Component;

class Index extends Component
{
    public $search;
    public $sort = 'prestamo.id'; 
    public $direc = 'asc';
    public $cant = '10';
    
    public function render()
    {
        $datos = PrestamoModel::join('detalle_prestamo', 'prestamo.id', '=', 'detalle_prestamo.id_prestamo')
            ->join('solicitante', 'prestamo.id_solicitante', '=', 'solicitante.id') 
            ->select(
                'prestamo.id', 
                'detalle_prestamo.fecha_prestamo',
                'detalle_prestamo.fecha_devolucion',
                'solicitante.nombre as solicitante'
            )
            ->distinct()
            ->where('solicitante.nombre', 'like', '%' . $this->search . '%')
            ->orderBy($this->sort, $this->direc)
            ->paginate($this->cant)
            ->withQueryString();
        return view('livewire.prestamo.index', compact('datos'));
    }
}
