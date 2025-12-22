<?php

namespace App\Exports;

use Illuminate\Support\Collection;
use Illuminate\Database\Eloquent\Builder;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithCustomStartCell;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Events\AfterSheet;

class DieselLogsExport implements FromCollection, WithHeadings, WithCustomStartCell, ShouldAutoSize, WithEvents
{
    private string $company;
    private string $from;
    private string $to;

    public function __construct(
        private Builder $query,
        string $company,
        string $from,
        string $to
    ) {
        $this->company = $company;
        $this->from = $from;
        $this->to = $to;
    }

    public function startCell(): string
    {
        // headings arrancan en A4 (dejamos 3 filas arriba para título/empresa/periodo)
        return 'A4';
    }

    public function headings(): array
    {
        return [
            'Fecha',
            'Unidad',
            'Proveedor',
            'Equipo',
            'Empleado',
            'Horómetro',
            'Litros',
            'Notas',
            'Estado',
        ];
    }

    public function collection(): Collection
    {
        return $this->query->get()->map(function ($row) {
            return [
                optional($row->date)->format('Y-m-d'),
                $row->businessUnit->name ?? '',
                $row->supplier->name ?? '',
                $row->equipment->name ?? '',
                $row->empleado->nombre . '' . $row->empleado->epellido_paterno ?? '',
                $row->hour_meter !== null ? number_format((float)$row->hour_meter, 2, '.', '') : '',
                number_format((float)$row->liters, 2, '.', ''),
                $row->notes ?? '',
                is_null($row->deleted_at) ? 'Activo' : 'Inactivo',
            ];
        });
    }

    public function registerEvents(): array
    {
        return [
            AfterSheet::class => function (AfterSheet $event) {
                // Título / Empresa / Periodo en filas 1-3
                $event->sheet->setCellValue('A1', 'Bitácora de Diésel');
                $event->sheet->setCellValue('A2', $this->company);
                $event->sheet->setCellValue('A3', "Periodo: {$this->from} a {$this->to}");

                // Combinar celdas de A1 a I1 (9 columnas = A..I)
                $event->sheet->mergeCells('A1:I1');
                $event->sheet->mergeCells('A2:I2');
                $event->sheet->mergeCells('A3:I3');

                // Estilos
                $event->sheet->getStyle('A1')->getFont()->setBold(true)->setSize(14);
                $event->sheet->getStyle('A2')->getFont()->setBold(true)->setSize(12);
                $event->sheet->getStyle('A3')->getFont()->setSize(10);

                // Alinear
                $event->sheet->getStyle('A1:A3')->getAlignment()->setHorizontal('center');

                // Encabezados (fila 4) en negrita
                $event->sheet->getStyle('A4:I4')->getFont()->setBold(true);
            },
        ];
    }
}
