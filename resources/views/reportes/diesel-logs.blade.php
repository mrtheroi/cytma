<!doctype html>
<html lang="es">
<head>
    <meta charset="utf-8">
    <title>Bitácora de Diésel</title>
    <style>
        body { font-family: DejaVu Sans, sans-serif; font-size: 11px; color: #111; }
        .muted { color: #666; font-size: 10px; }
        table { width: 100%; border-collapse: collapse; margin-top: 10px; }
        th, td { border: 1px solid #ddd; padding: 6px; vertical-align: top; }
        th { background: #f3f4f6; text-align: left; }
        .right { text-align: right; }
    </style>
</head>
<body>
<h2 style="margin:0;">Bitácora de Diésel</h2>
<p class="muted" style="margin:4px 0 0 0; font-weight:600;">
    CONCRETOS Y TRITURADOS MONTES AZULES SA DE CV
</p>

<div class="muted" style="margin-top:6px;">
    Periodo: {{ $from }} a {{ $to }} |
    Total litros: {{ number_format($totalLiters, 2) }} |
    Registros: {{ $totalRows }}
</div>

<table>
    <thead>
    <tr>
        <th>Fecha</th>
        <th>Unidad</th>
        <th>Proveedor</th>
        <th>Equipo</th>
        <th>Empleado</th>
        <th class="right">Horómetro</th>
        <th class="right">Litros</th>
        <th>Notas</th>
        <th>Estado</th>
    </tr>
    </thead>
    <tbody>
    @foreach($rows as $r)
        <tr>
            <td>{{ optional($r->date)->format('Y-m-d') }}</td>
            <td>{{ $r->businessUnit->name ?? '' }}</td>
            <td>{{ $r->supplier->name ?? '' }}</td>
            <td>{{ $r->equipment->name ?? '' }}</td>
            <td>{{ $r->empleado->nombre . '' . $r->empleado->apellido_paterno ?? '' }}</td>
            <td class="right">{{ $r->hour_meter !== null ? number_format($r->hour_meter, 2) : '' }}</td>
            <td class="right">{{ number_format($r->liters, 2) }}</td>
            <td>{{ $r->notes ?? '' }}</td>
            <td>{{ is_null($r->deleted_at) ? 'Activo' : 'Inactivo' }}</td>
        </tr>
    @endforeach
    </tbody>
</table>
</body>
</html>
