<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Reporte de Áreas</title>
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
            top: 15px;
            left: 0;
            right: 0;
            text-align: center;
            margin: 0;
            padding: 0;
        }

        /* Estilo para el número de página */
        .page-number {
            position: fixed;
            bottom: 0;
            right: 0;
            margin-right: 20px;
            font-size: 12px;
            color: #1B396A;
        }
    </style>
</head>

<body>

    <!-- Logos en las esquinas -->
    <img src="{{ public_path('images/logo-left.jpg') }}" class="logo-left" alt="Logo Izquierdo">
    <img src="{{ public_path('images/logo-right.jpg') }}" class="logo-right" alt="Logo Derecho">

    <!-- Titulo -->
    <h1 class="title" style="text-align: center;">Reporte de Áreas</h1>

    <!-- Contenido de la tabla -->
    <table>
        <thead class="bg-blue-tec text-white">
            <tr>
                <th>ID</th>
                <th>Nombre</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($datos as $dato)
            <tr>
                <td>{{ $dato->id }}</td>
                <td>{{ $dato->nombre }}</td>
            </tr>
            @endforeach
        </tbody>
    </table>

</body>

</html>