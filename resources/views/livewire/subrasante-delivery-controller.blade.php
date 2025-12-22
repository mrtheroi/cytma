{{-- resources/views/livewire/subrasante-deliveries-controller.blade.php --}}
<div class="space-y-6">

    {{-- Encabezado --}}
    <div class="flex flex-col gap-3 sm:flex-row sm:items-center sm:justify-between">
        <div>
            <h2 class="text-lg font-semibold text-gray-900 dark:text-white">Bitácora de Subrasante</h2>
            <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">
                Detalle y resumen (los viajes se calculan agrupando registros).
            </p>
        </div>
    </div>

    {{-- Tarjetas KPI (3) --}}
    <div class="grid grid-cols-1 gap-3 sm:grid-cols-2 lg:grid-cols-3">
        {{-- Total m3 --}}
        <div class="rounded-xl bg-white p-4 shadow-sm ring-1 ring-black/5 dark:bg-gray-900 dark:ring-white/10">
            <div class="flex items-start justify-between gap-3">
                <div>
                    <p class="text-xs font-medium text-gray-500 dark:text-gray-400">Total entregado (m³)</p>
                    <p class="mt-1 text-2xl font-semibold text-gray-900 dark:text-white">
                        {{ number_format($totalM3 ?? 0, 2) }}
                    </p>
                    <p class="mt-1 text-xs text-gray-500 dark:text-gray-400">Con base en los filtros actuales</p>
                </div>

                <span class="inline-flex h-10 w-10 items-center justify-center rounded-lg
                     bg-emerald-50 text-emerald-700 ring-1 ring-inset ring-emerald-600/20
                     dark:bg-emerald-900/30 dark:text-emerald-300 dark:ring-emerald-500/30">
            <svg xmlns="http://www.w3.org/2000/svg"
                 viewBox="0 0 640 640"
                 class="h-5 w-5 fill-current"
                 aria-hidden="true">
                <path d="M368.4 96L352.4 96L352.4 452.1L43.8 544.7L28.5 549.3L37.7 580L53 575.4L370.8 480.1L432.8 480.1C432.6 482.7 432.4 485.4 432.4 488.1C432.4 536.7 471.8 576.1 520.4 576.1C569 576.1 608.4 536.7 608.4 488.1C608.4 439.5 569 400.1 520.4 400.1C486.2 400.1 456.6 419.6 442 448.1L384.4 448.1L384.4 128.1L608.4 128.1L608.4 96.1L368.4 96.1zM464.4 488C464.4 457.1 489.5 432 520.4 432C551.3 432 576.4 457.1 576.4 488C576.4 518.9 551.3 544 520.4 544C489.5 544 464.4 518.9 464.4 488zM103.4 240.4C69.6 250.7 50.6 286.5 60.9 320.3C71.8 356 82.7 391.7 93.6 427.4C95.9 435.1 100.6 450.4 107.6 473.3L138.2 463.9L304.4 413.1L304.4 379.6L128.9 433.3C127.7 429.5 120.7 406.5 107.8 364.4C103.9 351.7 111.1 338.3 123.7 334.4C136.3 330.5 149.8 337.7 153.7 350.3C154.9 354.1 157.2 361.8 160.7 373.3L176 368.6L304.4 329.4L304.4 295.9L304.4 295.9L181.5 333.5C169.9 308.7 141.4 295.6 114.5 303.8C106.3 306.3 99.1 310.5 93.1 316L91.5 310.8C86.3 293.9 95.8 276 112.7 270.8L304.4 212.2L304.4 178.7L103.3 240.2z"/>
            </svg>
        </span>
            </div>
        </div>



        {{-- Viajes --}}
        <div class="rounded-xl bg-white p-4 shadow-sm ring-1 ring-black/5 dark:bg-gray-900 dark:ring-white/10">
            <div class="flex items-start justify-between gap-3">
                <div>
                    <p class="text-xs font-medium text-gray-500 dark:text-gray-400">Viajes (calculado)</p>
                    <p class="mt-1 text-2xl font-semibold text-gray-900 dark:text-white">
                        {{ number_format($totalTrips ?? 0) }}
                    </p>
                    <p class="mt-1 text-xs text-gray-500 dark:text-gray-400">COUNT(*) de registros</p>
                </div>



                <span class="inline-flex h-10 w-10 items-center justify-center rounded-lg
                     bg-indigo-50 text-indigo-700 ring-1 ring-inset ring-indigo-600/20
                     dark:bg-indigo-900/30 dark:text-indigo-300 dark:ring-indigo-500/30">
                    <svg xmlns="http://www.w3.org/2000/svg"
                         viewBox="0 0 640 640"
                         class="h-5 w-5 fill-current"
                         aria-hidden="true">
                    <path d="M56 64C25.1 64 0 89.1 0 120L0 296C0 326.9 25.1 352 56 352L328 352C358.9 352 384 326.9 384 296L384 120C384 89.1 358.9 64 328 64L56 64zM32 120C32 106.7 42.7 96 56 96L328 96C341.3 96 352 106.7 352 120L352 296C352 309.3 341.3 320 328 320L56 320C42.7 320 32 309.3 32 296L32 120zM96 128C87.2 128 80 135.2 80 144L80 272C80 280.8 87.2 288 96 288C104.8 288 112 280.8 112 272L112 144C112 135.2 104.8 128 96 128zM192 128C183.2 128 176 135.2 176 144L176 272C176 280.8 183.2 288 192 288C200.8 288 208 280.8 208 272L208 144C208 135.2 200.8 128 192 128zM288 128C279.2 128 272 135.2 272 144L272 272C272 280.8 279.2 288 288 288C296.8 288 304 280.8 304 272L304 144C304 135.2 296.8 128 288 128zM464 192C455.2 192 448 199.2 448 208L448 420.1C430.4 434.6 418.5 455.9 416.4 480L319.7 480C315.7 435.1 278 400 232.1 400C202.3 400 176 414.8 160.1 437.4C144.2 414.8 117.9 400 88.1 400C39.5 400 .1 439.4 .1 488C.1 536.6 39.5 576 88.1 576C117.9 576 144.2 561.2 160.1 538.6C176 561.2 202.3 576 232.1 576C272.4 576 306.3 548.9 316.8 512L419.4 512C429.8 548.9 463.8 576 504.1 576C544.9 576 579.2 548.3 589.2 510.7C618.3 504.7 640.1 478.9 640.1 448L640.1 291.9C640.1 274.9 633.4 258.6 621.4 246.6L585.5 210.7C573.5 198.7 557.2 192 540.2 192L464 192zM504 432C534.9 432 560 457.1 560 488C560 518.9 534.9 544 504 544C473.1 544 448 518.9 448 488C448 457.1 473.1 432 504 432zM591.2 476.2C585.4 433.2 548.6 400 504 400C495.7 400 487.6 401.2 480 403.3L480 352L608 352L608 448C608 460.2 601.2 470.7 591.2 476.2zM480 320L480 224L540.1 224C548.6 224 556.7 227.4 562.7 233.4L598.6 269.3C604.6 275.3 608 283.4 608 291.9L608 320L480 320zM32 488C32 457.1 57.1 432 88 432C118.9 432 144 457.1 144 488C144 518.9 118.9 544 88 544C57.1 544 32 518.9 32 488zM288 488C288 518.9 262.9 544 232 544C201.1 544 176 518.9 176 488C176 457.1 201.1 432 232 432C262.9 432 288 457.1 288 488z"/>
                </svg>

        </span>
            </div>
        </div>


        {{-- Promedio --}}
        <div class="rounded-xl bg-white p-4 shadow-sm ring-1 ring-black/5 dark:bg-gray-900 dark:ring-white/10">
            <div class="flex items-start justify-between gap-3">
                <div>
                    <p class="text-xs font-medium text-gray-500 dark:text-gray-400">Promedio m³ / viaje</p>
                    <p class="mt-1 text-2xl font-semibold text-gray-900 dark:text-white">
                        {{ number_format($avgM3PerTrip ?? 0, 2) }}
                    </p>
                    <p class="mt-1 text-xs text-gray-500 dark:text-gray-400">Total m³ / viajes</p>
                </div>

                <span class="inline-flex h-10 w-10 items-center justify-center rounded-lg
                             bg-amber-50 text-amber-700 ring-1 ring-inset ring-amber-600/20
                             dark:bg-amber-900/30 dark:text-amber-300 dark:ring-amber-500/30">
                    <svg class="h-5 w-5 fill-current" viewBox="0 0 20 20" aria-hidden="true">
                        <path d="M3 3h2v14H3V3zm6 6h2v8H9V9zm6-4h2v12h-2V5z"/>
                    </svg>
                </span>
            </div>
        </div>
    </div>

    {{-- Filtros --}}
    <div class="rounded-xl bg-white p-4 shadow-sm ring-1 ring-black/5 dark:bg-gray-900 dark:ring-white/10">
        <div class="grid grid-cols-1 gap-3 lg:grid-cols-12">
            {{-- Búsqueda --}}
            <div class="lg:col-span-4">
                <label class="block text-xs font-medium text-gray-700 dark:text-gray-200">Buscar</label>
                <input
                    wire:model.live.debounce.350ms="search"
                    type="text"
                    placeholder="Remisión, material, cliente, placas…"
                    class="mt-1 block w-full rounded-md border-gray-300 bg-white py-2 px-3 text-sm text-gray-900 shadow-sm
                           focus:border-indigo-500 focus:ring-indigo-500
                           dark:border-white/15 dark:bg-gray-900 dark:text-gray-100"
                />
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
                />
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
                />
            </div>

            {{-- Cliente --}}
            <div class="lg:col-span-2">
                <label class="block text-xs font-medium text-gray-700 dark:text-gray-200">Cliente</label>
                <select
                    wire:model.live="customer_id_filter"
                    class="mt-1 block w-full rounded-md border-gray-300 bg-white py-2 px-3 text-sm text-gray-900 shadow-sm
                           focus:border-indigo-500 focus:ring-indigo-500
                           dark:border-white/15 dark:bg-gray-900 dark:text-gray-100"
                >
                    <option value="">Todos</option>
                    @foreach($customers as $c)
                        <option value="{{ $c->id }}">{{ $c->name }}</option>
                    @endforeach
                </select>
            </div>

            {{-- Camión --}}
            <div class="lg:col-span-2">
                <label class="block text-xs font-medium text-gray-700 dark:text-gray-200">Camión</label>
                <select
                    wire:model.live="truck_id_filter"
                    class="mt-1 block w-full rounded-md border-gray-300 bg-white py-2 px-3 text-sm text-gray-900 shadow-sm
                           focus:border-indigo-500 focus:ring-indigo-500
                           dark:border-white/15 dark:bg-gray-900 dark:text-gray-100"
                >
                    <option value="">Todos</option>
                    @foreach($trucks as $t)
                        <option value="{{ $t->id }}">{{ $t->license_plate }} ({{ $t->capacity }} m³)</option>
                    @endforeach
                </select>
            </div>

            {{-- Limpiar --}}
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
    {{-- Tabla Detalle --}}
    <div class="relative overflow-hidden rounded-xl bg-white shadow-sm ring-1 ring-black/5 dark:bg-gray-900 dark:ring-white/10">
        <div class="flex items-center justify-between px-4 py-3 border-b border-gray-100 dark:border-white/10">
            <div>
                <h3 class="text-sm font-semibold text-gray-900 dark:text-white">Detalle</h3>
                <p class="text-xs text-gray-500 dark:text-gray-400">Cada fila = un viaje</p>
            </div>
        </div>

        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-100 dark:divide-white/10">
                <thead class="bg-gray-50 dark:bg-gray-950/40">
                <tr>
                    <th class="px-4 py-3 text-left text-xs font-semibold uppercase tracking-wide text-gray-600 dark:text-gray-300">Fecha</th>
                    <th class="px-4 py-3 text-left text-xs font-semibold uppercase tracking-wide text-gray-600 dark:text-gray-300">Remisión</th>
                    <th class="px-4 py-3 text-left text-xs font-semibold uppercase tracking-wide text-gray-600 dark:text-gray-300">Cliente</th>
                    <th class="px-4 py-3 text-left text-xs font-semibold uppercase tracking-wide text-gray-600 dark:text-gray-300">Placas</th>
                    <th class="px-4 py-3 text-right text-xs font-semibold uppercase tracking-wide text-gray-600 dark:text-gray-300">m³ camión</th>
                    <th class="px-4 py-3 text-left text-xs font-semibold uppercase tracking-wide text-gray-600 dark:text-gray-300">Material</th>
                    <th class="px-4 py-3 text-center text-xs font-semibold uppercase tracking-wide text-gray-600 dark:text-gray-300">Estado</th>
                    <th class="px-4 py-3 text-right text-xs font-semibold uppercase tracking-wide text-gray-600 dark:text-gray-300">Acciones</th>
                </tr>
                </thead>

                <tbody class="divide-y divide-gray-100 dark:divide-white/10">
                @forelse($deliveries as $row)
                    <tr class="hover:bg-gray-50/70 dark:hover:bg-white/5">
                        <td class="whitespace-nowrap px-4 py-3 text-sm text-gray-900 dark:text-gray-100">
                            {{ $row->date?->format('Y-m-d') }}
                        </td>

                        <td class="whitespace-nowrap px-4 py-3 text-sm text-gray-700 dark:text-gray-200">
                            {{ $row->delivery_note }}
                        </td>

                        <td class="px-4 py-3 text-sm text-gray-700 dark:text-gray-200">
                            {{ $row->customer->name ?? '-' }}
                        </td>

                        <td class="whitespace-nowrap px-4 py-3 text-sm text-gray-700 dark:text-gray-200">
                            {{ $row->truck->license_plate ?? '-' }}
                        </td>

                        <td class="whitespace-nowrap px-4 py-3 text-right text-sm font-semibold text-gray-900 dark:text-gray-100">
                            {{ number_format((float)($row->truck->capacity ?? 0), 2) }}
                        </td>

                        <td class="px-4 py-3 text-sm text-gray-700 dark:text-gray-200">
                            {{ $row->material_description }}
                        </td>

                        <td class="whitespace-nowrap px-4 py-3 text-center text-sm">
                            @php($active = is_null($row->deleted_at))
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
                                    wire:click="edit({{ $row->id }})"
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
                                    wire:click="deleteConfirmation({{ $row->id }})"
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
                        <td colspan="8" class="px-4 py-10 text-center text-sm text-gray-500 dark:text-gray-400">
                            No hay registros.
                        </td>
                    </tr>
                @endforelse
                </tbody>
            </table>
        </div>

        <div class="border-t border-gray-100 px-4 py-3 dark:border-white/10">
            {{ $deliveries->links() }}
        </div>
    </div>

    {{-- Resumen por Camión --}}
    <div class="overflow-hidden rounded-xl bg-white shadow-sm ring-1 ring-black/5 dark:bg-gray-900 dark:ring-white/10">
        <div class="flex items-center justify-between px-4 py-3 border-b border-gray-100 dark:border-white/10">
            <div>
                <h3 class="text-sm font-semibold text-gray-900 dark:text-white">Resumen por camión</h3>
                <p class="text-xs text-gray-500 dark:text-gray-400">Viajes = COUNT(*), Total m³ = SUM(capacidad camión)</p>
            </div>
        </div>

        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-100 dark:divide-white/10">
                <thead class="bg-gray-50 dark:bg-gray-950/40">
                <tr>
                    <th class="px-4 py-3 text-left text-xs font-semibold uppercase tracking-wide text-gray-600 dark:text-gray-300">Placas</th>
                    <th class="px-4 py-3 text-left text-xs font-semibold uppercase tracking-wide text-gray-600 dark:text-gray-300">Tipo</th>
                    <th class="px-4 py-3 text-right text-xs font-semibold uppercase tracking-wide text-gray-600 dark:text-gray-300">Capacidad (m³)</th>
                    <th class="px-4 py-3 text-right text-xs font-semibold uppercase tracking-wide text-gray-600 dark:text-gray-300">Viajes</th>
                    <th class="px-4 py-3 text-right text-xs font-semibold uppercase tracking-wide text-gray-600 dark:text-gray-300">Total (m³)</th>
                </tr>
                </thead>

                <tbody class="divide-y divide-gray-100 dark:divide-white/10">
                @forelse($summaryByTruck as $s)
                    <tr class="hover:bg-gray-50/70 dark:hover:bg-white/5">
                        <td class="whitespace-nowrap px-4 py-3 text-sm text-gray-900 dark:text-gray-100">
                            {{ $s->license_plate }}
                        </td>
                        <td class="whitespace-nowrap px-4 py-3 text-sm text-gray-700 dark:text-gray-200">
                            {{ $s->type ?? '-' }}
                        </td>
                        <td class="whitespace-nowrap px-4 py-3 text-right text-sm text-gray-700 dark:text-gray-200">
                            {{ number_format((float)$s->capacity, 2) }}
                        </td>
                        <td class="whitespace-nowrap px-4 py-3 text-right text-sm font-semibold text-gray-900 dark:text-gray-100">
                            {{ number_format((int)$s->trips) }}
                        </td>
                        <td class="whitespace-nowrap px-4 py-3 text-right text-sm font-semibold text-gray-900 dark:text-gray-100">
                            {{ number_format((float)$s->total_m3, 2) }}
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="5" class="px-4 py-10 text-center text-sm text-gray-500 dark:text-gray-400">
                            No hay datos de resumen.
                        </td>
                    </tr>
                @endforelse
                </tbody>
            </table>
        </div>
    </div>

    {{-- MODAL: Crear / Editar (mismo estilo que Diésel) --}}
    @if($open)
        <div class="fixed inset-0 z-50 flex items-center justify-center bg-black/40 backdrop-blur-sm">
            <div class="w-full max-w-3xl rounded-xl bg-white shadow-xl ring-1 ring-black/10 dark:bg-gray-900 dark:ring-white/10">
                {{-- Header --}}
                <div class="flex items-start justify-between border-b border-gray-100 px-6 py-4 dark:border-white/10">
                    <div>
                        <h3 class="text-sm font-semibold text-gray-900 dark:text-white">
                            {{ $deliveryId ? 'Editar entrega' : 'Nueva entrega' }}
                        </h3>
                        <p class="mt-1 text-xs text-gray-500 dark:text-gray-400">
                            Registra una entrega (un viaje).
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
                    <div class="grid grid-cols-1 gap-4 md:grid-cols-2">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-200">Fecha</label>
                            <input
                                type="date"
                                wire:model.defer="date"
                                class="mt-1 block w-full rounded-md border-gray-300 bg-white py-2 px-3 text-sm text-gray-900 shadow-sm
                                       focus:border-indigo-500 focus:ring-indigo-500
                                       dark:border-white/15 dark:bg-gray-900 dark:text-gray-100"
                            />
                            @error('date') <p class="mt-1 text-xs text-red-600">{{ $message }}</p> @enderror
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-200">Remisión</label>
                            <input
                                type="text"
                                wire:model.defer="delivery_note"
                                placeholder="Ej. 12345"
                                class="mt-1 block w-full rounded-md border-gray-300 bg-white py-2 px-3 text-sm text-gray-900 shadow-sm
                                       focus:border-indigo-500 focus:ring-indigo-500
                                       dark:border-white/15 dark:bg-gray-900 dark:text-gray-100"
                            />
                            @error('delivery_note') <p class="mt-1 text-xs text-red-600">{{ $message }}</p> @enderror
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-200">Cliente</label>
                            <select
                                wire:model.defer="customer_id"
                                class="mt-1 block w-full rounded-md border-gray-300 bg-white py-2 px-3 text-sm text-gray-900 shadow-sm
                                       focus:border-indigo-500 focus:ring-indigo-500
                                       dark:border-white/15 dark:bg-gray-900 dark:text-gray-100"
                            >
                                <option value="">Selecciona</option>
                                @foreach($customers as $c)
                                    <option value="{{ $c->id }}">{{ $c->name }}</option>
                                @endforeach
                            </select>
                            @error('customer_id') <p class="mt-1 text-xs text-red-600">{{ $message }}</p> @enderror
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-200">Camión</label>
                            <select
                                wire:model.defer="truck_id"
                                class="mt-1 block w-full rounded-md border-gray-300 bg-white py-2 px-3 text-sm text-gray-900 shadow-sm
                                       focus:border-indigo-500 focus:ring-indigo-500
                                       dark:border-white/15 dark:bg-gray-900 dark:text-gray-100"
                            >
                                <option value="">Selecciona</option>
                                @foreach($trucks as $t)
                                    <option value="{{ $t->id }}">{{ $t->license_plate }} ({{ $t->capacity }} m³)</option>
                                @endforeach
                            </select>
                            @error('truck_id') <p class="mt-1 text-xs text-red-600">{{ $message }}</p> @enderror
                        </div>

                        <div class="md:col-span-2">
                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-200">Descripción del material</label>
                            <input
                                type="text"
                                wire:model.defer="material_description"
                                placeholder="Ej. Subrasante"
                                class="mt-1 block w-full rounded-md border-gray-300 bg-white py-2 px-3 text-sm text-gray-900 shadow-sm
                                       focus:border-indigo-500 focus:ring-indigo-500
                                       dark:border-white/15 dark:bg-gray-900 dark:text-gray-100"
                            />
                            @error('material_description') <p class="mt-1 text-xs text-red-600">{{ $message }}</p> @enderror
                        </div>

                        <div class="md:col-span-2">
                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-200">Notas (opcional)</label>
                            <textarea
                                rows="3"
                                wire:model.defer="notes"
                                class="mt-1 block w-full rounded-md border-gray-300 bg-white py-2 px-3 text-sm text-gray-900 shadow-sm
                                       focus:border-indigo-500 focus:ring-indigo-500
                                       dark:border-white/15 dark:bg-gray-900 dark:text-gray-100"
                            ></textarea>
                            @error('notes') <p class="mt-1 text-xs text-red-600">{{ $message }}</p> @enderror
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
                                <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8v4a4 4 0 0 0-4 4H4z"></path>
                            </svg>

                            <span>{{ $deliveryId ? 'Guardar cambios' : 'Crear entrega' }}</span>
                        </button>
                    </div>
                </form>
            </div>
        </div>
    @endif

</div>
