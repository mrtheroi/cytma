<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Reporte Nómina {{ $periodo->fecha_inicio->format('d/m/Y') }} - {{ $periodo->fecha_fin->format('d/m/Y') }}</title>
    <style>
        body { font-family: Arial, sans-serif; font-size: 12px; }
        table { width: 100%; border-collapse: collapse; margin-top: 20px; }
        th, td { border: 1px solid #ddd; padding: 6px; }
        th { background-color: #f4f4f4; font-weight: bold; text-align: center; }
        td { text-align: center; }
        .text-left { text-align: left; }
        .header { font-weight: bold; font-size: 14px; margin-bottom: 10px; }
    </style>
</head>
<body>
    <div class="header">
        <div><strong>Periodo:</strong> {{ $periodo->fecha_inicio->format('d/m/Y') }} - {{ $periodo->fecha_fin->format('d/m/Y') }}</div>
        <div><strong>Unidad de negocio:</strong> {{ $periodo->unidad_negocio ?? 'N/A' }}</div>
    </div>

    <table>
        <thead>
            <tr>
                <th>Empleado</th>
                <th>Sueldo pactado</th>
                <th>Turno</th>
                <th>Categoría</th>
                <th>Días trabajados</th>
                <th>Horas extras</th>
                <th>Bonos</th>
                <th>Descuentos</th>
                <th>Neto</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($datosReporte as $registro)
                <tr>
                    <td class="text-left">{{ $registro['empleado'] }}</td>
                    <td>${{ number_format($registro['pactado'], 2) }}</td>
                    <td>{{ $registro['turno'] }}</td>
                    <td>{{ $registro['categoria'] }}</td>
                    <td>{{ $registro['dias_trabajados'] }}</td>
                    <td>{{ $registro['horas_extras'] }}</td>
                    <td>${{ number_format($registro['bonos'], 2) }}</td>
                    <td>${{ number_format($registro['descuentos'], 2) }}</td>
                    <td>${{ number_format($registro['neto'], 2) }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>
</body>
</html>