<?php

namespace App\Livewire\Prestamo;

use App\Models\EncargadoModel;
use App\Models\PrestamoModel;
use Illuminate\Pagination\LengthAwarePaginator;
use Livewire\Component;
use Livewire\WithPagination;

class Index extends Component
{
    public $search;
    public $sort = 'prestamo.id';
    public $direc = 'asc';
    public $cant = '10';
    public $mostrarModal = false;
    public $prestamoId;
    public $encargados, $encargados2, $SelectEncargado = 0;
    public $searchEnabled = false;
    public $rolActual;
    use WithPagination;

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

    public function mount()
    {
        $this->encargados = EncargadoModel::all();
    }

    // public function render()
    // {
    //     $idEncargado = auth()->user()->id_encargado; // Obtén el id_encargado del usuario autenticado

    //     $datos = PrestamoModel::join('solicitante', 'prestamo.id_solicitante', '=', 'solicitante.id')
    //         ->join('encargado', 'prestamo.id_encargado', '=', 'encargado.id')
    //         ->select(
    //             'prestamo.id',
    //             'prestamo.fecha AS fecha_prestamo', // Incluye la fecha del préstamo
    //             'solicitante.nombre AS solicitante_nombre',
    //             'solicitante.apellido_p AS solicitante_apellido_p',
    //             'solicitante.apellido_m AS solicitante_apellido_m',
    //             'solicitante.tipo AS solicitante_tipo',
    //             'encargado.nombre AS encargado_nombre',
    //             'encargado.apellido_p AS encargado_apellido_p',
    //             'encargado.apellido_m AS encargado_apellido_m'
    //         )
    //         ->where('prestamo.id_encargado', $idEncargado) // Filtro por el id_encargado del usuario autenticado
    //         ->where(function ($query) {
    //             $query->where('solicitante.nombre', 'like', '%' . $this->search . '%')
    //                 ->orWhere('solicitante.apellido_p', 'like', '%' . $this->search . '%')
    //                 ->orWhere('solicitante.apellido_m', 'like', '%' . $this->search . '%')
    //                 ->orWhere('solicitante.tipo', 'like', '%' . $this->search . '%')
    //             /*->orWhere('encargado.nombre', 'like', '%' . $this->search . '%')
    //         ->orWhere('encargado.apellido_p', 'like', '%' . $this->search . '%')
    //         ->orWhere('encargado.apellido_m', 'like', '%' . $this->search . '%')*/
    //                 ->orWhere('prestamo.fecha', 'like', '%' . $this->search . '%');
    //         })
    //         ->orderBy($this->sort, $this->direc)
    //         ->paginate($this->cant)
    //         ->withQueryString();

    //     return view('livewire.prestamo.index', compact('datos'));
    // }

    public function updatedSelectEncargado($value)
    {
        $this->encargados2 = EncargadoModel::find($value);
    }

    public function render()
    {
        // Obtener el id del encargado autenticado
        $idEncargado = auth()->user()->id_encargado;

        // Verificar si el usuario es un gerente y el valor de SelectEncargado es 0
        if (auth()->user()->id_rol == 7 && $this->SelectEncargado == 0) {
            // Retornar una colección vacía si el select está en 0
            $datos = new LengthAwarePaginator([], 0, $this->cant);
        } else {
            // Determinar el id del encargado basado en la selección o el usuario autenticado
            $encargadoId = auth()->user()->id_rol == 7 && $this->SelectEncargado > 0
                ? $this->SelectEncargado
                : $idEncargado;

            // Consultar los datos en la tabla de préstamos
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
                ->where('prestamo.id_encargado', $encargadoId) // Filtra según el encargado
                ->where(function ($query) {
                    $query->where('solicitante.nombre', 'like', '%' . $this->search . '%')
                        ->orWhere('solicitante.apellido_p', 'like', '%' . $this->search . '%')
                        ->orWhere('solicitante.apellido_m', 'like', '%' . $this->search . '%')
                        ->orWhere('solicitante.tipo', 'like', '%' . $this->search . '%')
                        ->orWhere('prestamo.fecha', 'like', '%' . $this->search . '%');
                })
                ->orderBy($this->sort, $this->direc)
                ->paginate($this->cant)
                ->withQueryString();
        }

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
