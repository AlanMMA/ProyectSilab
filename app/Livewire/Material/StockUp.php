<?php

namespace App\Livewire\Material;

use App\Models\MaterialModel;
use Livewire\Component;

class StockUp extends Component
{
    public $solicitanteSeleccionado2, $search, $cantid, $cantidad;
    public $materialSearch = '', $newStock = 1;
    public $materiales = [], $tMateriales = [];
    public $selectedMaterial = null, $stockActual = null;
    public $materialSeleccionado = true;
    public $listeners = ['agregarMaterial' , 'guardarCambios'];

    public function updatedMaterialSearch()
    {
        if (empty($this->materialSearch)) {
            $this->resetSelectedMaterial();
            $this->materiales = MaterialModel::where('id_encargado', auth()->user()->id_encargado)->get();
        } else {
            $encargadoId = auth()->user()->id_encargado;
    
            $this->materiales = MaterialModel::where('id_encargado', $encargadoId)
                ->where(function($query) {
                    $query->where('nombre', 'like', '%' . $this->materialSearch . '%')
                        ->orWhere('modelo', 'like', '%' . $this->materialSearch . '%');
                })
                ->get();
        }
    }

                // $this->materiales = MaterialModel::where('nombre', 'like', '%' . $this->materialSearch . '%')
            //     ->orWhere('modelo', 'like', '%' . $this->materialSearch . '%')
            //     ->get();
    public function resetSelectedMaterial()
    {
        $this->selectedMaterial = null;
        $this->cantid = null;
        $this->solicitanteSeleccionado2 = false;
        $this->stockActual = null;
    }


    public function selectMaterial($id)
    {
        $this->selectedMaterial = MaterialModel::find($id);
        if ($this->selectedMaterial) {
            $this->materialSearch = $this->selectedMaterial->nombre . ' (' . $this->selectedMaterial->modelo . ')';
            $this->stockActual = $this->selectedMaterial->stock;
        }
        $this->materiales = [];
    }

    public function agregarMaterial()
    {
        $materialExistente = collect($this->tMateriales)->firstWhere('id', $this->selectedMaterial->id);

        if ($materialExistente) {
            $this->dispatch('errorStock', 'Este material ya fue agregado.');
            $this->selectedMaterial = null;
            $this->materialSearch = '';
            $this->cantidad = null;
            $this->stockActual = null;
        } else {
            $this->tMateriales[] = [
                'id' => $this->selectedMaterial->id,
                'nombre' => $this->selectedMaterial->nombre,
                'cantidad' => $this->newStock,
                'modelo' => $this->selectedMaterial->modelo,
                'observacion' => $this->selectedMaterial->descripcion,
            ];

            // Restablecer los valores
            $this->selectedMaterial = null;
            $this->materialSearch = '';
            $this->cantidad = null;
            $this->stockActual = null;
            $this->materialSeleccionado = true;
            $this->newStock = 1;
        }
    }

    public function confirmarAgregarMaterial() {
        $this->dispatch('confirmarStock');
    }

    public function eliminarMaterial($id)
    {
        $this->tMateriales = array_filter($this->tMateriales, function ($material) use ($id) {
            return $material['id'] !== $id;
        });
    }

    public function guardarCambios(){
        foreach ($this->tMateriales as $material) {
            $materialDB = MaterialModel::find($material['id']);
            
            if ($materialDB) {
                $materialDB->stock += $material['cantidad'];
                $materialDB->save();
                $this->dispatch('exitostock', 'Entradas realizadas con exito');
                $this->tMateriales = [];
            }
        }
    }

    public function render()
    {
        return view('livewire.material.stock-up');
    }
}
