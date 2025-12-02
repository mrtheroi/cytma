{{-- <!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <style>
        body { font-family: sans-serif; font-size: 12px; }
        table { width: 100%; border-collapse: collapse; margin-top: 15px; }
        th, td { border: 1px solid #555; padding: 4px; text-align: center; }
        th { background: #efefef; }
        .small { font-size: 10px; }
    </style>
</head>
<body>

<h2>Reporte de Nómina</h2>
<p>
    <strong>Periodo:</strong>
    {{ $periodo->fecha_inicio->format('d/m/Y') }} -
    {{ $periodo->fecha_fin->format('d/m/Y') }} <br>
    <strong>Unidad:</strong> {{ $periodo->unidad_negocio }}
</p>

<table>
    <thead>
        <tr>
            <th rowspan="2">Empleado</th>
            <th rowspan="2">Sueldo</th>

            <th colspan="7">Días (Asistencia)</th>
            <th colspan="7">Horas Extra</th>

            <th rowspan="2">Total Días</th>
            <th rowspan="2">Total Hrs</th>
            <th rowspan="2">Percepciones</th>
            <th rowspan="2">Deducciones</th>
            <th rowspan="2">Neto</th>
        </tr>

        <tr class="small">
            <th>L</th><th>M</th><th>M</th><th>J</th><th>V</th><th>S</th><th>D</th>
            <th>L</th><th>M</th><th>M</th><th>J</th><th>V</th><th>S</th><th>D</th>
        </tr>
    </thead>

    <tbody>
        @foreach ($datosReporte as $r)
        <tr>
            <td>{{ $r['empleado'] }}</td>
            <td>{{ number_format($r['pactado'], 2) }}</td>

            @foreach ($r['dias'] as $d)
                <td>{{ $d ? '✔' : '' }}</td>
            @endforeach

            @foreach ($r['horas'] as $h)
                <td>{{ $h }}</td>
            @endforeach

            <td>{{ $r['total_dias'] }}</td>
            <td>{{ $r['total_horas'] }}</td>
            <td>{{ number_format($r['bonos'], 2) }}</td>
            <td>{{ number_format($r['descuentos'], 2) }}</td>
            <td>{{ number_format($r['neto'], 2) }}</td>
        </tr>
        @endforeach
    </tbody>
</table>

</body>
</html> --}}

<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <style>
        body { font-family: sans-serif; font-size: 11px; }
        table { width: 100%; border-collapse: collapse; margin-top: 15px; }
        th, td { border: 1px solid #555; padding: 3px; text-align: center; }
        th { background: #efefef; }
        .small { font-size: 9px; }
    </style>
</head>
<body>

<h2>Reporte de Nómina</h2>

<p>
    <strong>Periodo:</strong>
    {{ $periodo->fecha_inicio->format('d/m/Y') }} -
    {{ $periodo->fecha_fin->format('d/m/Y') }} <br>
    <strong>Unidad:</strong> {{ $periodo->unidad_negocio }}
</p>

<table>
    <thead>
        <tr>
            <th rowspan="2">Empleado</th>
            <th rowspan="2">Turno</th>
            <th rowspan="2">Categoría</th>
            <th rowspan="2">Pactado</th>

            <th colspan="7">Asistencias</th>
            <th colspan="7">Horas Extras</th>

            <th rowspan="2">Total Días</th>
            <th rowspan="2">Total Hrs</th>

            <th rowspan="2">HE a pagar</th>

            <th rowspan="2">Día + Hr Extra</th>

            <th rowspan="2">Costo Día</th>
            <th rowspan="2">Costo Hora</th>

            <th rowspan="2">Comidas</th>
            <th rowspan="2">Compensación</th>
            <th rowspan="2">Apoyo pasajes y estimulo</th>

            <th rowspan="2">Anticipo</th>
            <th rowspan="2">Por pagar</th>

            <th rowspan="2">Percepciones</th>
            <th rowspan="2">Deducciones</th>
            <th rowspan="2">Saldo</th>
        </tr>

        <tr class="small">
            <th>L</th><th>M</th><th>M</th><th>J</th><th>V</th><th>S</th><th>D</th>
            <th>L</th><th>M</th><th>M</th><th>J</th><th>V</th><th>S</th><th>D</th>
        </tr>
    </thead>

    <tbody>
        @foreach ($datosReporte as $r)
        <tr>
            <td>{{ $r['empleado'] }}</td>
            <td>{{ $r['turno'] }}</td>
            <td>{{ $r['categoria'] }}</td>
            <td>${{ number_format($r['pactado'], 2) }}</td>

            {{-- Asistencias --}}
            @foreach ($r['dias'] as $d)
                <td>{{ $d ? '1' : '' }}</td>
            @endforeach

            {{-- Horas extra --}}
            @foreach ($r['horas'] as $h)
                <td>{{ $h }}</td>
            @endforeach

            <td>{{ $r['total_dias'] }}</td>
            <td>{{ $r['total_horas'] }}</td>

            <td>${{ number_format($r['horas_extra_pagar'], 2) }}</td>

            <td>${{ number_format($r['dia_hr_extra'], 2) }}</td>

            <td>${{ number_format($r['costo_dia'], 2) }}</td>
            <td>${{ number_format($r['costo_hora'], 2) }}</td>

            {{-- Conceptos (comidas, compensación, apoyo) --}}
            <td>${{ number_format($r['comidas'] ?? 0, 2) }}</td>
            <td>${{ number_format($r['compensacion'] ?? 0, 2) }}</td>
            <td>${{ number_format($r['apoyo'] ?? 0, 2) }}</td>

            {{-- Deducciones específicas --}}
            <td>${{ number_format($r['anticipo'] ?? 0, 2) }}</td>
            <td>${{ number_format($r['por_pagar'] ?? 0, 2) }}</td>

            {{-- <td>${{ number_format($r['bonos'], 2) }}</td>
            <td>${{ number_format($r['descuentos'], 2) }}</td>
            <td>${{ number_format($r['neto'], 2) }}</td> --}}
            <td>${{ number_format($r['percepciones'], 2) }}</td>
            <td>${{ number_format($r['deducciones'], 2) }}</td>
            <td>${{ number_format($r['neto'], 2) }}</td>
        </tr>
        @endforeach
    </tbody>
</table>

</body>
</html>
