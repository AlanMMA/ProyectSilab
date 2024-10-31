<?php

namespace App\Livewire\Prestamo;

use App\Models\DetallePrestamoModel;
use App\Models\MaterialModel;
use App\Models\PrestamoModel;
use Illuminate\Support\Facades\DB;
use Livewire\Component;
use Illuminate\Support\Str;

class CreateTable extends Component
{
    public $solicitanteInfo, $nombreSolicitante, $solicitanteId, $texto, $idPrest;
    public $solicitanteSeleccionadoT = false;
    public $selectedMaterials = [], $textos = [];
    public $fechaDev, $nuevoMaterial, $materialEliminado;
    public $buttonTable = false;
    public $mostrarDatos = false;
    public $datosPrestamo;
    public $datosDetallePrestamo;


    protected $listeners = [
        'guardarSolicitante',
        'resetDatos',
        'materialAgregado',
        'removeMaterial',
        'mostrarBoton' => 'setButtonVisibility',
        'guardarPrestamo' => 'guardarPrestamo'
    ];


    public function mount()
    {
        $this->calcularSiguienteId();
    }

    public function calcularSiguienteId()
    {
        $ultimoPrestamo = PrestamoModel::max('id');
        $this->idPrest = $ultimoPrestamo ? $ultimoPrestamo + 1 : 1;
    }

    public function setButtonVisibility($value)
    {
        $this->buttonTable = $value;
    }


    public function guardarSolicitante(array $data)
    {
        $this->solicitanteId = $data['id'];
        $this->nombreSolicitante = $data['solicitante'];
        $this->solicitanteInfo = $this->solicitanteId . ' - ' . $this->nombreSolicitante;
        $this->solicitanteSeleccionadoT = true;
    }

    public function confirmarSeleccion2()
    {
        $this->dispatch('confirmarSeleccion2');
    }

    public function confirmarEliminacion()
    {
        $this->dispatch('confirmarEliminacion',);
    }

    public function resetDatos()
    {
        $materialesEliminados = array_column($this->selectedMaterials, 'id');
        $this->solicitanteInfo = '';
        $this->solicitanteSeleccionadoT = false;
        $this->selectedMaterials = [];
        $this->buttonTable = false;
        $this->dispatch('resetearMateriales', ['ids' => $materialesEliminados]);
        $this->dispatch('resetearFormulario');
    }

    public function materialAgregado($data)
    {
        $this->selectedMaterials[] = [
            'id' => $data['id'],
            'nombre' => $data['nombre'],
            'cantidad' => $data['cantidad'],
            'fechaPrestamo' => $data['fechaPrestamo'],
            'fechaDev' => $data['fechaDev']
        ];
    }

    public function removeMaterial($index)
    {
        logger("Se ha ejecutado removeMaterial con índice: " . $index);
        $materialEliminado = $this->selectedMaterials[$index];
        unset($this->selectedMaterials[$index]);
        $this->selectedMaterials = array_values($this->selectedMaterials);
        $this->dispatch('materialEliminado', ['id' => $materialEliminado['id']]);
    }

    // public function updatedFechaDev($value)
    // {
    //     $this->dispatch('fechaDevUpdated', ['fechaDev' => $value]);
    // }
    
    public function updated($propertyName)
    {
        if (Str::startsWith($propertyName, 'selectedMaterials.') && Str::endsWith($propertyName, '.observacion')) {

            $index = explode('.', $propertyName)[1];

            $originalText = $this->selectedMaterials[$index]['observacion'];
            $cleanedText = preg_replace('/[^a-zA-Z0-9áéíóúÁÉÍÓÚñÑüÜ ]/', '', $originalText);

            if ($originalText !== $cleanedText) {
                $this->selectedMaterials[$index]['observacion'] = $cleanedText;
                $this->dispatch('ErrorPrestamo', 'Los caracteres especiales en el campo de observación no son aceptados');
            }
        }
    }

    public function confirmarPrestamo()
    {

        $this->dispatch('confirmarPrestamo');
    }

    public function guardarPrestamo()
    {
        foreach ($this->selectedMaterials as $material) {
            if (!array_key_exists('observacion', $material) || empty(trim($material['observacion']))) {
                $this->dispatch('ErrorPrestamo', 'La observación de al menos uno de los materiales está vacía.');
                return;
            }
        }
        foreach ($this->selectedMaterials as $material) {
            $materialData = MaterialModel::find($material['id']);
            if (!$materialData) {
                $this->dispatch('ErrorPrestamo', 'El material seleccionado no existe.');
                return;
            }
            if ($materialData->stock < $material['cantidad']) {
                $this->dispatch('ErrorPrestamo', 'No hay suficiente stock para el material: ' . $material['nombre'] . '. Stock disponible: ' . $materialData->stock);
                return;
            }
            if ($material['cantidad'] < 1) {
                $this->dispatch('ErrorPrestamo', 'La cantidad debe ser al menos 1 para el material: ' . $material['nombre']);
                return;
            }
        }

        DB::beginTransaction();

        try {
            if (count($this->selectedMaterials) > 0) {
                $primerMaterial = $this->selectedMaterials[0];

                $prestamo = PrestamoModel::create([
                    'fecha' => $primerMaterial['fechaPrestamo'],
                    'id_encargado' => auth()->user()->id_encargado,
                    'id_solicitante' => $this->solicitanteId
                ]);

                $prestamoId = $prestamo->id;
                $estadoPrest = 'pendiente';
                foreach ($this->selectedMaterials as $material) {

                    DetallePrestamoModel::create([
                        'id_prestamo' => $prestamoId,
                        'fecha_prestamo' => $material['fechaPrestamo'],
                        'fecha_devolucion' => $material['fechaDev'],
                        'id_material' => $material['id'],
                        'cantidad' => $material['cantidad'],
                        'EstadoPrestamo' => $estadoPrest,
                        'observacion' => $material['observacion']
                    ]);
                    $materialData = MaterialModel::find($material['id']);
                    $materialData->stock -= $material['cantidad'];
                    $materialData->save();
                }

                DB::commit();
                $this->dispatch('alert', 'El préstamo se ha guardado con éxito.');
                $this->resetDatos();
                $this->calcularSiguienteId();
            } else {
                DB::rollBack();
                $this->dispatch('errorGuardado', 'No hay materiales seleccionados para guardar el préstamo.');
            }
        } catch (\Exception $e) {
            DB::rollBack();

            $this->dispatch('errorGuardado', 'Hubo un error al guardar el préstamo: ' . $e->getMessage());
        }
    }




    // public function verificarDatos()
    // {
    //     if (count($this->selectedMaterials) > 0) {
    //         $primerMaterial = $this->selectedMaterials[0];

    //         $this->datosPrestamo = [
    //             'fecha' => $primerMaterial['fechaPrestamo'],  
    //             'id_encargado' => auth()->user()->id_encargado,
    //             'id_solicitante' => $this->solicitanteId,
    //         ];

    //         $this->datosDetallePrestamo = [];
    //         foreach ($this->selectedMaterials as $material) {
    //             $this->datosDetallePrestamo[] = [
    //                 'id_prestamo' => 'prueba',  
    //                 'fecha_prestamo' => $material['fechaPrestamo'],  
    //                 'fecha_devolucion' => $material['fechaDev'],
    //                 'id_material' => $material['id'],
    //                 'cantidad' => $material['cantidad'],
    //                 'observacion' => $material['observacion'] ?? 'Sin observación', 
    //             ];
    //         }

    //         $this->mostrarDatos = true;
    //     } else {
    //         $this->mostrarDatos = false;
    //         session()->flash('error', 'No hay materiales seleccionados.');
    //     }
    // }


    public function render()
    {
        return view('livewire.prestamo.create-table');
    }
}
