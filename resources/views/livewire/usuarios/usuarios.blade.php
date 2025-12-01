{{-- <div>
    <div class="px-4 sm:px-6 lg:px-8">
        <div class="sm:flex sm:items-center">
            <div class="sm:flex-auto">
                <div class="sm:flex sm:items-center">
                    <flux:breadcrumbs>
                        <flux:breadcrumbs.item href="#" icon="home" />
                        <flux:breadcrumbs.item>Usuarios</flux:breadcrumbs.item>
                    </flux:breadcrumbs>
                </div>
            </div>
            <flux:button wire:click="newUser">Agregar usuario</flux:button>
        </div>
        <flux:input class="mt-4" wire:model.live="search" placeholder="Search..." icon="magnifying-glass" kbd="⌘K" />
    </div>

        <div class="mt-8 relative">
        <table class="min-w-full divide-y divide-gray-200 bg-white shadow rounded-lg">
            <thead class="bg-gray-50">
                <tr>
                    <th class="px-4 py-2 text-center text-xs font-medium text-gray-500 uppercase">Nombre de usuario</th>
                    <th class="px-4 py-2 text-center text-xs font-medium text-gray-500 uppercase">Email de usuario</th>
                    <th class="px-4 py-2 text-center text-xs font-medium text-gray-500 uppercase">Rol de usuario</th>
                    <th class="px-4 py-2 text-center text-xs font-medium text-gray-500 uppercase">Acción</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-100">
                @forelse($users as $user)
                    <tr>
                        <td class="px-4 py-2 text-sm text-gray-700 text-center">{{ $user->name }}</td>
                        <td class="px-4 py-2 text-sm text-gray-700 text-center">{{ $user->email }}</td>
                        <td class="px-4 py-2 text-sm text-gray-700 text-center">
                            @if ($user->getRoleNames()->first() === 'super_administrador')
                                <flux:badge color="orange">{{ $user->getRoleNames()->first() }}</flux:badge>
                            @elseif ($user->getRoleNames()->first() === 'trabajador')
                                <flux:badge color="blue">{{ $user->getRoleNames()->first() }}</flux:badge>
                            @elseif ($user->getRoleNames()->first() === 'administrador')
                                <flux:badge color="pink">{{ $user->getRoleNames()->first() }}</flux:badge>
                            @else
                                <flux:badge color="gray">Sin rol</flux:badge>
                            @endif
                        </td>
                        <td class="px-4 py-2 text-sm text-center">
                            <flux:button variant="primary" wire:click="edit({{ $user->id }})" size="sm">Editar</flux:button>

                            <flux:button variant="danger" wire:click="confirmDelete({{ $user->id }})" size="sm" class="ml-2">Eliminar</flux:button>
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
                Resultado: {{ \Illuminate\Support\Number::format($users->total()) }}
            </div>
            {{ $users->links('livewire.pagination.pagination') }}
        </div>
    </div>

    <flux:modal name="nuevo-usuario" class="md:w-96">
        <form wire:submit.prevent="save" class="space-y-6">
            <div>
                <flux:heading size="lg">{{ $editingId ? 'Editar usuario' : 'Agregar usuario' }}</flux:heading>
                <flux:text class="mt-2">
                    {{ $editingId ? 'Actualiza la información del usuario.' : 'Ingresa la información del nuevo usuario.' }}
                </flux:text>
            </div>

            <flux:input wire:model="name" label="Nombre del usuario" placeholder="Nombre de usuario" />
            <flux:input wire:model="email" label="Correo electrónico" placeholder="Correo electrónico" />
            
            @if(!$editingId)
                <flux:input wire:model="password" label="Contraseña" type="password" placeholder="Contraseña" />
            @endif

            <flux:select label="Rol del usuario" wire:model="role">
                <option value="">Seleccione un rol</option>
                @foreach($roles as $role)
                    <option value="{{ $role->name }}">{{ $role->name }}</option>
                @endforeach
            </flux:select>

            <div class="flex gap-2">
                <flux:spacer />
                <flux:button variant="subtle" type="button" wire:click="resetForm">Cancelar</flux:button>
                <flux:button type="submit" variant="primary">{{ $editingId ? 'Actualizar' : 'Guardar' }}</flux:button>
            </div>
        </form>
    </flux:modal>

    <flux:modal name="confirm-delete" class="md:w-80">
        <div class="space-y-4">
            <flux:heading size="lg">Confirmar eliminación</flux:heading>
            <flux:text>¿Seguro que quieres eliminar este usuario? Esta acción no se puede deshacer.</flux:text>

            <div class="flex justify-end gap-2">
                <flux:button variant="subtle" wire:click="cancelDelete">Cancelar</flux:button>
                <flux:button variant="danger" wire:click="deleteUser">Eliminar</flux:button>
            </div>
        </div>
    </flux:modal>
</div> --}}


{{-- V2 - Usuarios --}}
<div>
    <div class="px-4 sm:px-6 lg:px-8">
        <div class="sm:flex sm:items-center">
            <div class="sm:flex-auto">
                <div class="sm:flex sm:items-center">
                    <flux:breadcrumbs>
                        <flux:breadcrumbs.item href="#" icon="home" />
                        <flux:breadcrumbs.item>Usuarios</flux:breadcrumbs.item>
                    </flux:breadcrumbs>
                </div>
            </div>
            <flux:button wire:click="newUser">Agregar usuario</flux:button>
        </div>
        <flux:input class="mt-4" wire:model.live="search" placeholder="Search..." icon="magnifying-glass" kbd="⌘K" />
    </div>

        <div class="mt-8 relative">
        <table class="min-w-full divide-y divide-gray-200 bg-white shadow rounded-lg">
            <thead class="bg-gray-50">
                <tr>
                    <th class="px-4 py-2 text-center text-xs font-medium text-gray-500 uppercase">Nombre de usuario</th>
                    <th class="px-4 py-2 text-center text-xs font-medium text-gray-500 uppercase">Email de usuario</th>
                    <th class="px-4 py-2 text-center text-xs font-medium text-gray-500 uppercase">Rol de usuario</th>
                    <th class="px-4 py-2 text-center text-xs font-medium text-gray-500 uppercase">Acción</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-100">
                @forelse($users as $user)
                    <tr>
                        <td class="px-4 py-2 text-sm text-gray-700 text-center">{{ $user->name }}</td>
                        <td class="px-4 py-2 text-sm text-gray-700 text-center">{{ $user->email }}</td>
                        <td class="px-4 py-2 text-sm text-gray-700 text-center">
                            @if ($user->getRoleNames()->first() === 'super_administrador')
                                <flux:badge color="orange">{{ $user->getRoleNames()->first() }}</flux:badge>
                            @elseif ($user->getRoleNames()->first() === 'trabajador')
                                <flux:badge color="blue">{{ $user->getRoleNames()->first() }}</flux:badge>
                            @elseif ($user->getRoleNames()->first() === 'administrador')
                                <flux:badge color="pink">{{ $user->getRoleNames()->first() }}</flux:badge>
                            @else
                                <flux:badge color="gray">Sin rol</flux:badge>
                            @endif
                        </td>
                        <td class="px-4 py-2 text-sm text-center">
                            <flux:button variant="primary" wire:click="edit({{ $user->id }})" size="sm">Editar</flux:button>

                            <flux:button variant="danger" wire:click="confirmDelete({{ $user->id }})" size="sm" class="ml-2">Eliminar</flux:button>
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
                Resultado: {{ \Illuminate\Support\Number::format($users->total()) }}
            </div>
            {{ $users->links('livewire.pagination.pagination') }}
        </div>
    </div>

    <flux:modal name="nuevo-usuario" class="w-[900px] !max-w-[1600px]">
        <form wire:submit.prevent="save" class="space-y-6">

            <!-- Encabezado -->
            <div>
                <flux:heading size="lg">
                    {{ $editingId ? 'Editar usuario' : 'Agregar usuario' }}
                </flux:heading>

                <flux:text class="mt-2">
                    {{ $editingId ? 'Actualiza la información del usuario.' : 'Ingresa la información del nuevo usuario.' }}
                </flux:text>
            </div>

            <!-- Nombres -->
            <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                <flux:input wire:model="name" label="Nombre(s)" placeholder="Nombre" />
                <flux:input wire:model="apellido_paterno" label="Apellido paterno" placeholder="Apellido paterno" />
                <flux:input wire:model="apellido_materno" label="Apellido materno" placeholder="Apellido materno" />
            </div>

            <!-- Email y contraseña -->
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <flux:input wire:model="email" label="Correo electrónico" placeholder="Email" />

                @if(!$editingId)
                    <flux:input wire:model="password" label="Contraseña" type="password" placeholder="Contraseña" />
                @endif
            </div>

            <!-- Rol del usuario -->
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <flux:select wire:model="role" placeholder="Rol del usuario" label="Rol del usuario">
                    @foreach($roles as $role)
                        <option value="{{ $role->name }}">{{ $role->name }}</option>
                    @endforeach
                </flux:select>

                <!-- Categoría / Puesto (opciones reales del PDF) -->
                <flux:select wire:model="categoria" label="Categoría (Puesto)" placeholder="Selecciona una categoría">
                    <flux:select.option>Administrador</flux:select.option>
                    <flux:select.option>Ayudante de TR</flux:select.option>
                    <flux:select.option>AUX Administrativa</flux:select.option>
                    <flux:select.option>AUX de Almacén</flux:select.option>
                    <flux:select.option>Chofer de Volteo</flux:select.option>
                    <flux:select.option>Op. de Excavadora</flux:select.option>
                    <flux:select.option>Op. Payloader</flux:select.option>
                    <flux:select.option>Op. Trituradora</flux:select.option>
                </flux:select>
            </div>

            <!-- Turno, Ubicación y Adscrito -->
            <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                <flux:select wire:model="turno" label="Turno" placeholder="Selecciona un turno">
                    <flux:select.option>Matutino</flux:select.option>
                    <flux:select.option>Vespertino</flux:select.option>
                </flux:select>

                <flux:select wire:model="ubicacion" label="Ubicación" placeholder="Selecciona una ubicación">
                    <flux:select.option>Carretera Campeche - Mérida</flux:select.option>
                    <flux:select.option>KM9 - Campeche</flux:select.option>
                    <flux:select.option>Chocholá</flux:select.option>
                </flux:select>

                <flux:select wire:model="adscrito" label="Adscrito a" placeholder="Selecciona una adscripción">
                    <flux:select.option>Trituradora M.A</flux:select.option>
                    <flux:select.option>Trituradora M.A2</flux:select.option>
                </flux:select>
            </div>

            <!-- Sueldos y costos -->
            <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                <flux:input wire:model="costo_dia" type="number" step="0.01" label="Costo por día" placeholder="536.00" />
                <flux:input wire:model="costo_hora" type="number" step="0.01" label="Costo por hora" placeholder="67.00" />
                <flux:input wire:model="sueldo_pactado" type="number" step="0.01" label="Sueldo pactado" placeholder="6500.00" />
            </div>

            <!-- Botones -->
            <div class="flex justify-end gap-2 mt-4">
                {{-- <flux:button variant="subtle" type="button" wire:click="resetForm">Cancelar</flux:button> --}}
                <flux:modal.close>
                    <flux:button variant="ghost">Cancel</flux:button>
                </flux:modal.close>
                <flux:button variant="primary" type="submit">
                    {{ $editingId ? 'Actualizar' : 'Guardar' }}
                </flux:button>
            </div>
        </form>
    </flux:modal>

    <flux:modal name="confirm-delete" class="md:w-80">
        <div class="space-y-4">
            <flux:heading size="lg">Confirmar eliminación</flux:heading>
            <flux:text>¿Seguro que quieres eliminar este usuario? Esta acción no se puede deshacer.</flux:text>

            <div class="flex justify-end gap-2">
                {{-- <flux:button variant="subtle" wire:click="cancelDelete">Cancelar</flux:button> --}}
                <flux:modal.close>
                    <flux:button variant="ghost">Cancel</flux:button>
                </flux:modal.close>
                <flux:button variant="danger" wire:click="deleteUser">Eliminar</flux:button>
            </div>
        </div>
    </flux:modal>
</div>