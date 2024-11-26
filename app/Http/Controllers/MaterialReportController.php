<?php

namespace App\Http\Controllers;

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

        if (!$user) {
            // Si no hay usuario logueado, devolver un error
            return response()->json(['error' => 'No estás autenticado'], 401);
        }

        // Obtener los parámetros de la URL (como búsqueda, orden, etc.)
        $SelectEncargado = $request->query('encargado', ''); // Recibe el valor del encargado
        $search = $request->query('search', ''); // Filtro de búsqueda
        $sort = $request->query('sort', 'id'); // Ordenar por campo 'id'
        $direc = $request->query('direc', 'asc'); // Dirección de orden
        $cant = $request->query('cant', 10); // Número de registros a mostrar

        if (auth()->user()->id_rol == 7) {
            if ($SelectEncargado > 0) {
                // Se seleccionó un encargado válido.
                $encargadoId = $SelectEncargado;
            } else {
                // No se seleccionó un encargado.
                return redirect()->back()->with('error', 'Por favor, seleccione un encargado para exportar los datos.');
            }
        } else {
            // No es jefe, asignar el encargado basado en el usuario logueado.
            $encargadoId = $user2;
        }
        // Construir la consulta
        $query = MaterialModel::query();

        // Filtrar materiales por encargado seleccionado
        $query->where('id_encargado', $encargadoId);

        // Aplicar filtros adicionales
        if ($search) {
            $query->where('nombre', 'like', '%' . $search . '%');
        }

        $query->orderBy($sort, $direc);

        // Obtener los datos
        $datos = $query->limit($cant)->get();

        if ($datos->isEmpty()) {
            // Mensaje de error al no encontrar datos
            session()->flash('error', 'No hay materiales disponibles para exportar.');
            return redirect()->back();
        } else {

            // Generar el PDF con los datos filtrados
            $pdf = Pdf::loadView('reportes.materiales-pdf', compact('datos', 'search', 'sort', 'direc', 'cant'));

            // Descargar el PDF
            return $pdf->download('reporte_materiales.pdf');
        }

    }

    public function generateXML()
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
    }

    /*public function generateExcel()
{

// Pasar los datos filtrados al exportador de Excel
return Excel::download(new AreasExport, 'reporte_areas.xlsx');
}*/
}
