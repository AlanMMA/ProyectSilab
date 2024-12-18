<?php

namespace App\Livewire\Prestamo;

use App\Models\EncargadoModel;
use App\Models\MaterialModel;
use App\Models\SolicitanteModel;
use Carbon\Carbon;
use Livewire\Component;

class CreateForm extends Component
{
    public $cant = 0, $cantid, $search = '', $fechaPrestamo, $buttonTable;
    public $prest;
    public $solicitantes = [], $materiales = [], $selectedMaterials = [];
    public $searchMaterial = ''; // Cadena de búsqueda; 
    public $selectedMaterial = null; // Material seleccionado

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

    public function updatedSearchMaterial()
    {
        $datt = EncargadoModel::where('id', auth()->user()->id_encargado)
            ->pluck('id_laboratorio')
            ->first();
        if (empty($this->searchMaterial)) {
            $this->resetSelectedMaterial();
            $this->materiales = MaterialModel::where('id_laboratorio', $datt)->get()->toArray();
            return;
        }
    
        $this->materiales = MaterialModel::where('id_laboratorio', $datt)
            ->where(function ($query) {
                $query->where('nombre', 'like', '%' . $this->searchMaterial . '%')
                      ->orWhere('descripcion', 'like', '%' . $this->searchMaterial . '%')
                      ->orWhere('modelo', 'like', '%' . $this->searchMaterial . '%'); // Nueva condición
            })
            ->get();
    }
    

    public function selectMaterial($id)
    {
        // $this->selectedMaterial = MaterialModel::find($id);
        // if ($this->selectedMaterial) {
        //     $this->searchMaterial = $this->selectedMaterial->nombre;
        // }
        // $this->materiales = [];
        $this->selectMat = $id;
        $this->selectedMaterial = MaterialModel::find($id); // Opcional: Guarda los datos completos si los necesitas
        $this->searchMaterial = $this->selectedMaterial->nombre; // Actualiza el input con el nombre del material seleccionado
        $this->materiales = [];  // Vacía la lista desplegable
    }

    public function resetSelectedMaterial()
    {
        $this->selectedMaterial = null;
        $this->searchMaterial = '';
        $this->materiales = MaterialModel::all();
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
        $this->prest = auth()->user()->id_encargado;
        $this->solicitantes = SolicitanteModel::all();
        // $this->materiales = MaterialModel::where('id_encargado', $this->prest)->get()->toArray();
        $this->GenerarMat();
        $this->fechaPrestamo = now()->setTimezone('America/Mexico_City')->format('Y/m/d');
        $this->selectedMaterials = [];
    }

    public function GenerarMat() {
        $datt = EncargadoModel::where('id', auth()->user()->id_encargado)
        ->pluck('id_laboratorio')
        ->first();
    $this->materiales = MaterialModel::where('id_laboratorio', $datt)->get()->toArray();
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
        $this->resetSelectedMaterial();
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
            $this->resetSelectedMaterial();
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
                $this->resetSelectedMaterial();
                $this->Cantidad = 1;
                $this->dispatch('mostrarBoton', true);
            }
            
        }
    }
}