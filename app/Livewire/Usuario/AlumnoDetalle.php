<?php

namespace App\Livewire\Usuario;

use App\Models\Alumnos_ServicioModel;
use App\Models\PrestamoModel;
use Livewire\Component;

class AlumnoDetalle extends Component
{

    public $alumnos;

    public function mount($id = null){
        if ($id) {
            $this->cargarDetalles($id);
        }
    }

    public function cargarDetalles($id)
    {
        $this->alumnos = Alumnos_ServicioModel::find($id);
    
        if (!$this->alumnos) {
            $this->alumnos = null;
        }
    }

    


    public function render()
    {
        return view('livewire.usuario.alumno-detalle');
    }
}
