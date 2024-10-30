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
    public $mostrarModal = false;
    public $prestamoId;

    public function verDetalle($id)
    {
        $this->prestamoId = $id;
        $this->mostrarModal = true;
        $this->dispatch('open-modal');
    }

    public function cerrarModal()
    {
        $this->mostrarModal = false;
    }

    public function render()
    {
        $datos = PrestamoModel::join('solicitante', 'prestamo.id_solicitante', '=', 'solicitante.id')
            ->join('encargado', 'prestamo.id_encargado', '=', 'encargado.id')
            ->select(
                'prestamo.id',
                'prestamo.fecha AS fecha_prestamo', // Incluye la fecha del préstamo
                'solicitante.nombre AS solicitante_nombre',
                'solicitante.apellido_p AS solicitante_apellido_p',
                'solicitante.apellido_m AS solicitante_apellido_m',
                'solicitante.tipo AS solicitante_tipo',
                'encargado.nombre AS encargado_nombre',
                'encargado.apellido_p AS encargado_apellido_p',
                'encargado.apellido_m AS encargado_apellido_m'
            )
            ->where(function ($query) {
                $query->where('solicitante.nombre', 'like', '%' . $this->search . '%')
                    ->orWhere('solicitante.apellido_p', 'like', '%' . $this->search . '%')
                    ->orWhere('solicitante.apellido_m', 'like', '%' . $this->search . '%')
                    ->orWhere('solicitante.tipo', 'like', '%' . $this->search . '%')
                    ->orWhere('encargado.nombre', 'like', '%' . $this->search . '%')
                    ->orWhere('encargado.apellido_p', 'like', '%' . $this->search . '%')
                    ->orWhere('encargado.apellido_m', 'like', '%' . $this->search . '%')
                    ->orWhere('prestamo.fecha', 'like', '%' . $this->search . '%');
            })
            ->orderBy($this->sort, $this->direc)
            ->paginate($this->cant)
            ->withQueryString();

        return view('livewire.prestamo.index', compact('datos'));
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

}
