<?php

namespace App\Livewire\Home;

use App\Models\MaterialModel;
use App\Models\PrestamoModel;
use Carbon\Carbon;
use Livewire\Component;

class CardStock extends Component
{
    public $materiales = [];

    public function mount(){
        $this->materiales = MaterialModel::where('stock', '<', 50)
        ->where('id_encargado', Auth()->user()->id_encargado) 
        ->limit(4)
        ->get();
    }



    public function render()
    {
        return view('livewire.home.card-stock');
    }
}
