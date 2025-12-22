{{-- MODAL: Crear / Editar Periodo --}}
@if($open)
    <div class="fixed inset-0 z-50 flex items-center justify-center bg-black/40 backdrop-blur-sm">
        <div class="w-full max-w-xl rounded-xl bg-white shadow-xl ring-1 ring-black/10
                    dark:bg-gray-900 dark:ring-white/10">

            {{-- Header --}}
            <div class="flex items-start justify-between border-b border-gray-100 px-6 py-4 dark:border-white/10">
                <div>
                    <h3 class="text-sm font-semibold text-gray-900 dark:text-white">
                        {{ $editingId ? 'Editar periodo' : 'Crear nuevo periodo' }}
                    </h3>
                    <p class="mt-1 text-xs text-gray-500 dark:text-gray-400">
                        {{ $editingId
                            ? 'Actualiza la información del periodo.'
                            : 'El sistema creará un periodo semanal automáticamente.'
                        }}
                    </p>

                    @if(!$editingId && $fecha_inicio_crear && $fecha_fin_crear)
                        <p class="mt-2 text-xs text-gray-500 dark:text-gray-400">
                            Se creará el periodo semanal del
                            <span class="font-medium text-gray-900 dark:text-white">
                                {{ $fecha_inicio_crear->format('d/m/Y') }}
                            </span>
                            al
                            <span class="font-medium text-gray-900 dark:text-white">
                                {{ $fecha_fin_crear->format('d/m/Y') }}
                            </span>
                        </p>
                    @endif
                </div>

                <button
                    type="button"
                    wire:click="closeModal"
                    class="rounded-md p-1 text-gray-400 hover:bg-gray-100 hover:text-gray-600 dark:hover:bg-gray-800"
                >
                    <span class="sr-only">Cerrar</span>
                    <svg class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor" aria-hidden="true">
                        <path d="M6.28 5.22a.75.75 0 0 0-1.06 1.06L8.94 10l-3.72 3.72a.75.75 0 1 0 1.06 1.06L10 11.06l3.72 3.72a.75.75 0 1 0 1.06-1.06L11.06 10l3.72-3.72a.75.75 0 0 0-1.06-1.06L10 8.94 6.28 5.22Z" />
                    </svg>
                </button>
            </div>

            {{-- Form --}}
            <form wire:submit.prevent="crearPeriodo" class="space-y-5 px-6 py-5">

                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-200">
                            Unidad de negocio
                        </label>
                        <select
                            wire:model.defer="unidad_negocio"
                            class="mt-1 block w-full rounded-md border-gray-300 bg-white py-2 px-3
                                   text-sm text-gray-900 shadow-sm
                                   focus:border-indigo-500 focus:ring-indigo-500
                                   dark:border-white/15 dark:bg-gray-900 dark:text-gray-100"
                        >
                            <option value="">Selecciona una unidad</option>
                            <option>KM9 - Campeche</option>
                            <option>Chocholá</option>
                        </select>
                        @error('unidad_negocio')
                            <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
                        @enderror
                    </div>
                </div>

                {{-- Footer --}}
                <div class="flex items-center justify-end gap-3 pt-2 border-t border-gray-100 dark:border-white/10">
                    <button
                        type="button"
                        wire:click="closeModal"
                        class="rounded-md px-3 py-1.5 text-xs font-medium text-gray-600 hover:bg-gray-100 dark:text-gray-300 dark:hover:bg-gray-800"
                    >
                        Cancelar
                    </button>

                    <button
                        type="submit"
                        class="inline-flex items-center rounded-md bg-emerald-600 px-4 py-1.5
                               text-xs font-semibold text-white shadow-sm
                               hover:bg-emerald-500
                               focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2
                               focus-visible:outline-emerald-600
                               disabled:opacity-50 disabled:cursor-not-allowed"
                        wire:loading.attr="disabled"
                        wire:target="crearPeriodo"
                    >
                        <svg
                            wire:loading
                            wire:target="crearPeriodo"
                            class="mr-2 h-3.5 w-3.5 animate-spin text-white"
                            viewBox="0 0 24 24" fill="none">
                            <circle class="opacity-25" cx="12" cy="12" r="10"
                                    stroke="currentColor" stroke-width="4"/>
                            <path class="opacity-75" fill="currentColor"
                                  d="M4 12a8 8 0 018-8v4a4 4 0 0 0-4 4H4z"/>
                        </svg>
                        {{ $editingId ? 'Actualizar periodo' : 'Crear periodo' }}
                    </button>
                </div>

            </form>
        </div>
    </div>
@endif
