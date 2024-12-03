<?php

namespace App\Livewire\Home;

use App\Models\MaterialModel;
use App\Models\PrestamoModel;
use Carbon\Carbon;
use Livewire\Component;

class CardStock extends Component
{
    public $materiales = [];
    public $stockk = 50;

    public function mount()
    {
        $this->stockk = session('stockk', 50);
        $this->ActualizarStock();
    }


    public function ActualizarStock()
    {
        $this->materiales = MaterialModel::where('stock', '<', $this->stockk)
            ->where('id_encargado', Auth()->user()->id_encargado)
            ->limit(4)
            ->get();
    }

    public function ActualizarCifra($dato)
    {
        $this->stockk = $dato;
        session(['stockk' => $dato]); // Guarda en la sesión
        $this->ActualizarStock();
    }

    public function updatedStockk($value)
    {
        session(['stockk' => $value]); // Guarda el nuevo valor en la sesión
        $this->ActualizarStock();
    }




    public function render()
    {
        return view('livewire.home.card-stock');
    }
}
