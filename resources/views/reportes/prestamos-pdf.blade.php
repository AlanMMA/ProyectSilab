<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Reporte de Préstamos</title>
    <style>
        body {
            margin-top: 85px;
            margin-bottom: 60px;
        }

        table {
            width: 100%;
            border-collapse: collapse;
        }

        th,
        td {
            border: 1px solid black;
            text-align: center;
            padding: 8px;
        }

        /* Definir la clase blue-tec manualmente */
        .bg-blue-tec {
            background-color: #1B396A;
            /* Ajusta este valor al color que desees */
        }

        .text-white {
            color: white;
        }

        /* Estilo para las imágenes de los logos */
        .logo-left {
            position: fixed;
            top: 0px;
            left: 15px;
            width: 45px;
            /* Ajusta el tamaño según lo necesario */
        }

        .logo-right {
            position: fixed;
            top: 10px;
            right: 15px;
            width: 100px;
            /* Ajusta el tamaño según lo necesario */
        }

        .title {
            position: fixed;
            top: 8px;
            left: 0;
            right: 0;
            text-align: center;
            margin: 0;
            padding: 0;
        }

        .subtitle {
            position: fixed;
            top: 40px;
            left: 0;
            right: 0;
            text-align: center;
            margin: 0;
            padding: 0;
        }
    </style>
</head>

<body>

    <!-- Logos en las esquinas -->
    <img src="{{ public_path('images/logo-left.jpg') }}" class="logo-left" alt="Logo Izquierdo">
    <img src="{{ public_path('images/logo-right.jpg') }}" class="logo-right" alt="Logo Derecho">

    <!-- Titulo -->
    <h1 class="title" style="text-align: center;">Reporte de Préstamos</h1>

    @if($encargadoNombre)
    <h3 class="subtitle" style="text-align: center; margin-top: 10px; font-weight: normal;">Préstamos del encargado:
        <span style="font-weight: bold;">{{ $encargadoNombre }} </span>
    </h3>
    @endif

    <!-- Contenido de la tabla -->
    <table>
        <thead class="bg-blue-tec text-white">
            <tr>
                <th>ID</th>
                <th>Fecha</th>
                <th>Solicitante</th>
                <th>Tipo</th>
                <th>No. Control</th>
                @if($incluirEncargado)
                <th>Encargado</th>
                @endif
            </tr>
        </thead>
        <tbody>
            @foreach ($datos as $dato)
            <tr>
                <td>{{ $dato->id }}</td>
                <td>{{ $dato->fecha }}</td>
                <td>{{ $dato->solicitante ? $dato->solicitante->nombre . ' ' . $dato->solicitante->apellido_p . ' ' .
                    $dato->solicitante->apellido_m : 'Sin
                    solicitante' }}</td>
                <td>{{ $dato->solicitante->tipo }}</td>
                <td>{{ $dato->solicitante->numero_control ?? 'No asignado' }}</td>
                @if($incluirEncargado)
                <td>{{ $dato->encargado ? $dato->encargado->nombre . ' ' . $dato->encargado->apellido_p : 'Sin
                    encargado' }}</td>
                @endif
            </tr>
            @endforeach
        </tbody>
    </table>

</body>

</html>