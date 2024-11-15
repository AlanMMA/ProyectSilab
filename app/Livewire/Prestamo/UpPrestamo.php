<?php

namespace App\Livewire\Prestamo;

use App\Models\DetallePrestamoModel;
use App\Models\PrestamoModel;
use App\Models\SolicitanteModel;
use Carbon\Carbon;
use Livewire\Component;

class UpPrestamo extends Component
{

    public $solicitantes = [], $selectedDetalles = [];
    public $cant = 0, $cantid, $search = '', $detalles;
    public $selectedSolicitante = null, $solicitanteSeleccionado = false, $selectAll = false;
    public $solicitanteSeleccionado2 = true;
    public $listeners = ['alerta', 'devolverMaterial'];
    public $fecha_entrega;

    public function updatedSearch()
    {
        if (empty($this->search)) {
            $this->resetSelectedSolicitante();
            $this->solicitantes = SolicitanteModel::all();
        }

        if ($this->cant == '0') {
            $this->solicitantes = SolicitanteModel::where('nombre', 'like', '%' . $this->search . '%')
                ->orWhere('apellido_p', 'like', '%' . $this->search . '%')
                ->get();
        } else {
            $this->solicitantes = SolicitanteModel::where('tipo', $this->cant)
                ->where(function ($query) {
                    $query->where('nombre', 'like', '%' . $this->search . '%')
                        ->orWhere('apellido_p', 'like', '%' . $this->search . '%');
                })
                ->get();
        }
    }

    public function selectSolicitante($id)
    {
        $this->selectedSolicitante = SolicitanteModel::find($id);
        if ($this->selectedSolicitante) {
            $this->search = $this->selectedSolicitante->nombre . ' ' . $this->selectedSolicitante->apellido_p;
        }
        $this->solicitantes = [];
        $this->cantid = $id;
    }

    public function resetSelectedSolicitante()
    {
        $this->selectedSolicitante = null;
        $this->cantid = null;
        $this->solicitanteSeleccionado = false;
        $this->detalles = [];
    }

    public function resetView()
    {
        $this->selectedSolicitante = null;
        $this->cantid = null;
        $this->solicitanteSeleccionado = false;
        $this->detalles = [];
        $this->selectedDetalles = [];
        $this->search = null;
        $this->solicitanteSeleccionado2 = true;
    }

    public function filtrarDetalles()
    {
        if ($this->cantid) {
            $prestamos = PrestamoModel::where('id_solicitante', $this->cantid)->pluck('id');

            $this->detalles = DetallePrestamoModel::whereIn('id_prestamo', $prestamos)
                ->whereIn('EstadoPrestamo', ['pendiente', 'atrasado'])
                ->get();

            if ($this->detalles->isEmpty()) {
                $this->dispatch('sinPrestamosPendientes');
                $this->search = null;
                $this->resetSelectedSolicitante();
            } else {
                $this->solicitanteSeleccionado = true;
                $this->solicitanteSeleccionado2 = false;
            }
        } else {
            $this->detalles = [];
        }
    }


    public function devolverMaterial()
    {
        $fechasDevolucion = $this->detalles
            ->whereIn('id', $this->selectedDetalles)
            ->pluck('fecha_devolucion', 'id');

        foreach ($this->selectedDetalles as $detalleId) {
            $detalle = DetallePrestamoModel::find($detalleId);
            if ($detalle) {
                $material = $detalle->materialDP;
                $material->stock += $detalle->cantidad;
                $material->save();

                $fecha_entrega = Carbon::now('America/Mexico_City')->format('Y-m-d');

                $fechaDevolucionDetalle = Carbon::parse($fechasDevolucion[$detalleId]);

                if ($fechaDevolucionDetalle->lt(Carbon::parse($this->fecha_entrega))) {
                    $detalle->EstadoPrestamo = 'devuelto con atrasado';
                } else {
                    $detalle->EstadoPrestamo = 'devuelto';
                }
                $detalle->fecha_entrega = $fecha_entrega;
                $detalle->save();
            }
        }

        $this->filtrarDetalles();
        $this->dispatch('devolucionExitosa');

        
        if (empty($this->detalles)) {
            $this->selectedDetalles = [];
            $this->search = null;
            $this->solicitanteSeleccionado = false;
            $this->solicitanteSeleccionado2 = true;
        }
    }


    public function confirmarDevolucion()
    {

        if (empty($this->selectedDetalles)) {

            $this->dispatch('noHaySeleccion');
        } else {
            $this->dispatch('confirmarDevolucionMaterial');
        }
    }

    public function toggleSelectAll()
    {
        if ($this->selectAll) {
            // Si ya todos están seleccionados, desmarcamos todos
            $this->selectedDetalles = [];
        } else {
            // Si no están todos seleccionados, seleccionamos todos los detalles
            $this->selectedDetalles = $this->detalles->pluck('id')->toArray();
        }
    
        // Cambiar el estado de "selectAll"
        $this->selectAll = !$this->selectAll;
    }
    

    public function toggleDetalleSelection($detalleId)
    {
        // Verificar si el detalle está seleccionado
        if (in_array($detalleId, $this->selectedDetalles)) {
            // Si está seleccionado, lo deseleccionamos
            $this->selectedDetalles = array_diff($this->selectedDetalles, [$detalleId]);
        } else {
            // Si no está seleccionado, lo agregamos
            $this->selectedDetalles[] = $detalleId;
        }
    
        // Verificar si todos los elementos están seleccionados
        $this->selectAll = count($this->selectedDetalles) === count($this->detalles);
    }
    

    // Asegúrate de actualizar el estado de 'selectAll' cada vez que se modifiquen los 'selectedDetalles'
    public function updatedSelectedDetalles()
    {
        // Si todos los elementos están seleccionados, marcamos selectAll como verdadero
        $this->selectAll = count($this->selectedDetalles) === $this->detalles->count();
    }



    public function mount()
    {
        $this->solicitantes = SolicitanteModel::all();
        $this->detalles = [];
    }

    public function render()
    {
        return view('livewire.prestamo.up-prestamo');
    }
}
