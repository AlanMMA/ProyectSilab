<?php

namespace App\Livewire\Home;

use App\Models\MaterialModel;
use App\Models\PrestamoModel;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;

class CardPrest extends Component
{
    public $prestamos = [];
    public $days = 7;

    public function mount()
    {
        $this->actualizarPrestamos();
    }
    
    public function actualizarPrestamos()
    {
        $fechaActual = Carbon::now();
        $fechaLimite = Carbon::now()->addDays($this->days);
    
        $this->prestamos = PrestamoModel::where('id_encargado', auth()->user()->id_encargado)
            ->whereHas('detalles', function ($query) use ($fechaActual, $fechaLimite) {
                $query->whereBetween('fecha_devolucion', [$fechaActual, $fechaLimite])
                      ->where('EstadoPrestamo', 'pendiente');
            })
            ->with(['detalles' => function ($query) use ($fechaActual, $fechaLimite) {
                $query->whereBetween('fecha_devolucion', [$fechaActual, $fechaLimite])
                      ->where('EstadoPrestamo', 'pendiente');
            }])
            ->limit(6)
            ->get();
    }
    
    public function actualizarDias($diasSeleccionados)
    {
        $this->days = $diasSeleccionados;
        $this->actualizarPrestamos();
    }


    public function render()
    {
        return view('livewire.home.card-prest');
    }
}