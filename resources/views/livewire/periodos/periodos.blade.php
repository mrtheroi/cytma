<div>
    <div class="px-4 sm:px-6 lg:px-8">
        <div class="sm:flex sm:items-center">
            <div class="sm:flex-auto">
                <div class="sm:flex sm:items-center">
                    <flux:breadcrumbs>
                        <flux:breadcrumbs.item href="#" icon="home" />
                        <flux:breadcrumbs.item>Periodos</flux:breadcrumbs.item>
                    </flux:breadcrumbs>
                </div>
            </div>
            <flux:button wire:click="modalCrearPeriodo">Nuevo periodo</flux:button>
        </div>
        <flux:input class="mt-4" wire:model.live="search" placeholder="Search..." icon="magnifying-glass" kbd="⌘K" />
    </div>

        <div class="mt-8 relative">
        <table class="min-w-full divide-y divide-gray-200 bg-white shadow rounded-lg">
            <thead class="bg-gray-50">
                <tr>
                    <th class="px-4 py-2 text-center text-xs font-medium text-gray-500 uppercase">Fecha inicio</th>
                    <th class="px-4 py-2 text-center text-xs font-medium text-gray-500 uppercase">Fecha final</th>
                    <th class="px-4 py-2 text-center text-xs font-medium text-gray-500 uppercase">Unidad de negocio</th>
                    <th class="px-4 py-2 text-center text-xs font-medium text-gray-500 uppercase">Acción</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-100">
                @forelse($periodos as $periodo)
                    <tr>
                        <td class="px-4 py-2 text-sm text-gray-700 text-center">{{ $periodo->fecha_inicio }}</td>
                        <td class="px-4 py-2 text-sm text-gray-700 text-center">{{ $periodo->fecha_fin }}</td>
                        <td class="px-4 py-2 text-sm text-gray-700 text-center">{{ $periodo->unidad_negocio }}</td>

                        <td class="px-4 py-2 text-sm text-center">
                            {{-- <flux:button variant="primary" wire:click="edit({{ $periodo->id }})" size="sm">Editar</flux:button> --}}
                            <flux:button variant="primary" wire:click="verDetalles({{ $periodo->id }})" size="sm" class="ml-2">
                                Detalles
                            </flux:button>

                            <flux:button variant="danger" wire:click="confirmDelete({{ $periodo->id }})" size="sm" class="ml-2">Eliminar</flux:button>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="3" class="px-4 py-4 text-center text-gray-400">No hay registros</td>
                    </tr>
                @endforelse
                <div wire:loading wire:target="search, nextPage, previousPage"
                    class="absolute inset-0 bg-white opacity-50">
                </div>

                <div wire:loading.flex wire:target="search, nextPage, previousPage"
                    class="flex justify-center items-center absolute inset-0">
                    <x-icon.spinner size="10" class="text-gray-400" />
                </div>
            </tbody>
        </table>

        <div class="pt-4 flex justify-between items-center">
            <div class="text-gray-700 text-sm">
                Resultado: {{ \Illuminate\Support\Number::format($periodos->total()) }}
            </div>
            {{ $periodos->links('livewire.pagination.pagination') }}
        </div>
    </div>

    <flux:modal name="nuevo-periodo" class="w-[600px]">
        <form wire:submit.prevent="crearPeriodo" class="space-y-6">

            <div>
                <flux:heading size="lg">
                    {{ $editingId ? 'Editar periodo' : 'Crear nuevo periodo' }}
                </flux:heading>

                <flux:text class="mt-2">
                    {{ $editingId ? 'Actualiza la información del periodo.' : 'El sistema creará un periodo semanal automáticamente.' }}
                </flux:text>

                <flux:text class="mt-2">
                    @if(!$editingId && $fecha_inicio_crear && $fecha_fin_crear)
                        Se creará el periodo semanal del {{ $fecha_inicio_crear->format('d/m/Y') }} al {{ $fecha_fin_crear->format('d/m/Y') }}
                    @else
                        {{ $editingId ? 'Actualiza la información del periodo.' : 'El sistema creará un periodo semanal automáticamente.' }}
                    @endif
                </flux:text>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <flux:select wire:model="unidad_negocio" label="Unidad de negocio" placeholder="Selecciona una ubicación">
                    <flux:select.option>KM9 - Campeche</flux:select.option>
                    <flux:select.option>Chocholá</flux:select.option>
                </flux:select>
            </div>

            <div class="flex justify-end gap-2 mt-4">
                <flux:modal.close>
                    <flux:button variant="ghost">Cancelar</flux:button>
                </flux:modal.close>
                <flux:button variant="primary" type="submit">
                    {{ $editingId ? 'Actualizar' : 'Guardar' }}
                </flux:button>
            </div>
        </form>
    </flux:modal>


    <flux:modal name="detalle-periodo" class="w-[800px]">
        @if($periodo_detalle)
            <flux:heading size="lg">Detalle del periodo</flux:heading>

            <flux:text>
                {{ $periodo_detalle->fecha_inicio->format('d/m/Y') }} - {{ $periodo_detalle->fecha_fin->format('d/m/Y') }}
            </flux:text>
            <flux:text>Unidad: {{ $periodo_detalle->unidad_negocio }}</flux:text>

            <table class="min-w-full mt-4 divide-y divide-gray-200">
                <thead>
                    <tr>
                        <th>Empleado</th>
                        <th>Sueldo</th>
                        <th>Percepciones</th>
                        <th>Deducciones</th>
                        <th>Neto</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($periodo_detalle->registros as $registro)
                        <tr>
                            <td>{{ $registro->empleado->nombre }} {{ $registro->empleado->apellido_paterno }}</td>
                            <td>{{ number_format($registro->sueldo_pactado, 2) }}</td>
                            <td>{{ number_format($registro->percepciones_totales, 2) }}</td>
                            <td>{{ number_format($registro->deducciones_totales, 2) }}</td>
                            <td>{{ number_format($registro->neto, 2) }}</td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="px-4 py-4 text-center text-gray-400">
                                No hay registros
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>

            <div class="flex justify-end mt-4">
                <flux:button variant="primary" wire:click="generarReporte({{ $periodo_detalle->id }})">Generar reporte</flux:button>
                <flux:modal.close>
                    <flux:button variant="ghost">Cerrar</flux:button>
                </flux:modal.close>
            </div>
        @endif
    </flux:modal>
</div>