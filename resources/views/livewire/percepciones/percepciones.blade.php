<div class="space-y-6 px-4 sm:px-6 lg:px-8">

    {{-- Header --}}
    <div class="flex flex-col gap-3 sm:flex-row sm:items-center sm:justify-between">
        <div>
            <div class="flex items-center gap-2 text-sm text-gray-500 dark:text-gray-400">
                <span class="inline-flex items-center gap-1">
                    <svg class="h-4 w-4" viewBox="0 0 20 20" fill="currentColor">
                        <path d="M10.707 1.293a1 1 0 0 0-1.414 0l-7 7A1 1 0 0 0 3 10h1v7a1 1 0 0 0 1 1h4v-5a1 1 0 0 1 1-1h0a1 1 0 0 1 1 1v5h4a1 1 0 0 0 1-1v-7h1a1 1 0 0 0 .707-1.707l-7-7z"/>
                    </svg>
                    Inicio
                </span>
                <span>/</span>
                <span class="font-medium text-gray-900 dark:text-white">Percepciones</span>
            </div>

            <h2 class="mt-1 text-lg font-semibold text-gray-900 dark:text-white">
                Percepciones
            </h2>

            <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">
                Administra los montos por concepto de percepción para cada empleado.
            </p>
        </div>
    </div>

    {{-- Search --}}
    <div class="rounded-xl bg-white p-4 shadow-sm ring-1 ring-black/5 dark:bg-gray-900 dark:ring-white/10">
        <div class="grid grid-cols-1 gap-3 lg:grid-cols-12">
            <div class="lg:col-span-5">
                <label class="block text-xs font-medium text-gray-700 dark:text-gray-200">
                    Buscar
                </label>
                <div class="mt-1 relative">
                    <span class="pointer-events-none absolute inset-y-0 left-0 flex items-center pl-3 text-gray-400">
                        <svg class="h-4 w-4" viewBox="0 0 20 20" fill="currentColor">
                            <path fill-rule="evenodd"
                                  d="M8.5 3a5.5 5.5 0 1 0 3.53 9.72l2.62 2.62a1 1 0 0 0 1.42-1.41l-2.62-2.63A5.5 5.5 0 0 0 8.5 3Zm-3.5 5.5a3.5 3.5 0 1 1 7 0 3.5 3.5 0 0 1-7 0Z"
                                  clip-rule="evenodd"/>
                        </svg>
                    </span>

                    <input
                        wire:model.live.debounce.350ms="search"
                        type="text"
                        placeholder="Empleado, unidad o fecha…"
                        class="block w-full rounded-md border-gray-300 bg-white py-2 pl-9 pr-3 text-sm
                               text-gray-900 shadow-sm focus:border-indigo-500 focus:ring-indigo-500
                               dark:border-white/15 dark:bg-gray-900 dark:text-gray-100"
                    />
                </div>
            </div>

            <div class="lg:col-span-7 flex items-end justify-end">
                <div class="text-xs text-gray-500 dark:text-gray-400">
                    Resultado:
                    <span class="font-semibold text-gray-900 dark:text-white">
                        {{ \Illuminate\Support\Number::format($regs->total()) }}
                    </span>
                </div>
            </div>
        </div>
    </div>

    {{-- Table --}}
    <div class="relative overflow-hidden rounded-xl bg-white shadow-sm ring-1 ring-black/5
                dark:bg-gray-900 dark:ring-white/10">

        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-100 dark:divide-white/10">
                <thead class="bg-gray-50 dark:bg-gray-950/40">
                    <tr>
                        <th class="px-4 py-3 text-left text-xs font-semibold uppercase text-gray-600 dark:text-gray-300">
                            Empleado
                        </th>
                        <th class="px-4 py-3 text-left text-xs font-semibold uppercase text-gray-600 dark:text-gray-300">
                            Unidad
                        </th>
                        <th class="px-4 py-3 text-center text-xs font-semibold uppercase text-gray-600 dark:text-gray-300">
                            Período
                        </th>

                        @foreach ($conceptos as $con)
                            <th class="px-4 py-3 text-center text-xs font-semibold uppercase text-gray-600 dark:text-gray-300">
                                {{ $con->nombre }}
                            </th>
                        @endforeach

                        <th class="px-4 py-3 text-right text-xs font-semibold uppercase text-gray-600 dark:text-gray-300">
                            Acción
                        </th>
                    </tr>
                </thead>

                <tbody class="divide-y divide-gray-100 dark:divide-white/10">
                    @forelse($regs as $reg)
                        <tr class="hover:bg-gray-50/70 dark:hover:bg-white/5">

                            <td class="px-4 py-3 text-sm text-gray-900 dark:text-gray-100">
                                {{ $reg->empleado->nombre }}
                                {{ $reg->empleado->apellido_paterno }}
                            </td>

                            <td class="px-4 py-3 text-sm text-gray-700 dark:text-gray-200">
                                {{ $reg->empleado->unidad_negocio ?? '-' }}
                            </td>

                            <td class="px-4 py-3 text-center text-sm text-gray-700 dark:text-gray-200">
                                {{ $reg->periodoNomina->fecha_inicio->format('d/m/Y') }}
                                -
                                {{ $reg->periodoNomina->fecha_fin->format('d/m/Y') }}
                            </td>

                            @foreach ($conceptos as $con)
                                <td class="px-4 py-3 text-center">
                                    <input
                                        type="number"
                                        step="0.01"
                                        min="0"
                                        wire:model.defer="montos.{{ $reg->id }}.{{ $con->id }}"
                                        class="w-24 rounded-md border-gray-300 bg-white px-2 py-1 text-sm text-center
                                               focus:border-indigo-500 focus:ring-indigo-500
                                               dark:border-white/15 dark:bg-gray-900 dark:text-gray-100"
                                    />
                                </td>
                            @endforeach

                            <td class="px-4 py-3 text-right">
                                <button
                                    type="button"
                                    wire:click="guardarFila({{ $reg->id }})"
                                    class="inline-flex items-center rounded-md bg-emerald-600 px-4 py-1.5
                                        text-xs font-semibold text-white shadow-sm hover:bg-emerald-500
                                        focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2
                                        focus-visible:outline-emerald-600
                                        disabled:opacity-50 disabled:cursor-not-allowed"
                                    wire:loading.attr="disabled"
                                    wire:target="guardarFila"
                                >
                                    <svg
                                        wire:loading
                                        wire:target="guardarFila"
                                        class="mr-2 h-3.5 w-3.5 animate-spin text-white"
                                        viewBox="0 0 24 24" fill="none"
                                    >
                                        <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"/>
                                        <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8v4a4 4 0 0 0-4 4H4z"/>
                                    </svg>
                                    Guardar
                                </button>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="{{ 4 + $conceptos->count() }}"
                                class="px-4 py-10 text-center text-sm text-gray-500 dark:text-gray-400">
                                No hay registros de nómina activos.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        {{-- Loading --}}
        <div wire:loading wire:target="search, nextPage, previousPage"
             class="absolute inset-0 bg-white/60 dark:bg-gray-900/60"></div>

        <div wire:loading.flex wire:target="search, nextPage, previousPage"
             class="absolute inset-0 items-center justify-center">
            <svg class="h-10 w-10 animate-spin text-gray-400" viewBox="0 0 24 24" fill="none">
                <circle class="opacity-25" cx="12" cy="12" r="10"
                        stroke="currentColor" stroke-width="4"></circle>
                <path class="opacity-75" fill="currentColor"
                      d="M4 12a8 8 0 018-8v4a4 4 0 0 0-4 4H4z"/>
            </svg>
        </div>

        {{-- Pagination --}}
        <div class="border-t border-gray-100 px-4 py-3 dark:border-white/10">
            <div class="flex items-center justify-between">
                <div class="text-sm text-gray-600 dark:text-gray-300">
                    Resultado:
                    {{ \Illuminate\Support\Number::format($regs->total()) }}
                </div>
                {{ $regs->links('livewire.pagination.pagination') }}
            </div>
        </div>
    </div>
</div>