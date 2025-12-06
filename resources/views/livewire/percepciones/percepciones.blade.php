<div>
    <div class="px-4 sm:px-6 lg:px-8">
        <div class="sm:flex sm:items-center mb-4">
            <div class="sm:flex-auto">
                <flux:breadcrumbs>
                    <flux:breadcrumbs.item href="#" icon="home" />
                    <flux:breadcrumbs.item>Percepciones</flux:breadcrumbs.item>
                </flux:breadcrumbs>
            </div>
        </div>
        <flux:input class="mt-4" wire:model.live="search" placeholder="Search..." icon="magnifying-glass" kbd="⌘K" />
        <div class="mt-8 relative">
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200 bg-white shadow rounded-lg">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-4 py-2 text-center text-xs font-medium text-gray-500 uppercase">Empleado</th>
                        <th class="px-4 py-2 text-center text-xs font-medium text-gray-500 uppercase">Unidad</th>
                        <th class="px-4 py-2 text-center text-xs font-medium text-gray-500 uppercase">Período</th>

                        @foreach ($conceptos as $con)
                            <th class="px-4 py-2 text-center text-xs font-medium text-gray-500 uppercase">
                                {{ $con->nombre }}
                            </th>
                        @endforeach

                        <th class="px-4 py-2 text-center text-xs font-medium text-gray-500 uppercase">Acción</th>
                    </tr>
                </thead>

                <tbody class="divide-y divide-gray-100">
                    @forelse($regs as $reg)
                        <tr class="hover:bg-gray-50">

                            <td class="px-4 py-2 text-center">
                                {{ $reg->empleado->nombre }} {{ $reg->empleado->apellido_paterno }}
                            </td>

                            <td class="px-4 py-2 text-center">
                                {{ $reg->empleado->unidad_negocio ?? '-' }}
                            </td>

                            <td class="px-4 py-2 text-center">
                                {{ $reg->periodoNomina->fecha_inicio->format('d/m/Y') }}
                                -
                                {{ $reg->periodoNomina->fecha_fin->format('d/m/Y') }}
                            </td>

                            {{-- 
                            | Formato | Resultado ejemplo |
                            | ------- | ----------------- |
                            | `d/m/Y` | 01/12/2025        |
                            | `d M Y` | 01 Dec 2025       |
                            | `d F Y` | 01 December 2025  |
                            | `d-m-Y` | 01-12-2025        |
                            | `d/m`   | 01/12             |
                            --}}

                            @foreach ($conceptos as $con)
                                <td class="text-center">
                                    <input type="number" step="0.01" min="0"
                                        wire:model="montos.{{ $reg->id }}.{{ $con->id }}"
                                        class="w-24 border rounded p-1 text-sm text-center"
                                    >
                                </td>
                            @endforeach

                            <td class="px-4 py-2 text-center">
                                <flux:button variant="primary"
                                    wire:click="guardarFila({{ $reg->id }})"
                                    size="sm" class="ml-1">
                                    Guardar
                                </flux:button>
                            </td>

                        </tr>
                    @empty
                        <tr>
                            <td colspan="20" class="px-4 py-4 text-center text-gray-400">
                                No hay registros de nómina activos
                            </td>
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
        </div>
    </div>
</div>