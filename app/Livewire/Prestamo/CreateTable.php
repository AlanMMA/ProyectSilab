<?php

namespace App\Livewire\Prestamo;

use Livewire\Component;

class CreateTable extends Component
{
    public $solicitanteInfo;

    protected $listeners = ['guardarSolicitante'];

    public function guardarSolicitante(array $data) // Asegúrate de tipar el parámetro como array
    {
        $this->solicitanteInfo = $data['id'] . ' - ' . $data['solicitante'];
    }
    

    public function render()
    {
        return view('livewire.prestamo.create-table');
    }
}

