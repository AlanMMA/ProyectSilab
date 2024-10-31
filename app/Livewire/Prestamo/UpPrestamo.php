<?php

namespace App\Livewire\Prestamo;

use App\Models\DetallePrestamoModel;
use App\Models\PrestamoModel;
use App\Models\SolicitanteModel;
use Livewire\Component;

class UpPrestamo extends Component
{

    public $solicitantes = [], $selectedDetalles = [];
    public $cant = 0, $cantid, $search = '', $detalles;
    public $selectedSolicitante = null, $solicitanteSeleccionado = false;
    public $listeners = ['alerta', 'devolverMaterial'];

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
            } else {
                $this->solicitanteSeleccionado = true;
            }
        } else {
            $this->detalles = [];
        }
    }

    public function toggleDetalleSelection($detalleId)
    {
        if (in_array($detalleId, $this->selectedDetalles)) {
            $this->selectedDetalles = array_diff($this->selectedDetalles, [$detalleId]);
        } else {
            $this->selectedDetalles[] = $detalleId;
        }
    }

    public function devolverMaterial()
    {
        foreach ($this->selectedDetalles as $detalleId) {
            $detalle = DetallePrestamoModel::find($detalleId);

            if ($detalle) {

                $material = $detalle->materialDP;
                $material->stock += $detalle->cantidad;
                $material->save();

                $detalle->EstadoPrestamo = 'devuelto';
                $detalle->save();
            }
        }

        $this->selectedDetalles = [];
        $this->filtrarDetalles();
        $this->search = null;
        $this->dispatch('devolucionExitosa');
        $this->solicitanteSeleccionado = false;
    }

    public function confirmarDevolucion()
    {

        if (empty($this->selectedDetalles)) {

            $this->dispatch('noHaySeleccion');
        } else {
            $this->dispatch('confirmarDevolucionMaterial');
        }
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
