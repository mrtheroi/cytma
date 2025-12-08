<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Confirmación de Asistencia</title>
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
</head>
<body class="bg-gray-100 flex items-center justify-center h-screen">

    <div class="bg-white p-6 rounded shadow w-full max-w-md text-center">
        <h1 class="text-2xl font-bold mb-4">Registro de Asistencia</h1>

        @if(session('mensaje'))
            <div class="bg-green-100 text-green-800 p-3 rounded mb-4">
                {{ session('mensaje') }}
            </div>
        @endif

        <p class="mb-6 text-gray-700">Tu acción ha sido registrada correctamente.</p>

        <a href="{{ route('login') }}" 
            class="bg-blue-600 hover:bg-blue-700 text-white py-2 px-6 rounded inline-block">
            Hecho
        </a>
    </div>

</body>
</html>
