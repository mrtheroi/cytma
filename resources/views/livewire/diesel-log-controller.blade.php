<div class="space-y-6">

    {{-- Título --}}
    <div class="flex items-center justify-between">
        <div>
            <h2 class="text-lg font-semibold text-gray-900 dark:text-white">Bitácora de Diésel</h2>
            <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">
                Consulta y registra cargas de diésel. (Activo si deleted_at es NULL)
            </p>
        </div>
    </div>

    {{-- KPI Cards --}}
    <div class="grid grid-cols-1 gap-3 sm:grid-cols-2 lg:grid-cols-3">
        {{-- Total litros --}}
        <div class="rounded-xl bg-white p-4 shadow-sm ring-1 ring-black/5 dark:bg-gray-900 dark:ring-white/10">
            <div class="flex items-start justify-between gap-3">
                <div>
                    <p class="text-xs font-medium text-gray-500 dark:text-gray-400">Total diésel (L)</p>
                    <p class="mt-1 text-2xl font-semibold text-gray-900 dark:text-white">
                        {{ number_format($totalLiters ?? 0, 2) }}
                    </p>
                    <p class="mt-1 text-xs text-gray-500 dark:text-gray-400">Considerando filtros actuales</p>
                </div>

                <span class="inline-flex h-10 w-10 items-center justify-center rounded-lg
                     bg-emerald-50 text-emerald-700 ring-1 ring-inset ring-emerald-600/20
                     dark:bg-emerald-900/30 dark:text-emerald-300 dark:ring-emerald-500/30">
                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 640 640" class="h-5 w-5 fill-current" aria-hidden="true">
                        <path d="M320 96C337.7 96 352 110.3 352 128L352 256L128 256L128 128C128 110.3 142.3 96 160 96L320 96zM352 288L352 544L128 544L128 288L352 288zM96 128L96 544C87.2 544 80 551.2 80 560C80 568.8 87.2 576 96 576L384 576C392.8 576 400 568.8 400 560C400 551.2 392.8 544 384 544L384 384L400 384C426.5 384 448 405.5 448 432L448 448C448 483.3 476.7 512 512 512C547.3 512 576 483.3 576 448L576 221.1C576 203.2 568.5 186 555.2 173.9L474.8 100.2C468.3 94.2 458.2 94.7 452.2 101.2C446.2 107.7 446.7 117.8 453.2 123.8L480 148.4L480 224C480 259.3 508.7 288 544 288L544 448C544 465.7 529.7 480 512 480C494.3 480 480 465.7 480 448L480 432C480 387.8 444.2 352 400 352L384 352L384 128C384 92.7 355.3 64 320 64L160 64C124.7 64 96 92.7 96 128zM544 256C526.3 256 512 241.7 512 224L512 177.7L533.6 197.5C540.2 203.6 544 212.1 544 221.1L544 256z"/>
                    </svg>
                </span>
            </div>
        </div>

        {{-- # Cargas --}}
        {{-- Cargas registradas --}}
        <div class="rounded-xl bg-white p-4 shadow-sm ring-1 ring-black/5 dark:bg-gray-900 dark:ring-white/10">
            <div class="flex items-start justify-between gap-3">
                <div>
                    <p class="text-xs font-medium text-gray-500 dark:text-gray-400">Cargas registradas</p>
                    <p class="mt-1 text-2xl font-semibold text-gray-900 dark:text-white">
                        {{ number_format($totalRows ?? 0) }}
                    </p>
                    <p class="mt-1 text-xs text-gray-500 dark:text-gray-400">
                        Registros en el periodo
                    </p>
                </div>

                <span class="inline-flex h-10 w-10 items-center justify-center rounded-lg
                     bg-indigo-50 text-indigo-700 ring-1 ring-inset ring-indigo-600/20
                     dark:bg-indigo-900/30 dark:text-indigo-300 dark:ring-indigo-500/30">
                    <svg xmlns="http://www.w3.org/2000/svg"
                         viewBox="0 0 640 640"
                         class="h-5 w-5 fill-current"
                         aria-hidden="true">
                        <path d="M128 80L384 80C410.5 80 432 101.5 432 128L432 274.7C437.3 273.8 442.6 273.1 448 272.7L448 128C448 92.7 419.3 64 384 64L128 64C92.7 64 64 92.7 64 128L64 512C64 547.3 92.7 576 128 576L308 576C304.3 570.9 300.8 565.5 297.7 560L128 560C101.5 560 80 538.5 80 512L80 128C80 101.5 101.5 80 128 80zM160 200C160 204.4 163.6 208 168 208L344 208C348.4 208 352 204.4 352 200C352 195.6 348.4 192 344 192L168 192C163.6 192 160 195.6 160 200zM160 296C160 300.4 163.6 304 168 304L312 304C316.4 304 320 300.4 320 296C320 291.6 316.4 288 312 288L168 288C163.6 288 160 291.6 160 296zM160 392C160 396.4 163.6 400 168 400L248 400C252.4 400 256 396.4 256 392C256 387.6 252.4 384 248 384L168 384C163.6 384 160 387.6 160 392zM464 336C534.7 336 592 393.3 592 464C592 534.7 534.7 592 464 592C393.3 592 336 534.7 336 464C336 393.3 393.3 336 464 336zM464 608C543.5 608 608 543.5 608 464C608 384.5 543.5 320 464 320C384.5 320 320 384.5 320 464C320 543.5 384.5 608 464 608zM524.7 395.5C521.1 392.9 516.1 393.7 513.5 397.3L439.5 499L406.1 458.9C403.3 455.5 398.2 455 394.8 457.9C391.4 460.8 390.9 465.8 393.8 469.2L433.8 517.2C435.4 519.1 437.7 520.2 440.2 520.1C442.7 520 445 518.8 446.4 516.8L526.4 406.8C529 403.2 528.2 398.2 524.6 395.6z"/>
                    </svg>
                </span>
            </div>
        </div>

        {{-- Promedio --}}
        <div class="rounded-xl bg-white p-4 shadow-sm ring-1 ring-black/5 dark:bg-gray-900 dark:ring-white/10">
            <div class="flex items-start justify-between gap-3">
                <div>
                    <p class="text-xs font-medium text-gray-500 dark:text-gray-400">Promedio por carga (L)</p>
                    <p class="mt-1 text-2xl font-semibold text-gray-900 dark:text-white">
                        {{ number_format($avgLiters ?? 0, 2) }}
                    </p>
                    <p class="mt-1 text-xs text-gray-500 dark:text-gray-400">Total / # cargas</p>
                </div>

                <span class="inline-flex h-10 w-10 items-center justify-center rounded-lg
                     bg-amber-50 text-amber-700 ring-1 ring-inset ring-amber-600/20
                     dark:bg-amber-900/30 dark:text-amber-300 dark:ring-amber-500/30">
                    <svg xmlns="http://www.w3.org/2000/svg"
                         viewBox="0 0 640 640"
                         class="h-5 w-5 fill-current"
                         aria-hidden="true">
                        <path d="M96 112L96 96L64 96L64 544L576 544L576 512L96 512L96 112zM334.5 161.2L317.7 125.6L304.9 162.8L228.6 384L160 384L160 416L251.4 416L255.1 405.2L322.3 210.4L369.5 310.8L373.8 320L455.4 320L514.7 408.9L523.6 422.2L550.2 404.4L541.3 391.1L477.3 295.1L472.6 288L394.2 288L334.5 161.2z"/>
                    </svg>
                </span>
            </div>
        </div>

    </div>


    {{-- Barra buscador + filtros --}}
    <div class="rounded-xl bg-white p-4 shadow-sm ring-1 ring-black/5 dark:bg-gray-900 dark:ring-white/10">
        <div class="grid grid-cols-1 gap-3 lg:grid-cols-12">
            {{-- Buscador --}}
            <div class="lg:col-span-4">
                <label class="block text-xs font-medium text-gray-700 dark:text-gray-200">Buscar</label>
                <input
                    wire:model.live.debounce.350ms="search"
                    type="text"
                    placeholder="Notas, unidad, proveedor, equipo, empleado…"
                    class="mt-1 block w-full rounded-md border-gray-300 bg-white py-2 px-3 text-sm text-gray-900 shadow-sm
                           focus:border-indigo-500 focus:ring-indigo-500
                           dark:border-white/15 dark:bg-gray-900 dark:text-gray-100"
                >
            </div>

            {{-- Desde --}}
            <div class="lg:col-span-2">
                <label class="block text-xs font-medium text-gray-700 dark:text-gray-200">Desde</label>
                <input
                    wire:model.live="from"
                    type="date"
                    class="mt-1 block w-full rounded-md border-gray-300 bg-white py-2 px-3 text-sm text-gray-900 shadow-sm
                           focus:border-indigo-500 focus:ring-indigo-500
                           dark:border-white/15 dark:bg-gray-900 dark:text-gray-100"
                >
            </div>

            {{-- Hasta --}}
            <div class="lg:col-span-2">
                <label class="block text-xs font-medium text-gray-700 dark:text-gray-200">Hasta</label>
                <input
                    wire:model.live="to"
                    type="date"
                    class="mt-1 block w-full rounded-md border-gray-300 bg-white py-2 px-3 text-sm text-gray-900 shadow-sm
                           focus:border-indigo-500 focus:ring-indigo-500
                           dark:border-white/15 dark:bg-gray-900 dark:text-gray-100"
                >
            </div>

            {{-- Unidad --}}
            <div class="lg:col-span-2">
                <label class="block text-xs font-medium text-gray-700 dark:text-gray-200">Unidad</label>
                <select
                    wire:model.live="business_unit_id_filter"
                    class="mt-1 block w-full rounded-md border-gray-300 bg-white py-2 px-3 text-sm text-gray-900 shadow-sm
                           focus:border-indigo-500 focus:ring-indigo-500
                           dark:border-white/15 dark:bg-gray-900 dark:text-gray-100"
                >
                    <option value="">Todas</option>
                    @foreach($businessUnits as $bu)
                        <option value="{{ $bu->id }}">{{ $bu->name }}</option>
                    @endforeach
                </select>
            </div>

            {{-- Equipo --}}
            <div class="lg:col-span-2">
                <label class="block text-xs font-medium text-gray-700 dark:text-gray-200">Equipo</label>
                <select
                    wire:model.live="equipment_id_filter"
                    class="mt-1 block w-full rounded-md border-gray-300 bg-white py-2 px-3 text-sm text-gray-900 shadow-sm
                           focus:border-indigo-500 focus:ring-indigo-500
                           dark:border-white/15 dark:bg-gray-900 dark:text-gray-100"
                >
                    <option value="">Todos</option>
                    @foreach($equipment as $eq)
                        <option value="{{ $eq->id }}">{{ $eq->name }}</option>
                    @endforeach
                </select>
            </div>

            {{-- Proveedor --}}
            <div class="lg:col-span-3">
                <label class="block text-xs font-medium text-gray-700 dark:text-gray-200">Proveedor</label>
                <select
                    wire:model.live="supplier_id_filter"
                    class="mt-1 block w-full rounded-md border-gray-300 bg-white py-2 px-3 text-sm text-gray-900 shadow-sm
                           focus:border-indigo-500 focus:ring-indigo-500
                           dark:border-white/15 dark:bg-gray-900 dark:text-gray-100"
                >
                    <option value="">Todos</option>
                    @foreach($suppliers as $sp)
                        <option value="{{ $sp->id }}">{{ $sp->name }}</option>
                    @endforeach
                </select>
            </div>

            {{-- Empleado --}}
            <div class="lg:col-span-3">
                <label class="block text-xs font-medium text-gray-700 dark:text-gray-200">Empleado</label>
                <select
                    wire:model.live="empleado_id_filter"
                    class="mt-1 block w-full rounded-md border-gray-300 bg-white py-2 px-3 text-sm text-gray-900 shadow-sm
                           focus:border-indigo-500 focus:ring-indigo-500
                           dark:border-white/15 dark:bg-gray-900 dark:text-gray-100"
                >
                    <option value="">Todos</option>
                    @foreach($empleados as $emp)
                        <option value="{{ $emp->id }}">{{ $emp->name }}</option>
                    @endforeach
                </select>
            </div>

            {{-- Botón limpiar filtros --}}
            <div class="lg:col-span-12 flex justify-end pt-2">
                <button
                    type="button"
                    wire:click="resetFilters"
                    class="rounded-md px-3 py-1.5 text-xs font-medium text-gray-600 hover:bg-gray-100 dark:text-gray-300 dark:hover:bg-gray-800"
                >
                    Limpiar filtros
                </button>
            </div>
        </div>
    </div>

    <div class="flex flex-wrap items-center justify-end gap-2">
        {{-- Agregar --}}
        <button
            wire:click="create"
            type="button"
            class="inline-flex items-center gap-2 rounded-md bg-emerald-600 px-3 py-1.5 text-xs font-semibold text-white shadow-sm
               hover:bg-emerald-500 focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2
               focus-visible:outline-emerald-600"
        >
            <svg class="h-4 w-4" viewBox="0 0 20 20" fill="currentColor" aria-hidden="true">
                <path d="M10 3a1 1 0 0 1 1 1v5h5a1 1 0 1 1 0 2h-5v5a1 1 0 1 1-2 0v-5H4a1 1 0 1 1 0-2h5V4a1 1 0 0 1 1-1z"/>
            </svg>
            Agregar
        </button>

        {{-- Excel --}}
        <button
            type="button"
            wire:click="exportExcel"
            class="inline-flex items-center gap-2 rounded-md px-3 py-1.5 text-xs font-semibold
           text-gray-700 ring-1 ring-inset ring-gray-200 shadow-sm
           hover:bg-emerald-50 hover:text-emerald-700
           focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-emerald-600
           dark:text-gray-200 dark:ring-white/10 dark:hover:bg-emerald-900/30 dark:hover:text-emerald-300"
        >
            <svg xmlns="http://www.w3.org/2000/svg"
                 viewBox="0 0 640 640"
                 class="h-4 w-4 fill-current text-emerald-600 dark:text-emerald-300"
                 aria-hidden="true">
                <path d="M128 80L261.5 80C265.1 80 268.6 80.4 272 81.2L272 184C272 214.9 297.1 240 328 240L430.8 240C431.6 243.4 432 246.9 432 250.5L432 400L448 400L448 250.5C448 233.5 441.3 217.2 429.3 205.2L306.7 82.7C294.7 70.7 278.5 64 261.5 64L128 64C92.7 64 64 92.7 64 128L64 512C64 547.3 92.7 576 128 576L208 576L208 560L128 560C101.5 560 80 538.5 80 512L80 128C80 101.5 101.5 80 128 80zM424 224L328 224C305.9 224 288 206.1 288 184L288 88C290.7 89.8 293.1 91.8 295.4 94.1L417.9 216.6C420.2 218.9 422.2 221.4 424 224zM296 464C278.3 464 264 478.3 264 496L264 576C264 593.7 278.3 608 296 608L312 608C329.7 608 344 593.7 344 576L344 568C344 563.6 340.4 560 336 560C331.6 560 328 563.6 328 568L328 576C328 584.8 320.8 592 312 592L296 592C287.2 592 280 584.8 280 576L280 496C280 487.2 287.2 480 296 480L312 480C320.8 480 328 487.2 328 496L328 504C328 508.4 331.6 512 336 512C340.4 512 344 508.4 344 504L344 496C344 478.3 329.7 464 312 464L296 464zM432 464C409.9 464 392 481.9 392 504C392 526.1 409.9 544 432 544C445.3 544 456 554.7 456 568C456 581.3 445.3 592 432 592L400 592C395.6 592 392 595.6 392 600C392 604.4 395.6 608 400 608L432 608C454.1 608 472 590.1 472 568C472 545.9 454.1 528 432 528C418.7 528 408 517.3 408 504C408 490.7 418.7 480 432 480L456 480C460.4 480 464 476.4 464 472C464 467.6 460.4 464 456 464L432 464zM528 464C523.6 464 520 467.6 520 472L520 503.6C520 536.8 529.8 569.2 548.2 596.8L553.3 604.5C554.8 606.7 557.3 608.1 560 608.1C562.7 608.1 565.2 606.8 566.7 604.5L571.8 596.8C590.2 569.2 600 536.8 600 503.6L600 472C600 467.6 596.4 464 592 464C587.6 464 584 467.6 584 472L584 503.6C584 532.7 575.7 561.1 560 585.6C544.3 561.1 536 532.7 536 503.6L536 472C536 467.6 532.4 464 528 464z"/>
            </svg>
            Excel
        </button>



        {{-- PDF --}}
        <button
            type="button"
            wire:click="exportPdf"
            class="inline-flex items-center gap-2 rounded-md px-3 py-1.5 text-xs font-semibold
           text-gray-700 ring-1 ring-inset ring-gray-200 shadow-sm
           hover:bg-red-50 hover:text-red-700
           focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-red-600
           dark:text-gray-200 dark:ring-white/10 dark:hover:bg-red-900/30 dark:hover:text-red-300"
        >
            <svg xmlns="http://www.w3.org/2000/svg"
                 viewBox="0 0 640 640"
                 class="h-4 w-4 fill-current text-red-600 dark:text-red-300"
                 aria-hidden="true">
                <path d="M128 80L261.5 80C265.1 80 268.6 80.4 272 81.2L272 184C272 214.9 297.1 240 328 240L430.8 240C431.6 243.4 432 246.9 432 250.5L432 400L448 400L448 250.5C448 233.5 441.3 217.2 429.3 205.2L306.7 82.7C294.7 70.7 278.5 64 261.5 64L128 64C92.7 64 64 92.7 64 128L64 512C64 547.3 92.7 576 128 576L208 576L208 560L128 560C101.5 560 80 538.5 80 512L80 128C80 101.5 101.5 80 128 80zM424 224L328 224C305.9 224 288 206.1 288 184L288 88C290.7 89.8 293.1 91.8 295.4 94.1L417.9 216.6C420.2 218.9 422.2 221.4 424 224zM272 464C267.6 464 264 467.6 264 472L264 600C264 604.4 267.6 608 272 608C276.4 608 280 604.4 280 600L280 560L304 560C330.5 560 352 538.5 352 512C352 485.5 330.5 464 304 464L272 464zM304 544L280 544L280 480L304 480C321.7 480 336 494.3 336 512C336 529.7 321.7 544 304 544zM400 464C395.6 464 392 467.6 392 472L392 600C392 604.4 395.6 608 400 608L432 608C454.1 608 472 590.1 472 568L472 504C472 481.9 454.1 464 432 464L400 464zM408 592L408 480L432 480C445.3 480 456 490.7 456 504L456 568C456 581.3 445.3 592 432 592L408 592zM520 472L520 600C520 604.4 523.6 608 528 608C532.4 608 536 604.4 536 600L536 544L576 544C580.4 544 584 540.4 584 536C584 531.6 580.4 528 576 528L536 528L536 480L576 480C580.4 480 584 476.4 584 472C584 467.6 580.4 464 576 464L528 464C523.6 464 520 467.6 520 472z"/>
            </svg>
            PDF
        </button>


    </div>



    {{-- Tabla --}}
    <div class="overflow-hidden rounded-xl bg-white shadow-sm ring-1 ring-black/5 dark:bg-gray-900 dark:ring-white/10">
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-100 dark:divide-white/10">
                <thead class="bg-gray-50 dark:bg-gray-950/40">
                <tr>
                    <th class="px-4 py-3 text-left text-xs font-semibold uppercase tracking-wide text-gray-600 dark:text-gray-300">Fecha</th>
                    <th class="px-4 py-3 text-left text-xs font-semibold uppercase tracking-wide text-gray-600 dark:text-gray-300">Unidad</th>
                    <th class="px-4 py-3 text-left text-xs font-semibold uppercase tracking-wide text-gray-600 dark:text-gray-300">Proveedor</th>
                    <th class="px-4 py-3 text-left text-xs font-semibold uppercase tracking-wide text-gray-600 dark:text-gray-300">Equipo</th>
                    <th class="px-4 py-3 text-left text-xs font-semibold uppercase tracking-wide text-gray-600 dark:text-gray-300">Empleado</th>
                    <th class="px-4 py-3 text-right text-xs font-semibold uppercase tracking-wide text-gray-600 dark:text-gray-300">Horómetro</th>
                    <th class="px-4 py-3 text-right text-xs font-semibold uppercase tracking-wide text-gray-600 dark:text-gray-300">Litros</th>
                    <th class="px-4 py-3 text-left text-xs font-semibold uppercase tracking-wide text-gray-600 dark:text-gray-300">Notas</th>
                    <th class="px-4 py-3 text-center text-xs font-semibold uppercase tracking-wide text-gray-600 dark:text-gray-300">Estado</th>
                    <th class="px-4 py-3 text-right text-xs font-semibold uppercase tracking-wide text-gray-600 dark:text-gray-300">Acciones</th>
                </tr>
                </thead>

                <tbody class="divide-y divide-gray-100 dark:divide-white/10">
                @forelse($logs as $log)
                    <tr class="hover:bg-gray-50/70 dark:hover:bg-white/5">
                        <td class="whitespace-nowrap px-4 py-3 text-sm text-gray-900 dark:text-gray-100">
                            {{ $log->date?->format('Y-m-d') }}
                        </td>

                        <td class="whitespace-nowrap px-4 py-3 text-sm text-gray-700 dark:text-gray-200">
                            {{ $log->businessUnit->name ?? '-' }}
                        </td>

                        <td class="whitespace-nowrap px-4 py-3 text-sm text-gray-700 dark:text-gray-200">
                            {{ $log->supplier->name ?? '-' }}
                        </td>

                        <td class="whitespace-nowrap px-4 py-3 text-sm text-gray-700 dark:text-gray-200">
                            {{ $log->equipment->name ?? '-' }}
                        </td>

                        <td class="whitespace-nowrap px-4 py-3 text-sm text-gray-700 dark:text-gray-200">
                            {{ $log->empleado->nombre . ' ' . $log->empleado->apellido_paterno . '' }}
                        </td>

                        <td class="whitespace-nowrap px-4 py-3 text-right text-sm text-gray-700 dark:text-gray-200">
                            {{ $log->hour_meter !== null ? number_format($log->hour_meter, 2) : '-' }}
                        </td>

                        <td class="whitespace-nowrap px-4 py-3 text-right text-sm font-semibold text-gray-900 dark:text-gray-100">
                            {{ number_format($log->liters, 2) }}
                        </td>

                        <td class="px-4 py-3 text-sm text-gray-700 dark:text-gray-200">
                            <span class="line-clamp-2">{{ $log->notes ?? '-' }}</span>
                        </td>

                        <td class="whitespace-nowrap px-4 py-3 text-center text-sm">
                            @php($active = is_null($log->deleted_at))
                            <span class="inline-flex items-center rounded-md px-2 py-1 text-xs font-medium ring-1 ring-inset
                                {{ $active
                                    ? 'bg-emerald-50 text-emerald-700 ring-emerald-600/20 dark:bg-emerald-900/30 dark:text-emerald-300 dark:ring-emerald-500/50'
                                    : 'bg-red-50 text-red-700 ring-red-600/20 dark:bg-red-900/30 dark:text-red-300 dark:ring-red-500/50' }}">
                                {{ $active ? 'Activo' : 'Inactivo' }}
                            </span>
                        </td>

                        <td class="whitespace-nowrap px-4 py-3 text-right text-sm">
                            <div class="inline-flex items-center gap-2">
                                <button
                                    wire:click="edit({{ $log->id }})"
                                    type="button"
                                    class="inline-flex items-center gap-2 rounded-md px-3 py-1.5 text-xs font-semibold
                                               text-gray-700 ring-1 ring-inset ring-gray-200 shadow-sm hover:bg-gray-50
                                               focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-indigo-600
                                               dark:text-gray-200 dark:ring-white/10 dark:hover:bg-gray-800"
                                >
                                    <svg class="h-4 w-4 fill-current text-indigo-600 dark:text-indigo-300" viewBox="0 0 20 20" aria-hidden="true">
                                        <path d="M13.586 3.586a2 2 0 112.828 2.828l-8.5 8.5a1 1 0 01-.47.263l-3 1a1 1 0 01-1.263-1.263l1-3a1 1 0 01.263-.47l8.5-8.5z"/>
                                    </svg>
                                    Editar
                                </button>

                                <button
                                    wire:click="deleteConfirmation({{ $log->id }})"
                                    type="button"
                                    class="inline-flex items-center gap-2 rounded-md px-3 py-1.5 text-xs font-semibold
                                               text-gray-700 ring-1 ring-inset ring-gray-200 shadow-sm
                                               hover:bg-red-50 hover:text-red-700
                                               focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-red-600
                                               dark:text-gray-200 dark:ring-white/10 dark:hover:bg-red-900/30 dark:hover:text-red-300"
                                >
                                    <svg class="h-4 w-4 fill-current text-red-600 dark:text-red-300" viewBox="0 0 20 20" aria-hidden="true">
                                        <path fill-rule="evenodd" d="M9 2a1 1 0 00-1 1v1H5a1 1 0 100 2h.5l.9 10.1A2 2 0 008.4 18h3.2a2 2 0 002-1.9L14.5 6H15a1 1 0 100-2h-3V3a1 1 0 00-1-1H9zm2 2H9v0h2v0z" clip-rule="evenodd"/>
                                    </svg>
                                    Eliminar
                                </button>
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="10" class="px-4 py-10 text-center text-sm text-gray-500 dark:text-gray-400">
                            No hay registros con los filtros actuales.
                        </td>
                    </tr>
                @endforelse
                </tbody>
            </table>
        </div>

        <div class="border-t border-gray-100 px-4 py-3 dark:border-white/10">
            {{ $logs->links() }}
        </div>
    </div>

    @include('livewire.modals.diesel-loads-form')
    @livewire('confirm-modal')

</div>
