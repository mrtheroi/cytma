<?php

namespace App\Livewire\Asistencias;

use App\Models\AsistenciaNomina;
use App\Models\PeriodoNomina;
use App\Models\RegistroNomina;
use Livewire\Component;
use Jantinnerezo\LivewireAlert\Facades\LivewireAlert;


class Asistencias extends Component
{
    public $registros;
    public $dias = [];
    public $horasExtras = [];

    // Filtros
    public $search = '';
    public $periodoId = null;
    public $unidadNegocio = null;

    public function mount()
    {
        $periodos = PeriodoNomina::orderBy('fecha_inicio', 'desc')->pluck('id');

        $this->registros = RegistroNomina::with(['empleado', 'asistencias'])
            ->whereIn('periodo_nomina_id', $periodos)
            ->get();

        foreach ($this->registros as $reg) {
            // Obtener asistencia existente
            $asistencia = $reg->asistencias()->first();

            // Crear solo si no existe
            if (!$asistencia) {
                $asistencia = AsistenciaNomina::create([
                    'registro_nomina_id' => $reg->id
                ]);
            }

            // Guardar en la propiedad del registro para usar después
            $reg->asistencia = $asistencia;

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
    }

    public function getRegistrosFiltradosProperty()
    {
        $registros = $this->registros;

        if ($this->search) {
            $search = strtolower($this->search);

            $registros = $registros->filter(function ($reg) use ($search) {
                $nombre = strtolower(
                    $reg->empleado->nombre . ' ' .
                    $reg->empleado->apellido_paterno . ' ' .
                    $reg->empleado->apellido_materno
                );

                return str_contains($nombre, $search);
            });
        }

        return $registros;
    }

    protected function applySearch($query)
    {
        return $this->search === ''
            ? $query
            : $query
                ->where(function ($q) {
                    $q->where('name', 'like', '%' . $this->search . '%')
                      ->orWhere('email', 'like', '%' . $this->search . '%');
                });
    }

    public function guardarFila($registroId)
    {
        $asistencia = AsistenciaNomina::where('registro_nomina_id', $registroId)->firstOrFail();

        $asistencia->update([
            'lunes' => $this->dias[$registroId]['lunes'],
            'horas_extra_lunes' => $this->horasExtras[$registroId]['lunes'] ?? 0,

            'martes' => $this->dias[$registroId]['martes'],
            'horas_extra_martes' => $this->horasExtras[$registroId]['martes'] ?? 0,

            'miercoles' => $this->dias[$registroId]['miercoles'],
            'horas_extra_miercoles' => $this->horasExtras[$registroId]['miercoles'] ?? 0,

            'jueves' => $this->dias[$registroId]['jueves'],
            'horas_extra_jueves' => $this->horasExtras[$registroId]['jueves'] ?? 0,

            'viernes' => $this->dias[$registroId]['viernes'],
            'horas_extra_viernes' => $this->horasExtras[$registroId]['viernes'] ?? 0,

            'sabado' => $this->dias[$registroId]['sabado'],
            'horas_extra_sabado' => $this->horasExtras[$registroId]['sabado'] ?? 0,

            'domingo' => $this->dias[$registroId]['domingo'],
            'horas_extra_domingo' => $this->horasExtras[$registroId]['domingo'] ?? 0,
        ]);
        $asistencia->registroNomina->calcularNomina();
        LivewireAlert::title('¡Éxito!')
            ->text('Cambios guardados correctamente.')
            ->success()
            ->toast()
            ->position('top-end')
            ->show();
    }

    public function render()
    {
        return view('livewire.asistencias.asistencias', [
            'regs' => $this->registrosFiltrados,
        ]);
    }
}
