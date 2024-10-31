<?php

namespace App\Livewire\Prestamo;

use App\Models\MaterialModel;
use App\Models\SolicitanteModel;
use Carbon\Carbon;
use Livewire\Component;

class CreateForm extends Component
{
    public $cant = 0, $cantid, $search = '', $fechaPrestamo, $buttonTable;
    public $solicitantes = [], $materiales = [], $selectedMaterials = [];
    public $solicitanteSeleccionado = false;
    public $solicitanteSeleccionado2 = true;
    protected $listeners = [
        'resetearFormulario',
        'fechaDevUpdated',
        'materialEliminado',
        'resetearMateriales',
        'BlockDat' => 'BlockDat'
    ];
    public $selectedSolicitante = null;
    public $selectMat, $Cantidad = 1, $btnConfirm, $fechaDev, $solicitanteInfo, $nombreSolicitante;


    public function updatedCant($value)
    {
        $this->search = '';
        
        if ($value == 'alumno') {
            $this->solicitantes = SolicitanteModel::where('tipo', 'alumno')->get();
        } elseif ($value == 'docente') {
            $this->solicitantes = SolicitanteModel::where('tipo', 'docente')->get();
        } else {
            $this->solicitantes = SolicitanteModel::all();
        }
    }

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

    public function resetSelectedSolicitante()
    {
        $this->selectedSolicitante = null;
        $this->cantid = null;
        $this->solicitanteSeleccionado = false;
    }

    public function materialEliminado($material)
    {
        logger("Material eliminado: " . json_encode($material));

        $materialId = $material['id'];

        $this->selectedMaterials = array_filter($this->selectedMaterials, function ($material) use ($materialId) {
            return $material['id'] != $materialId;
        });

        $this->selectedMaterials = array_values($this->selectedMaterials);
    }

    public function resetearMateriales($data)
    {
        logger("Materiales eliminados: " . json_encode($data['ids']));
        $this->selectedMaterials = array_filter($this->selectedMaterials, function ($material) use ($data) {
            return !in_array($material['id'], $data['ids']);
        });

        $this->selectedMaterials = array_values($this->selectedMaterials);
    }

    public function BlockDat()
    {
        $this->solicitanteSeleccionado = true;
        $this->solicitanteSeleccionado2 = false;
    }

    public function selectSolicitante($id)
    {
        $this->selectedSolicitante = SolicitanteModel::find($id);
        if ($this->selectedSolicitante) {
            $this->search = $this->selectedSolicitante->nombre . ' ' . $this->selectedSolicitante->apellido_p;
        }
        $this->solicitantes = []; 
        $this->cantid = $id; 
        $this->solicitanteSeleccionado2 = true;
    }

    public function clearSelection()
{
    $this->selectedSolicitante = null; 
    $this->search = '';
    $this->solicitantes = SolicitanteModel::all(); 
    $this->solicitanteSeleccionado2 = false;
}

    public function confirmarSeleccion()
    {
        if ($this->cantid && $this->cantid != '0') {
            $solicitanteSeleccionado = SolicitanteModel::find($this->cantid);

            if ($solicitanteSeleccionado) {
                $this->solicitanteInfo = $solicitanteSeleccionado->id;

                $this->nombreSolicitante = $solicitanteSeleccionado->nombre . ' ' . $solicitanteSeleccionado->apellido_p;

                $this->dispatch('confirmarSeleccion', [
                    'id' => $this->solicitanteInfo,
                    'solicitante' => $this->nombreSolicitante
                ]);
            } else {
                $this->dispatch('solicitanteNoEncontrado');
            }
        } else {
            $this->dispatch('solicitanteNoSeleccionado');
        }
    }

    public function mount()
    {
        $this->solicitantes = SolicitanteModel::all();
        $this->materiales = MaterialModel::all();
        $this->fechaPrestamo = now()->format('Y/m/d');
        $this->selectedMaterials = [];
    }

    public function resetearFormulario()
    {
        $this->solicitanteSeleccionado = null;
        $this->solicitanteSeleccionado2 = false;
        $this->selectedSolicitante = null;
        $this->cant = 0;
        $this->search = '';
        $this->selectMat = 0;
        $this->Cantidad = 1;
        $this->btnConfirm = false;
        $this->solicitantes = [];
        $this->solicitanteSeleccionado2 = true;
        $this->fechaDev = null;
    }

    public function addMaterial()
    {
        if (empty($this->selectMat)) {
            $this->dispatch('mostrarErrorFecha', 'Debe seleccionar un material. antes de agregarlo');
            return;
        } else if (empty($this->Cantidad) || $this->Cantidad < 1) {
            $this->dispatch('mostrarErrorFecha', 'Debe ingresar una cantidad válida mayor a 0.');
            return;
        } else if (empty($this->fechaDev)) {
            $this->dispatch('mostrarErrorFecha', 'Debe seleccionar una fecha de devolución antes de agregar un material.');
            return;
        } else if (Carbon::parse($this->fechaDev)->isBefore(Carbon::today())) {
            $this->dispatch('mostrarErrorFecha', 'Para realizar un préstamo debe elegir una fecha mayor a la actual');
            return;
        } else if (in_array($this->selectMat, array_column($this->selectedMaterials, 'id'))) {
            $this->dispatch('mostrarErrorFecha', 'Este material ya fue agregado.');
            return;
        }

        if ($this->selectMat && $this->Cantidad > 0 && $this->fechaPrestamo) {
            $material = MaterialModel::find($this->selectMat);
            if ($material) {
                $this->selectedMaterials[] = [
                    'id' => $material->id,
                    'nombre' => $material->nombre,
                    'cantidad' => $this->Cantidad,
                    'fechaPrestamo' => $this->fechaPrestamo,
                    'fechaDev' => $this->fechaDev
                ];
                $this->dispatch('materialAgregado', [
                    'id' => $material->id,
                    'nombre' => $material->nombre,
                    'cantidad' => $this->Cantidad,
                    'fechaPrestamo' => $this->fechaPrestamo,
                    'fechaDev' => $this->fechaDev
                ]);
                $this->selectMat = null;
                $this->Cantidad = 1;
                $this->dispatch('mostrarBoton', true);
            }
        }
    }

    public function fechaDevUpdated($data)
    {
        $this->fechaDev = $data['fechaDev'];
    }

    public function render()
    {
        return view('livewire.prestamo.create-form');
    }
}
