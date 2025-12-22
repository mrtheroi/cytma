<?php

namespace App\Livewire\Asistencias;

use App\Models\RegistroNomina;
use Carbon\Carbon;
use Livewire\Component;
use Livewire\WithPagination;

class Asistencias extends Component
{
    use WithPagination;

    public $search = '';
    public $dias = [];
    public $horasExtras = [];

    public function updatedSearch()
    {
        $this->resetPage();
    }

    public function render()
    {
        $search = $this->search;

        if (preg_match('/\d{2}\/\d{2}\/\d{4}/', $search)) {
            $search = Carbon::createFromFormat('d/m/Y', $search)->format('Y-m-d');
        }

        $regs = RegistroNomina::with([
                'empleado',
                'asistencias',
                'periodoNomina'
            ])
            ->when($search, function ($q) use ($search) {

                $q->where(function ($query) use ($search) {

                    // 🔹 Empleado (nombre y apellidos)
                    $query->whereHas('empleado', function ($e) use ($search) {
                        $e->where('nombre', 'like', "%{$search}%")
                        ->orWhere('apellido_paterno', 'like', "%{$search}%")
                        ->orWhere('apellido_materno', 'like', "%{$search}%")
                        ->orWhere('unidad_negocio', 'like', "%{$search}%");
                    });

                    // 🔹 Periodo (fechas)
                    $query->orWhereHas('periodoNomina', function ($p) use ($search) {
                        $p->whereDate('fecha_inicio', 'like', "%{$search}%")
                        ->orWhereDate('fecha_fin', 'like', "%{$search}%");
                    });
                });
            })
            ->orderByDesc('id')
            ->paginate(10);

        // Inicializar arrays SOLO para la página actual
        foreach ($regs as $reg) {
            $asistencia = $reg->asistencias()->firstOrCreate([]);

            $this->dias[$reg->id] = [
                'lunes' => (bool) $asistencia->lunes,
                'martes' => (bool) $asistencia->martes,
                'miercoles' => (bool) $asistencia->miercoles,
                'jueves' => (bool) $asistencia->jueves,
                'viernes' => (bool) $asistencia->viernes,
                'sabado' => (bool) $asistencia->sabado,
                'domingo' => (bool) $asistencia->domingo,
            ];

            $this->horasExtras[$reg->id] = [
                'lunes' => $asistencia->horas_extra_lunes,
                'martes' => $asistencia->horas_extra_martes,
                'miercoles' => $asistencia->horas_extra_miercoles,
                'jueves' => $asistencia->horas_extra_jueves,
                'viernes' => $asistencia->horas_extra_viernes,
                'sabado' => $asistencia->horas_extra_sabado,
                'domingo' => $asistencia->horas_extra_domingo,
            ];
        }

        return view('livewire.asistencias.asistencias', compact('regs'));
    }
}
