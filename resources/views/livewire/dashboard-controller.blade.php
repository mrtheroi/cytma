<div class="space-y-8 px-4 sm:px-6 lg:px-8">

    {{-- Header --}}
    <div class="flex flex-col gap-3 sm:flex-row sm:items-center sm:justify-between">
        <div>
            <h2 class="text-lg font-semibold text-gray-900 dark:text-white">Dashboard</h2>
            <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">
                Resumen general del periodo seleccionado.
            </p>
        </div>

        {{-- Date Range --}}
        <div class="flex flex-wrap items-center gap-2">
            <div>
                <label class="block text-[11px] font-medium text-gray-600 dark:text-gray-300">Desde</label>
                <input
                    type="date"
                    wire:model.live="from"
                    class="mt-1 w-[160px] rounded-md border-gray-300 bg-white py-1.5 px-2 text-sm text-gray-900 shadow-sm
                           focus:border-indigo-500 focus:ring-indigo-500
                           dark:border-white/15 dark:bg-gray-900 dark:text-gray-100"
                />
            </div>

            <div>
                <label class="block text-[11px] font-medium text-gray-600 dark:text-gray-300">Hasta</label>
                <input
                    type="date"
                    wire:model.live="to"
                    class="mt-1 w-[160px] rounded-md border-gray-300 bg-white py-1.5 px-2 text-sm text-gray-900 shadow-sm
                           focus:border-indigo-500 focus:ring-indigo-500
                           dark:border-white/15 dark:bg-gray-900 dark:text-gray-100"
                />
            </div>

            <button
                type="button"
                wire:click="resetFilters"
                class="mt-5 inline-flex items-center gap-2 rounded-md px-3 py-1.5 text-xs font-semibold
           text-gray-700 ring-1 ring-inset ring-gray-200 shadow-sm hover:bg-gray-50
           focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-indigo-600
           dark:text-gray-200 dark:ring-white/10 dark:hover:bg-gray-800"
            >
                <svg xmlns="http://www.w3.org/2000/svg"
                     viewBox="0 0 640 640"
                     class="h-4 w-4 fill-current text-gray-600 dark:text-gray-300"
                     aria-hidden="true">
                    <path d="M603.3 59.3C609.5 53.1 609.5 42.9 603.3 36.7C597.1 30.5 586.9 30.5 580.7 36.7L372.7 244.7L354.1 226.1C333.9 205.9 302.7 201.5 277.8 215.4L48.4 342.9C38.3 348.5 32 359.2 32 370.8C32 379.2 35.4 387.4 41.3 393.3L246.7 598.7C252.7 604.7 260.8 608 269.3 608C280.9 608 291.6 601.7 297.2 591.6L424.6 362.2C438.5 337.2 434.1 306.1 413.9 285.9L395.3 267.3L603.3 59.3zM331.5 248.8L391.2 308.5C401.3 318.6 403.5 334.2 396.5 346.7L375.3 384.8L255.1 264.6L293.2 243.4C305.7 236.5 321.3 238.6 331.4 248.7zM226.1 280.8L359.2 413.9L269.2 576L145.9 452.7L187.3 411.3C193.5 405.1 193.5 394.9 187.3 388.7C181.1 382.5 170.9 382.5 164.7 388.7L123.3 430.1L64 370.8L226.1 280.8z"/>
                </svg>
                Reset
            </button>


        </div>
    </div>

    <div
        x-data="dashboardCombinedChart({
        labels: @js($chartLabels ?? []),
        diesel: @js($chartDiesel ?? []),
        subrasante: @js($chartSubrasante ?? []),
    })"
        x-init="init()"
        x-on:charts-updated.window="update($event.detail)"
        class="space-y-3"
    >
        <div class="flex items-end justify-between">
            <div>
                <h3 class="text-sm font-semibold text-gray-900 dark:text-white">Diésel vs Subrasante</h3>
                <p class="mt-1 text-xs text-gray-500 dark:text-gray-400">
                    Comparativo por día dentro del periodo seleccionado.
                </p>
            </div>

            <div class="flex items-center gap-2">
            <span class="inline-flex items-center rounded-md bg-blue-50 px-2 py-1 text-[11px] font-medium text-blue-700 ring-1 ring-inset ring-blue-600/20
                         dark:bg-blue-900/30 dark:text-blue-300 dark:ring-blue-500/30">
                Diésel (L)
            </span>
                <span class="inline-flex items-center rounded-md bg-pink-50 px-2 py-1 text-[11px] font-medium text-pink-700 ring-1 ring-inset ring-pink-600/20
                         dark:bg-pink-900/30 dark:text-pink-300 dark:ring-pink-500/30">
                Subrasante (m³)
            </span>
            </div>
        </div>

        <div class="rounded-xl bg-white p-4 shadow-sm ring-1 ring-black/5 dark:bg-gray-900 dark:ring-white/10">
            <div class="h-[340px]">
                <canvas x-ref="canvas"></canvas>
            </div>
        </div>
    </div>









    {{-- =========================
         Grupo 1: Personas & Proveedores
         ========================= --}}
    <div class="space-y-3">
        <div class="flex items-end justify-between">
            <div>
                <h3 class="text-sm font-semibold text-gray-900 dark:text-white">Personas & Proveedores</h3>
                <p class="mt-1 text-xs text-gray-500 dark:text-gray-400">
                    Catálogos principales para operación y nómina.
                </p>
            </div>
        </div>

        <div class="grid grid-cols-1 gap-3 sm:grid-cols-2 xl:grid-cols-2">

            {{-- Empleados --}}
            <div class="rounded-xl bg-white p-4 shadow-sm ring-1 ring-black/5 dark:bg-gray-900 dark:ring-white/10">
                <div class="flex items-start justify-between gap-3">
                    <div>
                        <p class="text-xs font-medium text-gray-500 dark:text-gray-400">Empleados</p>
                        <p class="mt-1 text-2xl font-semibold text-gray-900 dark:text-white">
                            {{ number_format($employeesCount ?? 0) }}
                        </p>
                        <p class="mt-1 text-xs text-gray-500 dark:text-gray-400">Total registrados</p>
                    </div>

                    <span class="inline-flex h-10 w-10 items-center justify-center rounded-lg
                                 bg-indigo-50 text-indigo-700 ring-1 ring-inset ring-indigo-600/20
                                 dark:bg-indigo-900/30 dark:text-indigo-300 dark:ring-indigo-500/30">
                        <svg xmlns="http://www.w3.org/2000/svg"
                             viewBox="0 0 640 640"
                             class="h-5 w-5 fill-current"
                             aria-hidden="true">
                            <path d="M304 32C288.7 32 276 42.7 272.8 57C225.5 75.8 192 122 192 176L184 176C175.2 176 168 183.2 168 192C168 200.8 175.2 208 184 208L192 208C192 278.7 249.3 336 320 336C390.7 336 448 278.7 448 208L456 208C464.8 208 472 200.8 472 192C472 183.2 464.8 176 456 176L448 176C448 122 414.5 75.8 367.2 57C364 42.7 351.2 32 336 32L304 32zM416 208C416 261 373 304 320 304C267 304 224 261 224 208L416 208zM224 176C224 140.5 243.3 109.4 272 92.8L272 112C272 120.8 279.2 128 288 128C296.8 128 304 120.8 304 112L304 64L336 64L336 112C336 120.8 343.2 128 352 128C360.8 128 368 120.8 368 112L368 92.8C396.7 109.4 416 140.4 416 176L224 176zM272 416L368 416C447.5 416 512 480.5 512 560C512 568.8 519.2 576 528 576C536.8 576 544 568.8 544 560C544 462.8 465.2 384 368 384L272 384C174.8 384 96 462.8 96 560C96 568.8 103.2 576 112 576C120.8 576 128 568.8 128 560C128 480.5 192.5 416 272 416z"/>
                        </svg>
                    </span>

                </div>
            </div>

            {{-- Proveedores --}}
            <div class="rounded-xl bg-white p-4 shadow-sm ring-1 ring-black/5 dark:bg-gray-900 dark:ring-white/10">
                <div class="flex items-start justify-between gap-3">
                    <div>
                        <p class="text-xs font-medium text-gray-500 dark:text-gray-400">Proveedores</p>
                        <p class="mt-1 text-2xl font-semibold text-gray-900 dark:text-white">
                            {{ number_format($suppliersCount ?? 0) }}
                        </p>
                        <p class="mt-1 text-xs text-gray-500 dark:text-gray-400">Total registrados</p>
                    </div>

                    <span class="inline-flex h-10 w-10 items-center justify-center rounded-lg
                                 bg-fuchsia-50 text-fuchsia-700 ring-1 ring-inset ring-fuchsia-600/20
                                 dark:bg-fuchsia-900/30 dark:text-fuchsia-300 dark:ring-fuchsia-500/30">
                        <svg xmlns="http://www.w3.org/2000/svg"
                             viewBox="0 0 640 640"
                             class="h-5 w-5 fill-current"
                             aria-hidden="true">
                            <path d="M192 64C205.3 64 216 74.7 216 88C216 101.3 205.3 112 192 112C178.7 112 168 101.3 168 88C168 74.7 178.7 64 192 64zM192 144C222.9 144 248 118.9 248 88C248 57.1 222.9 32 192 32C161.1 32 136 57.1 136 88C136 118.9 161.1 144 192 144zM192.5 176C156.9 176 128 204.9 128 240.5L128 336C128 361.2 139.9 384.9 160 400L236.8 457.6C248.9 466.7 256 480.9 256 496L256 592C256 600.8 263.2 608 272 608C280.8 608 288 600.8 288 592L288 496C288 470.8 276.1 447.1 256 432L256 303.4L256.5 304.5C269.3 333.4 298 352 329.6 352L464 352C490.5 352 512 330.5 512 304L512 208C512 181.5 490.5 160 464 160L368 160C341.5 160 320 181.5 320 208L320 304C320 309.4 320.9 314.6 322.6 319.5C306.5 317.1 292.5 306.7 285.8 291.5L251.5 214.3C241.1 191 218 176 192.5 176zM368 320C359.2 320 352 312.8 352 304L352 208C352 199.2 359.2 192 368 192L464 192C472.8 192 480 199.2 480 208L480 304C480 312.8 472.8 320 464 320L368 320zM128 592C128 600.8 135.2 608 144 608C152.8 608 160 600.8 160 592L160 460L131.2 438.4C130.1 437.6 129.1 436.8 128 435.9L128 592zM224 240L224 408L179.2 374.4C167.1 365.3 160 351.1 160 336L160 240C160 222.3 174.3 208 192 208C209.7 208 224 222.3 224 240z"/>
                        </svg>
                    </span>

                </div>
            </div>

        </div>
    </div>

    {{-- =========================
         Grupo 2: Operación (Maquinaria & Equipos)
         ========================= --}}
    <div class="space-y-3">
        <div>
            <h3 class="text-sm font-semibold text-gray-900 dark:text-white">Maquinaria & Equipos</h3>
            <p class="mt-1 text-xs text-gray-500 dark:text-gray-400">
                Activos y equipos disponibles en operación.
            </p>
        </div>

        <div class="grid grid-cols-1 gap-3 sm:grid-cols-2 xl:grid-cols-2">
            {{-- Maquinarias --}}
            <div class="rounded-xl bg-white p-4 shadow-sm ring-1 ring-black/5 dark:bg-gray-900 dark:ring-white/10">
                <div class="flex items-start justify-between gap-3">
                    <div>
                        <p class="text-xs font-medium text-gray-500 dark:text-gray-400">Maquinarias</p>
                        <p class="mt-1 text-2xl font-semibold text-gray-900 dark:text-white">
                            {{ number_format($equipmentCount ?? 0) }}
                        </p>
                        <p class="mt-1 text-xs text-gray-500 dark:text-gray-400">Total registradas</p>
                    </div>

                    <span class="inline-flex h-10 w-10 items-center justify-center rounded-lg
                                 bg-amber-50 text-amber-700 ring-1 ring-inset ring-amber-600/20
                                 dark:bg-amber-900/30 dark:text-amber-300 dark:ring-amber-500/30">
                        <svg xmlns="http://www.w3.org/2000/svg"
                             viewBox="0 0 640 640"
                             class="h-5 w-5 fill-current"
                             aria-hidden="true">
                            <path d="M16 96C7.2 96 0 103.2 0 112C0 120.8 7.2 128 16 128L32 128L32 224L16 224C7.2 224 0 231.2 0 240C0 248.8 7.2 256 16 256L336 256C344.8 256 352 248.8 352 240C352 231.2 344.8 224 336 224L304 224L304 128L336 128C344.8 128 352 120.8 352 112C352 103.2 344.8 96 336 96L16 96zM272 128L272 224L224 224L224 128L272 128zM192 128L192 224L144 224L144 128L192 128zM112 128L112 224L64 224L64 128L112 128zM384 336L576 336L576 416C576 433.7 561.7 448 544 448L543.6 448C539.6 403.1 501.9 368 456 368C410.1 368 372.4 403.1 368.4 448L271.7 448C267.7 403.1 230 368 184.1 368C138.2 368 100.5 403.1 96.5 448L80 448C71.2 448 64 440.8 64 432L64 352C64 343.2 71.2 336 80 336L384 336zM384 304L80 304C53.5 304 32 325.5 32 352L32 432C32 458.5 53.5 480 80 480L99.3 480C109.7 516.9 143.7 544 184 544C224.3 544 258.2 516.9 268.7 480L371.3 480C381.7 516.9 415.7 544 456 544C496.3 544 530.2 516.9 540.7 480L544 480C579.3 480 608 451.3 608 416L608 320.6C608 309.2 605 298.5 599.9 289.5L546.2 192.9C535 172.6 513.6 160 490.3 160L416 160C398.3 160 384 174.3 384 192L384 304zM416 304L416 192L490.3 192C501.9 192 512.6 198.3 518.3 208.5L571.4 304L416 304zM128 456C128 425.1 153.1 400 184 400C214.9 400 240 425.1 240 456C240 486.9 214.9 512 184 512C153.1 512 128 486.9 128 456zM456 400C486.9 400 512 425.1 512 456C512 486.9 486.9 512 456 512C425.1 512 400 486.9 400 456C400 425.1 425.1 400 456 400z"/>
                        </svg>
                    </span>

                </div>
            </div>

            {{-- Camiones --}}
            <div class="rounded-xl bg-white p-4 shadow-sm ring-1 ring-black/5 dark:bg-gray-900 dark:ring-white/10">
                <div class="flex items-start justify-between gap-3">
                    <div>
                        <p class="text-xs font-medium text-gray-500 dark:text-gray-400">Camiones</p>
                        <p class="mt-1 text-2xl font-semibold text-gray-900 dark:text-white">
                            {{ number_format($trucksCount ?? 0) }}
                        </p>
                        <p class="mt-1 text-xs text-gray-500 dark:text-gray-400">Total registrados</p>
                    </div>

                    <span class="inline-flex h-10 w-10 items-center justify-center rounded-lg
                                 bg-cyan-50 text-cyan-700 ring-1 ring-inset ring-cyan-600/20
                                 dark:bg-cyan-900/30 dark:text-cyan-300 dark:ring-cyan-500/30">
                        <svg xmlns="http://www.w3.org/2000/svg"
                             viewBox="0 0 640 640"
                             class="h-5 w-5 fill-current"
                             aria-hidden="true">
                            <path d="M285.9 73.6C294.2 67.4 304.3 64 314.7 64L592 64C600.8 64 608 71.2 608 80C608 88.8 600.8 96 592 96L544 96L544 192L592 192C600.8 192 608 199.2 608 208C608 216.8 600.8 224 592 224L336 224L336 512C336 520.8 328.8 528 320 528L268.7 528C258.3 564.9 224.3 592 184 592C143.7 592 109.8 564.9 99.3 528L80 528C53.5 528 32 506.5 32 480L32 288C32 272.9 39.1 258.7 51.2 249.6L285.9 73.6zM271.6 496L304 496L304 240L73.6 412.8L64 420L64 480C64 488.8 71.2 496 80 496L96.4 496C100.4 451.1 138.1 416 184 416C229.9 416 267.6 451.1 271.6 496zM336 192L512 192L512 96L336 96L336 192zM304 100L70.4 275.2C66.4 278.2 64 283 64 288L64 380L301.9 201.6C302.6 201.1 303.3 200.6 304 200.1L304 100zM240 504C240 473.1 214.9 448 184 448C153.1 448 128 473.1 128 504C128 534.9 153.1 560 184 560C214.9 560 240 534.9 240 504zM501.5 304L416 304L416 436.1C431.2 423.5 450.7 416 472 416C516.6 416 553.4 449.2 559.2 492.2C569.2 486.8 576 476.2 576 464L576 378.5C576 370 572.6 361.9 566.6 355.9L524.1 313.4C518.1 307.4 510 304 501.5 304zM384 448L384 304C384 286.3 398.3 272 416 272L501.5 272C518.5 272 534.8 278.7 546.8 290.7L589.3 333.2C601.3 345.2 608 361.5 608 378.5L608 464C608 494.9 586.1 520.6 557.1 526.7C547.1 564.3 512.8 592 472 592C423.4 592 384 552.6 384 504L384 448zM472 560C502.9 560 528 534.9 528 504C528 473.1 502.9 448 472 448C441.1 448 416 473.1 416 504C416 534.9 441.1 560 472 560z"/>
                        </svg>
                    </span>

                </div>
            </div>

            {{-- Aquí luego podemos agregar: Equipos Activos, Equipos en mantenimiento, etc. --}}
        </div>
    </div>

    {{-- =========================
         Grupo 3: Bitácoras del periodo
         ========================= --}}
    <div class="space-y-3">
        <div>
            <h3 class="text-sm font-semibold text-gray-900 dark:text-white">Bitácoras del periodo</h3>
            <p class="mt-1 text-xs text-gray-500 dark:text-gray-400">
                Resumen de consumo y entregas según el rango seleccionado.
            </p>
        </div>

        <div class="grid grid-cols-1 gap-3 sm:grid-cols-2 xl:grid-cols-3">
            {{-- Diésel --}}
            <div class="rounded-xl bg-white p-4 shadow-sm ring-1 ring-black/5 dark:bg-gray-900 dark:ring-white/10">
                <div class="flex items-start justify-between gap-3">
                    <div>
                        <p class="text-xs font-medium text-gray-500 dark:text-gray-400">Diésel (periodo)</p>
                        <p class="mt-1 text-2xl font-semibold text-gray-900 dark:text-white">
                            {{ number_format($dieselTotalLiters ?? 0, 2) }}
                        </p>
                        <p class="mt-1 text-xs text-gray-500 dark:text-gray-400">
                            Litros • {{ number_format($dieselLoadsCount ?? 0) }} cargas
                        </p>
                    </div>

                    <span class="inline-flex h-10 w-10 items-center justify-center rounded-lg
                                 bg-emerald-50 text-emerald-700 ring-1 ring-inset ring-emerald-600/20
                                 dark:bg-emerald-900/30 dark:text-emerald-300 dark:ring-emerald-500/30">
                        <svg xmlns="http://www.w3.org/2000/svg"
                             viewBox="0 0 640 640"
                             class="h-5 w-5 fill-current"
                             aria-hidden="true">
                            <path d="M320 96C337.7 96 352 110.3 352 128L352 256L128 256L128 128C128 110.3 142.3 96 160 96L320 96zM352 288L352 544L128 544L128 288L352 288zM96 128L96 544C87.2 544 80 551.2 80 560C80 568.8 87.2 576 96 576L384 576C392.8 576 400 568.8 400 560C400 551.2 392.8 544 384 544L384 384L400 384C426.5 384 448 405.5 448 432L448 448C448 483.3 476.7 512 512 512C547.3 512 576 483.3 576 448L576 221.1C576 203.2 568.5 186 555.2 173.9L474.8 100.2C468.3 94.2 458.2 94.7 452.2 101.2C446.2 107.7 446.7 117.8 453.2 123.8L480 148.4L480 224C480 259.3 508.7 288 544 288L544 448C544 465.7 529.7 480 512 480C494.3 480 480 465.7 480 448L480 432C480 387.8 444.2 352 400 352L384 352L384 128C384 92.7 355.3 64 320 64L160 64C124.7 64 96 92.7 96 128zM544 256C526.3 256 512 241.7 512 224L512 177.7L533.6 197.5C540.2 203.6 544 212.1 544 221.1L544 256z"/>
                        </svg>
                    </span>

                </div>
            </div>

            {{-- Subrasante --}}
            <div class="rounded-xl bg-white p-4 shadow-sm ring-1 ring-black/5 dark:bg-gray-900 dark:ring-white/10">
                <div class="flex items-start justify-between gap-3">
                    <div>
                        <p class="text-xs font-medium text-gray-500 dark:text-gray-400">Subrasante (periodo)</p>
                        <p class="mt-1 text-2xl font-semibold text-gray-900 dark:text-white">
                            {{ number_format($subrasanteTotalM3 ?? 0, 2) }}
                        </p>
                        <p class="mt-1 text-xs text-gray-500 dark:text-gray-400">
                            m³ • {{ number_format($subrasanteTrips ?? 0) }} viajes
                        </p>
                    </div>

                    <span class="inline-flex h-10 w-10 items-center justify-center rounded-lg
                                 bg-purple-50 text-purple-700 ring-1 ring-inset ring-purple-600/20
                                 dark:bg-purple-900/30 dark:text-purple-300 dark:ring-purple-500/30">
                        <svg xmlns="http://www.w3.org/2000/svg"
                             viewBox="0 0 640 640"
                             class="h-5 w-5 fill-current"
                             aria-hidden="true">
                            <path d="M544.9 448L95.5 448L204.1 259.2C228 217.6 272.3 192 320.2 192C368.1 192 412.4 217.6 436.3 259.2L544.9 448zM320.2 160C260.8 160 206 191.7 176.3 243.2L67.8 432C55.5 453.3 70.9 480 95.5 480L544.9 480C569.5 480 584.9 453.4 572.6 432L464.1 243.2C434.5 191.7 379.6 160 320.2 160z"/>
                        </svg>
                    </span>

                </div>
            </div>

            {{-- Placeholder: producto terminado (cuando lo integremos) --}}
            <div class="rounded-xl border border-dashed border-gray-200 bg-white p-4 text-sm text-gray-500
                        dark:border-white/10 dark:bg-gray-900 dark:text-gray-400">
                Próximamente: Producto terminado (card + gráfica).
            </div>
        </div>
    </div>

</div>


@push('scripts')
    <script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.1/dist/chart.umd.min.js"></script>

    <script>
        function dashboardCombinedChart(initial) {
            return {
                labels: initial.labels ?? [],
                diesel: initial.diesel ?? [],
                subrasante: initial.subrasante ?? [],
                chart: null,

                init() {
                    const ctx = this.$refs.canvas.getContext('2d');

                    this.chart = new Chart(ctx, {
                        type: 'line',
                        data: {
                            labels: this.labels,
                            datasets: [
                                {
                                    label: 'Diésel (L)',
                                    data: this.diesel,
                                    borderColor: '#2563eb',
                                    backgroundColor: 'rgba(37, 99, 235, 0.10)',
                                    tension: 0.35,
                                    fill: false,
                                    pointRadius: 2,
                                    pointHoverRadius: 4,
                                    borderWidth: 2,
                                    yAxisID: 'y',
                                },
                                {
                                    label: 'Subrasante (m³)',
                                    data: this.subrasante,
                                    borderColor: '#db2777',
                                    backgroundColor: 'rgba(219, 39, 119, 0.10)',
                                    tension: 0.35,
                                    fill: false,
                                    pointRadius: 2,
                                    pointHoverRadius: 4,
                                    borderWidth: 2,
                                    yAxisID: 'y1',
                                },
                            ]
                        },
                        options: {
                            responsive: true,
                            maintainAspectRatio: false,
                            interaction: { mode: 'index', intersect: false },
                            plugins: { legend: { display: true, position: 'bottom' } },
                            scales: {
                                x: { grid: { display: false }, ticks: { maxRotation: 0 } },
                                y:  { beginAtZero: true, title: { display: true, text: 'Diésel (L)' } },
                                y1: { beginAtZero: true, position: 'right', grid: { drawOnChartArea: false }, title: { display: true, text: 'Subrasante (m³)' } }
                            }
                        }
                    });
                },

                update(detail) {
                    if (!this.chart) return;
                    this.chart.data.labels = detail.labels ?? [];
                    this.chart.data.datasets[0].data = detail.diesel ?? [];
                    this.chart.data.datasets[1].data = detail.subrasante ?? [];
                    this.chart.update();
                }
            }
        }
    </script>
@endpush
