<?php

namespace App\Livewire\Prestamo;

use App\Models\SolicitanteModel;
use Livewire\Component;

class CreateForm extends Component
{
    public $cant;
    public $solicitantes = [];
    public $solicitanteSeleccionado = false;
    public $solicitanteSeleccionado2 = true;
    public function updatedCant($value)
    {

        if ($value == 'alumno') {
            $this->solicitantes = SolicitanteModel::where('tipo', 'alumno')->get();
        } elseif ($value == 'docente') {
            $this->solicitantes = SolicitanteModel::where('tipo', 'docente')->get();
        } else {
            $this->solicitantes = SolicitanteModel::all();
        }
    }


    public function confirmarSeleccion()
    {
        if ($this->cant && $this->cant != '0') {
            $solicitanteSeleccionado = SolicitanteModel::find($this->cant);

            if ($solicitanteSeleccionado) {
                $this->solicitanteSeleccionado = true;
                $this->solicitanteSeleccionado2 = false; 
                $this->dispatch('confirmarSeleccion', [
                    'id' => $solicitanteSeleccionado->id,
                    'solicitante' => trim($solicitanteSeleccionado->nombre . ' ' . $solicitanteSeleccionado->apellido_p)
                ]);
            } else {
                $this->dispatch('solicitanteNoEncontrado');
            }
        } else {
            $this->dispatch('solicitanteNoSeleccionado');
        }
    }

    // public function confirmarSeleccion()
    // {
    //     if ($this->cant && $this->cant != '0') {
    //         $solicitanteSeleccionado = SolicitanteModel::find($this->cant);

    //         if ($solicitanteSeleccionado) {
    //             $this->dispatch('confirmarSeleccion', [
    //                 'id' => $solicitanteSeleccionado->id,
    //                 'solicitante' => trim($solicitanteSeleccionado->nombre . ' ' . $solicitanteSeleccionado->apellido_p)
    //             ]);
    //         } else {
    //             $this->dispatch('solicitanteNoEncontrado');
    //         }
    //     } else {
    //         $this->dispatch('solicitanteNoSeleccionado');
    //     }
    // }


    public function mount()
    {
        $this->solicitantes = SolicitanteModel::all();
    }


    public function render()
    {
        return view('livewire.prestamo.create-form');
    }
}
