<div>
    {{-- <x-layouts.empleado>
        <div class="min-h-screen flex items-center justify-center bg-gray-100 dark:bg-gray-800">
            <div class="w-full max-w-md p-6 bg-white dark:bg-gray-900 rounded shadow">
                <h2 class="text-2xl font-bold text-center mb-4">Registro de Asistencia</h2>

                @if (session()->has('status'))
                    <div class="bg-green-100 text-green-800 p-2 mb-4 rounded">
                        {{ session('status') }}
                    </div>
                @endif

                @if (session()->has('error'))
                    <div class="bg-red-100 text-red-800 p-2 mb-4 rounded">
                        {{ session('error') }}
                    </div>
                @endif

                <form wire:submit.prevent="marcarEntradaSalida" class="flex flex-col gap-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-200">Correo electrónico</label>
                        <input
                            type="email"
                            wire:model="email"
                            class="mt-1 block w-full border rounded p-2 dark:bg-gray-800 dark:text-white"
                            placeholder="empleado@empresa.com"
                            required
                        >
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 dark:text-gray-200">Contraseña</label>
                        <input
                            type="password"
                            wire:model="password"
                            class="mt-1 block w-full border rounded p-2 dark:bg-gray-800 dark:text-white"
                            placeholder="********"
                            required
                        >
                    </div>

                    <button
                        type="submit"
                        class="mt-4 w-full bg-blue-600 text-white font-semibold py-2 px-4 rounded hover:bg-blue-700 transition"
                    >
                        Marcar Entrada / Salida
                    </button>
                </form>
            </div>
        </div>
    </x-layouts.empleado> --}}
    Entrada a login-empleado.blade.php
    {{-- <div>
        <x-layouts.empleado>
            <div class="min-h-screen flex items-center justify-center bg-gray-100 dark:bg-gray-800">
                <div class="w-full max-w-md p-6 bg-white dark:bg-gray-900 rounded shadow">
                    <h2 class="text-2xl font-bold text-center mb-4">Registro de Asistencia</h2>

                    @if (session()->has('status'))
                        <div class="bg-green-100 text-green-800 p-2 mb-4 rounded">
                            {{ session('status') }}
                        </div>
                    @endif

                    @if (session()->has('error'))
                        <div class="bg-red-100 text-red-800 p-2 mb-4 rounded">
                            {{ session('error') }}
                        </div>
                    @endif

                    <form wire:submit.prevent="marcarEntradaSalida" class="flex flex-col gap-4">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-200">Correo electrónico</label>
                            <input
                                type="email"
                                wire:model="email"
                                class="mt-1 block w-full border rounded p-2 dark:bg-gray-800 dark:text-white"
                                placeholder="empleado@empresa.com"
                                required
                            >
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-200">Contraseña</label>
                            <input
                                type="password"
                                wire:model="password"
                                class="mt-1 block w-full border rounded p-2 dark:bg-gray-800 dark:text-white"
                                placeholder="********"
                                required
                            >
                        </div>

                        <button
                            type="submit"
                            class="mt-4 w-full bg-blue-600 text-white font-semibold py-2 px-4 rounded hover:bg-blue-700 transition"
                        >
                            Marcar Entrada / Salida
                        </button>
                    </form>
                </div>
            </div>
        </x-layouts.empleado>
    </div> --}}
</div>
