{{-- MODAL: Detalle del Periodo --}}
@if($openDetalle)
    <div class="fixed inset-0 z-50 flex items-center justify-center bg-black/40 backdrop-blur-sm">
        <div class="w-full max-w-6xl rounded-xl bg-white shadow-xl ring-1 ring-black/10
                    dark:bg-gray-900 dark:ring-white/10">

            {{-- Header --}}
            <div class="flex items-start justify-between border-b border-gray-100 px-6 py-4 dark:border-white/10">
                <div>
                    <h3 class="text-sm font-semibold text-gray-900 dark:text-white">
                        Detalle del periodo
                    </h3>
                    <p class="mt-1 text-xs text-gray-500 dark:text-gray-400">
                        {{ $periodo_detalle->fecha_inicio->format('d/m/Y') }} - {{ $periodo_detalle->fecha_fin->format('d/m/Y') }}
                    </p>
                    <p class="mt-1 text-xs text-gray-500 dark:text-gray-400">
                        Unidad: {{ $periodo_detalle->unidad_negocio }}
                    </p>
                </div>

                <button
                    type="button"
                    wire:click="closeModal"
                    class="rounded-md p-1 text-gray-400 hover:bg-gray-100 hover:text-gray-600
                           dark:hover:bg-gray-800 dark:hover:text-gray-300"
                >
                    <span class="sr-only">Cerrar</span>
                    <svg class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor">
                        <path d="M6.28 5.22a.75.75 0 0 0-1.06 1.06L8.94 10l-3.72 3.72a.75.75 0 1 0 1.06 1.06L10 11.06l3.72 3.72a.75.75 0 1 0 1.06-1.06L11.06 10l3.72-3.72a.75.75 0 0 0-1.06-1.06L10 8.94 6.28 5.22Z"/>
                    </svg>
                </button>
            </div>

            {{-- Contenido --}}
            <div class="px-6 py-5 space-y-4">

                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-200 bg-white shadow rounded-lg">
                        <thead class="bg-gray-50">
                            <tr>
                                <th class="px-4 py-2 text-center text-xs font-medium text-gray-500 uppercase">Empleado</th>
                                <th class="px-4 py-2 text-center text-xs font-medium text-gray-500 uppercase">Sueldo</th>
                                <th class="px-4 py-2 text-center text-xs font-medium text-gray-500 uppercase">Percepciones</th>
                                <th class="px-4 py-2 text-center text-xs font-medium text-gray-500 uppercase">Deducciones</th>
                                <th class="px-4 py-2 text-center text-xs font-medium text-gray-500 uppercase">Neto</th>
                            </tr>
                        </thead>

                        <tbody class="divide-y divide-gray-100">
                            @forelse($periodo_detalle->registros as $registro)
                                <tr class="hover:bg-gray-50 dark:hover:bg-gray-800">
                                    <td class="px-4 py-2 text-sm text-gray-700 text-center dark:text-gray-200">
                                        {{ $registro->empleado->nombre }} {{ $registro->empleado->apellido_paterno }}
                                    </td>
                                    <td class="px-4 py-2 text-sm text-gray-700 text-center dark:text-gray-200">
                                        {{ number_format($registro->sueldo_pactado, 2) }}
                                    </td>
                                    <td class="px-4 py-2 text-sm text-gray-700 text-center dark:text-gray-200">
                                        {{ number_format($registro->percepciones_totales, 2) }}
                                    </td>
                                    <td class="px-4 py-2 text-sm text-gray-700 text-center dark:text-gray-200">
                                        {{ number_format($registro->deducciones_totales, 2) }}
                                    </td>
                                    <td class="px-4 py-2 text-sm text-gray-700 text-center dark:text-gray-200">
                                        {{ number_format($registro->neto, 2) }}
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="5" class="px-4 py-4 text-center text-gray-400 dark:text-gray-500">
                                        No hay registros
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                {{-- Footer --}}
                <div class="flex items-center justify-end gap-3 pt-2 border-t border-gray-100 dark:border-white/10">
                    {{-- <button
                        type="button"
                        wire:click="generarReporte({{ $periodo_detalle->id }})"
                        class="inline-flex items-center rounded-md bg-emerald-600 px-4 py-1.5
                               text-xs font-semibold text-white shadow-sm hover:bg-emerald-500
                               focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2
                               focus-visible:outline-emerald-600"
                    >
                        Generar reporte
                    </button> --}}

                    <button
                        type="button"
                        wire:click="generarReporte({{ $periodo_detalle->id }})"
                        class="inline-flex items-center rounded-md bg-emerald-600 px-4 py-1.5
                            text-xs font-semibold text-white shadow-sm hover:bg-emerald-500
                            focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2
                            focus-visible:outline-emerald-600
                            disabled:opacity-50 disabled:cursor-not-allowed"
                        wire:loading.attr="disabled"
                        wire:target="generarReporte"
                    >
                        <svg
                            wire:loading
                            wire:target="generarReporte"
                            class="mr-2 h-3.5 w-3.5 animate-spin text-white"
                            viewBox="0 0 24 24" fill="none"
                        >
                            <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"/>
                            <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8v4a4 4 0 0 0-4 4H4z"/>
                        </svg>
                        Generar reporte
                    </button>

                    <button
                        type="button"
                        wire:click="closeModal"
                        class="rounded-md px-3 py-1.5 text-xs font-medium text-gray-600
                               hover:bg-gray-100
                               dark:text-gray-300 dark:hover:bg-gray-800"
                    >
                        Cerrar
                    </button>
                </div>

            </div>
        </div>
    </div>
@endif