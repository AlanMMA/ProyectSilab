<?php

namespace App\Http\Controllers;

use App\Models\EncargadoModel;
use App\Models\PrestamoModel;
use App\Models\SolicitanteModel;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Request;

class PrestamoReportController extends Controller
{
    public $UserId;

    public function generatePDF(Request $request)
    {
        // Obtener el usuario logueado
        $user = auth()->user();
        $user2 = auth()->user()->id_encargado;

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
        $query = PrestamoModel::query();
        $encargadoNombre = null;
        $incluirEncargado = false;

        if (auth()->user()->id_rol == 7) {
            if ($SelectEncargado == -1) {
                // Jefe seleccionó "todos los encargados", no se filtra por `id_encargado`
                $incluirEncargado = true; // Mostrar la columna con el encargado
            } elseif ($SelectEncargado > 0) {
                // Jefe seleccionó un encargado específico
                $query->where('id_encargado', $SelectEncargado);
                //Obtiene el nombre del encargado seleccionado
                $encargado = EncargadoModel::find($SelectEncargado);
                $encargadoNombre = $encargado ? $encargado->nombre . ' ' . $encargado->apellido_p : null;
            } else {
                // Jefe no seleccionó un encargado válido
                return redirect()->back()->with('error', 'Por favor, seleccione un encargado para exportar los datos.');
            }
        } else {
            // No es jefe, filtrar por el encargado asignado al usuario
            $query->where('id_encargado', $user2);
            $encargado = EncargadoModel::find($user2);
            $encargadoNombre = $encargado ? $encargado->nombre . ' ' . $encargado->apellido_p : null;
        }

        // Aplicar filtros adicionales (búsqueda)
        if ($search) {
            $query->where(function ($query) use ($search) {
                $query->where('fecha', 'like', '%' . $search . '%')
                    ->orWhereHas('solicitante', function ($q) use ($search) {
                        $q->where('nombre', 'like', '%' . $search . '%')
                            ->orWhere('apellido_p', 'like', '%' . $search . '%')
                            ->orWhere('apellido_m', 'like', '%' . $search . '%')
                            ->orWhere('tipo', 'like', '%' . $search . '%');
                    });
            });
        }

        // Aplicar ordenamiento por nombre de fecha, solicitante o tipo si se especifica
        if ($sort == 'fecha_prestamo') {
            $query->orderBy('prestamo.fecha', $direc);
        } elseif ($sort == 'id_solicitante') {
            $query->orderBy(
                SolicitanteModel::select('nombre')->whereColumn('solicitante.id', 'prestamo.id_solicitante'),
                $direc
            );
        } elseif ($sort == 'tipo') {
            $query->join('solicitante', 'prestamo.id_solicitante', '=', 'solicitante.id')
                ->orderBy('solicitante.tipo', $direc);
        } elseif ($sort == 'id_encargado') {
            $query->orderBy(
                EncargadoModel::select('nombre')->whereColumn('encargado.id', 'prestamo.id_encargado'),
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
            session()->flash('error', 'No hay préstamos disponibles para exportar.');
            return redirect()->back();
        } else {
            // Generar el PDF con los datos filtrados
            $pdf = Pdf::loadView('reportes.prestamos-pdf', compact('datos', 'encargadoNombre', 'incluirEncargado', 'search', 'sort', 'direc'));

            // Descargar el PDF
            return $pdf->download('reporte_prestamos.pdf');
        }
    }
}
