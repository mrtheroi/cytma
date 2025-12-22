{{-- MODAL: crear / editar carga de diésel (mismo estilo que tu modal) --}}
@if($open)
    <div class="fixed inset-0 z-50 flex items-center justify-center bg-black/40 backdrop-blur-sm">
        <div class="w-full max-w-3xl rounded-xl bg-white shadow-xl ring-1 ring-black/10 dark:bg-gray-900 dark:ring-white/10">
            {{-- Header modal --}}
            <div class="flex items-start justify-between border-b border-gray-100 px-6 py-4 dark:border-white/10">
                <div>
                    <h3 class="text-sm font-semibold text-gray-900 dark:text-white">
                        {{ $dieselLoadsId ? 'Editar carga de diésel' : 'Nueva carga de diésel' }}
                    </h3>
                    <p class="mt-1 text-xs text-gray-500 dark:text-gray-400">
                        Registra la carga de diésel en la bitácora.
                    </p>
                </div>

                <button
                    type="button"
                    wire:click="closeModal"
                    class="rounded-md p-1 text-gray-400 hover:bg-gray-100 hover:text-gray-600 dark:hover:bg-gray-800"
                >
                    <span class="sr-only">Cerrar</span>
                    <svg class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor" aria-hidden="true">
                        <path
                            d="M6.28 5.22a.75.75 0 0 0-1.06 1.06L8.94 10l-3.72 3.72a.75.75 0 1 0 1.06 1.06L10 11.06l3.72 3.72a.75.75 0 1 0 1.06-1.06L11.06 10l3.72-3.72a.75.75 0 0 0-1.06-1.06L10 8.94 6.28 5.22Z" />
                    </svg>
                </button>
            </div>

            {{-- Formulario --}}
            <form wire:submit.prevent="save" class="space-y-5 px-6 py-5">
                <div class="grid grid-cols-1 gap-4 md:grid-cols-2">
                    {{-- Fecha --}}
                    <div>
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-200">Fecha</label>
                        <div class="mt-1">
                            <input
                                type="date"
                                wire:model.defer="date"
                                class="block w-full rounded-md border-gray-300 bg-white py-2 px-3 text-sm text-gray-900 shadow-sm
                                           focus:border-indigo-500 focus:ring-indigo-500
                                           dark:border-white/15 dark:bg-gray-900 dark:text-gray-100"
                            >
                        </div>
                        @error('date')
                        <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    {{-- Unidad --}}
                    <div>
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-200">Unidad de negocio</label>
                        <div class="mt-1">
                            <select
                                wire:model.defer="business_unit_id"
                                class="block w-full rounded-md border-gray-300 bg-white py-2 px-3 text-sm text-gray-900 shadow-sm
                                           focus:border-indigo-500 focus:ring-indigo-500
                                           dark:border-white/15 dark:bg-gray-900 dark:text-gray-100"
                            >
                                <option value="">Selecciona</option>
                                @foreach($businessUnits as $bu)
                                    <option value="{{ $bu->id }}">{{ $bu->name }}</option>
                                @endforeach
                            </select>
                        </div>
                        @error('business_unit_id')
                        <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    {{-- Proveedor --}}
                    <div>
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-200">Proveedor</label>
                        <div class="mt-1">
                            <select
                                wire:model.defer="supplier_id"
                                class="block w-full rounded-md border-gray-300 bg-white py-2 px-3 text-sm text-gray-900 shadow-sm
                                           focus:border-indigo-500 focus:ring-indigo-500
                                           dark:border-white/15 dark:bg-gray-900 dark:text-gray-100"
                            >
                                <option value="">Selecciona</option>
                                @foreach($suppliers as $sp)
                                    <option value="{{ $sp->id }}">{{ $sp->name }}</option>
                                @endforeach
                            </select>
                        </div>
                        @error('supplier_id')
                        <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    {{-- Equipo --}}
                    <div>
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-200">Equipo</label>
                        <div class="mt-1">
                            <select
                                wire:model.defer="equipment_id"
                                class="block w-full rounded-md border-gray-300 bg-white py-2 px-3 text-sm text-gray-900 shadow-sm
                                           focus:border-indigo-500 focus:ring-indigo-500
                                           dark:border-white/15 dark:bg-gray-900 dark:text-gray-100"
                            >
                                <option value="">Selecciona</option>
                                @foreach($equipment as $eq)
                                    <option value="{{ $eq->id }}">{{ $eq->name }}</option>
                                @endforeach
                            </select>
                        </div>
                        @error('equipment_id')
                        <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    {{-- Empleado --}}
                    <div>
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-200">Empleado</label>
                        <div class="mt-1">
                            <select
                                wire:model.defer="empleado_id"
                                class="block w-full rounded-md border-gray-300 bg-white py-2 px-3 text-sm text-gray-900 shadow-sm
                                           focus:border-indigo-500 focus:ring-indigo-500
                                           dark:border-white/15 dark:bg-gray-900 dark:text-gray-100"
                            >
                                <option value="">Selecciona</option>
                                @foreach($empleados as $emp)
                                    <option value="{{ $emp->id }}">{{ $emp->nombre }} {{ $emp->apellido_paterno }}</option>
                                @endforeach
                            </select>
                        </div>
                        @error('empleado_id')
                        <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    {{-- Horómetro --}}
                    <div>
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-200">
                            Horómetro <span class="text-xs text-gray-400">(opcional)</span>
                        </label>
                        <div class="mt-1">
                            <input
                                type="number"
                                step="0.01"
                                min="0"
                                wire:model.defer="hour_meter"
                                class="block w-full rounded-md border-gray-300 bg-white py-2 px-3 text-sm text-gray-900 shadow-sm
                                           focus:border-indigo-500 focus:ring-indigo-500
                                           dark:border-white/15 dark:bg-gray-900 dark:text-gray-100"
                                placeholder="Ej. 1234.50"
                            >
                        </div>
                        @error('hour_meter')
                        <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    {{-- Litros --}}
                    <div>
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-200">Litros</label>
                        <div class="mt-1">
                            <input
                                type="number"
                                step="0.01"
                                min="0"
                                wire:model.defer="liters"
                                class="block w-full rounded-md border-gray-300 bg-white py-2 px-3 text-sm text-gray-900 shadow-sm
                                           focus:border-indigo-500 focus:ring-indigo-500
                                           dark:border-white/15 dark:bg-gray-900 dark:text-gray-100"
                                placeholder="Ej. 250.00"
                            >
                        </div>
                        @error('liters')
                        <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    {{-- Notas --}}
                    <div class="md:col-span-2">
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-200">
                            Notas <span class="text-xs text-gray-400">(opcional)</span>
                        </label>
                        <div class="mt-1">
                                <textarea
                                    rows="3"
                                    wire:model.defer="notes"
                                    class="block w-full rounded-md border-gray-300 bg-white py-2 px-3 text-sm text-gray-900 shadow-sm
                                           focus:border-indigo-500 focus:ring-indigo-500
                                           dark:border-white/15 dark:bg-gray-900 dark:text-gray-100"
                                    placeholder="Ej. Observación de la carga…"
                                ></textarea>
                        </div>
                        @error('notes')
                        <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
                        @enderror
                    </div>
                </div>

                {{-- Footer modal --}}
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
                        class="inline-flex items-center rounded-md bg-emerald-600 px-4 py-1.5 text-xs font-semibold text-white shadow-sm
                                   hover:bg-emerald-500 focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2
                                   focus-visible:outline-emerald-600 disabled:opacity-50 disabled:cursor-not-allowed"
                        wire:loading.attr="disabled"
                        wire:target="save"
                    >
                        <svg
                            wire:loading
                            wire:target="save"
                            class="mr-2 h-3.5 w-3.5 animate-spin text-white"
                            xmlns="http://www.w3.org/2000/svg"
                            fill="none" viewBox="0 0 24 24">
                            <circle class="opacity-25" cx="12" cy="12" r="10"
                                    stroke="currentColor" stroke-width="4"></circle>
                            <path class="opacity-75" fill="currentColor"
                                  d="M4 12a8 8 0 018-8v4a4 4 0 00-4 4H4z"></path>
                        </svg>

                        <span>{{ $dieselLoadsId ? 'Guardar cambios' : 'Crear carga' }}</span>
                    </button>
                </div>
            </form>
        </div>
    </div>
@endif
