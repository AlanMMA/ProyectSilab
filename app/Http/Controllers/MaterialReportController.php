<?php

namespace App\Http\Controllers;

use App\Models\CategoriaModel;
use App\Models\EncargadoModel;
use App\Models\LaboratorioModel;
use App\Models\localizacion;
use App\Models\MarcaModel;
use App\Models\MaterialModel;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Request;

class MaterialReportController extends Controller
{
    public $UserId;
    /*public function generatePDF()
    {
    $datos = AreaModel::all();
    $pdf = Pdf::loadView('reportes.areas-pdf', compact('datos'));
    return $pdf->download('reporte_areas.pdf');
    }*/

    public function generatePDF(Request $request)
    {
        // Obtener el usuario logueado

        $user = auth()->user();
        $user2 = auth()->user()->id_encargado;
        $lab = EncargadoModel::where('id', $user2)
        ->pluck('id_laboratorio')
        ->first();

        if (!$user) {
            // Si no hay usuario logueado, devolver un error
            return response()->json(['error' => 'No estás autenticado'], 401);
        }

        // Obtener los parámetros de la URL
        $SelectEncargado = $request->query('encargado', ''); // Valor del encargado
        $search = $request->query('search', ''); // Filtro de búsqueda
        $sort = $request->query('sort', 'id'); // Ordenar por campo
        $direc = $request->query('direc', 'asc'); // Dirección de orden
        $cant = $request->query('cant', 10); // Número de registros (no afecta exportación completa)

        // Construir la consulta inicial
        $query = MaterialModel::query();
        $encargadoNombre = null;
        $incluirEncargado = false;

        if (auth()->user()->id_rol == 7) {
            if ($SelectEncargado == -1) {
                // Jefe seleccionó "todos los encargados", no se filtra por `id_encargado`
                $incluirEncargado = true; // Mostrar la columna con el encargado
            } elseif ($SelectEncargado > 0) {
                // Jefe seleccionó un encargado específico
                $query->where('id_laboratorio', $SelectEncargado);
                //Obtiene el nombre del encargado seleccionado
                $encargado = LaboratorioModel::find($SelectEncargado);
                $encargadoNombre = $encargado ? $encargado->nombre . ' ' . $encargado->apellido_p : null;
            } else {
                // Jefe no seleccionó un encargado válido
                return redirect()->back()->with('error', 'Por favor, seleccione un encargado para exportar los datos.');
            }
        } else {
            // No es jefe, filtrar por el encargado asignado al usuario
            $query->where('id_laboratorio', $lab);
            $encargado = LaboratorioModel::find($lab);
            $encargadoNombre = $encargado ? $encargado->nombre . ' ' . $encargado->apellido_p : null;
        }

        // Aplicar filtros adicionales (búsqueda)
        if ($search) {
            $query->where(function ($query) use ($search) {
                $query->where('nombre', 'like', '%' . $search . '%')
                    ->orWhere('modelo', 'like', '%' . $search . '%')
                    ->orWhere('stock', 'like', '%' . $search . '%')
                    ->orWhere('descripcion', 'like', '%' . $search . '%')
                    ->orWhereHas('marca', function ($q) use ($search) {
                        $q->where('nombre', 'like', '%' . $search . '%');
                    })
                    ->orWhereHas('categoria', function ($q) use ($search) {
                        $q->where('nombre', 'like', '%' . $search . '%');
                    })
                    ->orWhereHas('localizacion', function ($q) use ($search) {
                        $q->where('nombre', 'like', '%' . $search . '%');
                    })
                    ->orWhereHas('encargado', function ($q) use ($search) {
                        $q->where('nombre', 'like', '%' . $search . '%')
                            ->orWhere('apellido_p', 'like', '%' . $search . '%')
                            ->orWhere('apellido_m', 'like', '%' . $search . '%');
                    });
            });
        }

        // Aplicar ordenamiento por nombre de marca, encargado o categoría si se especifica
        if ($sort == 'id_marca') {
            $query->orderBy(
                MarcaModel::select('nombre')->whereColumn('marca.id', 'material.id_marca'),
                $direc
            );
        } elseif ($sort == 'id_laboratorio') {
            $query->orderBy(
                LaboratorioModel::select('nombre')->whereColumn('laboratorio.id', 'material.id_laboratorio'),
                $direc
            );
        } elseif ($sort == 'id_categoria') {
            $query->orderBy(
                CategoriaModel::select('nombre')->whereColumn('categoria.id', 'material.id_categoria'),
                $direc
            );
        } elseif ($sort == 'id_localizacion') {
            $query->orderBy(
                localizacion::select('nombre')->whereColumn('localizacion.id', 'material.id_localizacion'),
                $direc
            );
        } else {
            // Para otros campos, ordenar normalmente por el campo recibido
            $query->orderBy($sort, $direc);
        }

        // Obtener los datos sin limitar (para exportar todos)
        $datos = $query->get();

        if ($datos->isEmpty()) {
            // Mensaje de error al no encontrar datos
            session()->flash('error', 'No hay materiales disponibles para exportar.');
            return redirect()->back();
        } else {
            // Generar el PDF con los datos filtrados
            $pdf = Pdf::loadView('reportes.materiales-pdf', compact('datos', 'encargadoNombre', 'incluirEncargado', 'search', 'sort', 'direc'));

            // Generar el timestamp para el nombre del archivo
            $timestamp = now()->setTimezone('America/Mexico_City')->format('Y-m-d_H-i-s'); // Formato: Año-Mes-Día_Hora-Minuto-Segundo
            $fileName = "reporte_materiales_{$timestamp}.pdf";

            // Descargar el PDF
            return $pdf->download($fileName);
        }
    }

    /*public function generateXML()
    {
    $datos = MaterialModel::all();

    $xml = new \SimpleXMLElement('<materiales/>');
    foreach ($datos as $dato) {
    $material = $xml->addChild('material');
    $material->addChild('id', $dato->id);
    $material->addChild('nombre', $dato->nombre);
    }

    return response($xml->asXML(), 200)
    ->header('Content-Type', 'application/xml')
    ->header('Content-Disposition', 'attachment; filename="reporte_areas.xml"');
    }*/

    /*public function generateExcel()
{

// Pasar los datos filtrados al exportador de Excel
return Excel::download(new AreasExport, 'reporte_areas.xlsx');
}*/

}
