{{-- MODAL: Crear/Editar Usuario --}}
@if($open)
    <div class="fixed inset-0 z-50 flex items-center justify-center bg-black/40 backdrop-blur-sm">
        <div class="w-full max-w-5xl rounded-xl bg-white shadow-xl ring-1 ring-black/10 dark:bg-gray-900 dark:ring-white/10">
            {{-- Header modal --}}
            <div class="flex items-start justify-between border-b border-gray-100 px-6 py-4 dark:border-white/10">
                <div>
                    <h3 class="text-sm font-semibold text-gray-900 dark:text-white">
                        {{ $editingId ? 'Editar usuario' : 'Agregar usuario' }}
                    </h3>
                    <p class="mt-1 text-xs text-gray-500 dark:text-gray-400">
                        {{ $editingId ? 'Actualiza la información del usuario.' : 'Ingresa la información del nuevo usuario.' }}
                    </p>

                    <p class="mt-2 text-xs text-gray-500 dark:text-gray-400">
                        {{ $editingId
                            ? 'La información del usuario se actualizará. Los registros de nómina existentes permanecerán intactos.'
                            : 'Se creará el usuario y su perfil de empleado. Los registros de nómina se generarán automáticamente cuando se cree un periodo.'
                        }}
                    </p>
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

            {{-- Formulario --}}
            <form wire:submit.prevent="save" class="space-y-5 px-6 py-5">
                {{-- Nombres --}}
                <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-200">Nombre(s)</label>
                        <input
                            type="text"
                            wire:model.defer="name"
                            class="mt-1 block w-full rounded-md border-gray-300 bg-white py-2 px-3 text-sm text-gray-900 shadow-sm
                                       focus:border-indigo-500 focus:ring-indigo-500
                                       dark:border-white/15 dark:bg-gray-900 dark:text-gray-100"
                            placeholder="Nombre"
                        />
                        @error('name') <p class="mt-1 text-xs text-red-600">{{ $message }}</p> @enderror
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-200">Apellido paterno</label>
                        <input
                            type="text"
                            wire:model.defer="first_name"
                            class="mt-1 block w-full rounded-md border-gray-300 bg-white py-2 px-3 text-sm text-gray-900 shadow-sm
                                       focus:border-indigo-500 focus:ring-indigo-500
                                       dark:border-white/15 dark:bg-gray-900 dark:text-gray-100"
                            placeholder="Apellido paterno"
                        />
                        @error('first_name') <p class="mt-1 text-xs text-red-600">{{ $message }}</p> @enderror
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-200">Apellido materno</label>
                        <input
                            type="text"
                            wire:model.defer="last_name"
                            class="mt-1 block w-full rounded-md border-gray-300 bg-white py-2 px-3 text-sm text-gray-900 shadow-sm
                                       focus:border-indigo-500 focus:ring-indigo-500
                                       dark:border-white/15 dark:bg-gray-900 dark:text-gray-100"
                            placeholder="Apellido materno"
                        />
                        @error('last_name') <p class="mt-1 text-xs text-red-600">{{ $message }}</p> @enderror
                    </div>
                </div>

                {{-- Email y contraseña --}}
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-200">Correo electrónico</label>
                        <input
                            type="email"
                            wire:model.defer="email"
                            class="mt-1 block w-full rounded-md border-gray-300 bg-white py-2 px-3 text-sm text-gray-900 shadow-sm
                                       focus:border-indigo-500 focus:ring-indigo-500
                                       dark:border-white/15 dark:bg-gray-900 dark:text-gray-100"
                            placeholder="correo@empresa.com"
                        />
                        @error('email') <p class="mt-1 text-xs text-red-600">{{ $message }}</p> @enderror
                    </div>

                    @if(!$editingId)
                        <div>
                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-200">Contraseña</label>
                            <input
                                type="password"
                                wire:model.defer="password"
                                class="mt-1 block w-full rounded-md border-gray-300 bg-white py-2 px-3 text-sm text-gray-900 shadow-sm
                                           focus:border-indigo-500 focus:ring-indigo-500
                                           dark:border-white/15 dark:bg-gray-900 dark:text-gray-100"
                                placeholder="********"
                            />
                            @error('password') <p class="mt-1 text-xs text-red-600">{{ $message }}</p> @enderror
                        </div>
                    @endif
                </div>

                {{-- Rol / Categoría --}}
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-200">Rol del usuario</label>
                        <select
                            wire:model.defer="role"
                            class="mt-1 block w-full rounded-md border-gray-300 bg-white py-2 px-3 text-sm text-gray-900 shadow-sm
                                       focus:border-indigo-500 focus:ring-indigo-500
                                       dark:border-white/15 dark:bg-gray-900 dark:text-gray-100"
                        >
                            <option value="">Seleccione un rol</option>
                            @foreach($roles as $r)
                                <option value="{{ $r->name }}">{{ $r->name }}</option>
                            @endforeach
                        </select>
                        @error('role') <p class="mt-1 text-xs text-red-600">{{ $message }}</p> @enderror
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-200">Categoría (Puesto)</label>
                        <select
                            wire:model.defer="categoria"
                            class="mt-1 block w-full rounded-md border-gray-300 bg-white py-2 px-3 text-sm text-gray-900 shadow-sm
                                       focus:border-indigo-500 focus:ring-indigo-500
                                       dark:border-white/15 dark:bg-gray-900 dark:text-gray-100"
                        >
                            <option value="">Selecciona una categoría</option>
                            <option>Administrador</option>
                            <option>Ayudante de TR</option>
                            <option>AUX Administrativa</option>
                            <option>AUX de Almacén</option>
                            <option>Chofer de Volteo</option>
                            <option>Op. de Excavadora</option>
                            <option>Op. Payloader</option>
                            <option>Op. Trituradora</option>
                        </select>
                        @error('categoria') <p class="mt-1 text-xs text-red-600">{{ $message }}</p> @enderror
                    </div>
                </div>

                {{-- Turno / Unidad / Adscrito --}}
                <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-200">Turno</label>
                        <select
                            wire:model.defer="turno"
                            class="mt-1 block w-full rounded-md border-gray-300 bg-white py-2 px-3 text-sm text-gray-900 shadow-sm
                                       focus:border-indigo-500 focus:ring-indigo-500
                                       dark:border-white/15 dark:bg-gray-900 dark:text-gray-100"
                        >
                            <option value="">Selecciona un turno</option>
                            <option>Matutino</option>
                            <option>Vespertino</option>
                        </select>
                        @error('turno') <p class="mt-1 text-xs text-red-600">{{ $message }}</p> @enderror
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-200">Unidad de negocio</label>
                        <select
                            wire:model.defer="ubicacion"
                            class="mt-1 block w-full rounded-md border-gray-300 bg-white py-2 px-3 text-sm text-gray-900 shadow-sm
                                       focus:border-indigo-500 focus:ring-indigo-500
                                       dark:border-white/15 dark:bg-gray-900 dark:text-gray-100"
                        >
                            <option value="">Selecciona una unidad</option>
                            <option>KM9 - Campeche</option>
                            <option>Chocholá</option>
                        </select>
                        @error('ubicacion') <p class="mt-1 text-xs text-red-600">{{ $message }}</p> @enderror
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-200">Adscrito a</label>
                        <select
                            wire:model.defer="adscrito"
                            class="mt-1 block w-full rounded-md border-gray-300 bg-white py-2 px-3 text-sm text-gray-900 shadow-sm
                                       focus:border-indigo-500 focus:ring-indigo-500
                                       dark:border-white/15 dark:bg-gray-900 dark:text-gray-100"
                        >
                            <option value="">Selecciona</option>
                            <option>Trituradora M.A</option>
                            <option>Trituradora M.A2</option>
                        </select>
                        @error('adscrito') <p class="mt-1 text-xs text-red-600">{{ $message }}</p> @enderror
                    </div>
                </div>

                {{-- Sueldo / Hora extra --}}
                <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-200">Sueldo pactado</label>
                        <input
                            wire:model.defer="sueldo_pactado"
                            type="number"
                            step="0.0001"
                            class="mt-1 block w-full rounded-md border-gray-300 bg-white py-2 px-3 text-sm text-gray-900 shadow-sm
                                       focus:border-indigo-500 focus:ring-indigo-500
                                       dark:border-white/15 dark:bg-gray-900 dark:text-gray-100"
                            placeholder="$6500.00"
                        />
                        @error('sueldo_pactado') <p class="mt-1 text-xs text-red-600">{{ $message }}</p> @enderror
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-200">Costo por hora extra</label>
                        <input
                            wire:model.defer="costo_hora_extra"
                            type="number"
                            step="0.0001"
                            class="mt-1 block w-full rounded-md border-gray-300 bg-white py-2 px-3 text-sm text-gray-900 shadow-sm
                                       focus:border-indigo-500 focus:ring-indigo-500
                                       dark:border-white/15 dark:bg-gray-900 dark:text-gray-100"
                            placeholder="$67.00"
                        />
                        @error('costo_hora_extra') <p class="mt-1 text-xs text-red-600">{{ $message }}</p> @enderror
                    </div>

                    {{-- Campo auxiliar (solo lectura) --}}
                    <div>
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-200">Costo por día (auto)</label>
                        <input
                            type="text"
                            value="{{ number_format((float)($costo_dia ?? 0), 4) }}"
                            readonly
                            class="mt-1 block w-full rounded-md border-gray-200 bg-gray-50 py-2 px-3 text-sm text-gray-700 shadow-sm
                                       dark:border-white/10 dark:bg-gray-800 dark:text-gray-200"
                            placeholder="0.0000"
                        />
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
                        <span>{{ $editingId ? 'Guardar cambios' : 'Crear usuario' }}</span>
                    </button>
                </div>
            </form>
        </div>
    </div>
@endif
