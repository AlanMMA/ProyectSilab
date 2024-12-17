<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Reporte de Materiales</title>
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
            word-wrap: break-word;
            word-break: break-word;
            max-width: 75px;
            overflow-wrap: break-word;
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
    <h1 class="title" style="text-align: center;">Reporte de Materiales</h1>

    @if($encargadoNombre)
    <h3 class="subtitle" style="text-align: center; margin-top: 10px; font-weight: normal;">Materiales del laboratorio:
        <span style="font-weight: bold;">{{ $encargadoNombre }} </span>
    </h3>
    @endif

    <table>
        <thead class="bg-blue-tec text-white">
            <tr>
                <th>ID</th>
                <th>Nombre</th>
                <th>Marca</th>
                <th>Modelo</th>
                <th>Categoria</th>
                <th>Stock</th>
                <th>Descripción</th>
                <th>Localización</th>
                @if($incluirEncargado)
                <th>Laboratorio</th>
                @endif
            </tr>
        </thead>
        <tbody>
            @foreach ($datos as $dato)
            <tr>
                <td>{{ $dato->id }}</td>
                <td>{{ $dato->nombre }}</td>
                <td>{{ $dato->marca->nombre }}</td>
                <td>{{ $dato->modelo }}</td>
                <td>{{ $dato->categoria->nombre }}</td>
                <td>{{ $dato->stock }}</td>
                <td>{{ $dato->descripcion }}</td>
                <td>{{ $dato->localizacion ? $dato->localizacion->nombre : 'Sin
                    encargado'}}</td>
                @if($incluirEncargado)
                <td>{{ $dato->laboratorio ? $dato->laboratorio->nombre . ' ' : 'Sin
                    encargado' }}</td>
                @endif
            </tr>
            @endforeach
        </tbody>
    </table>

</body>

</html>