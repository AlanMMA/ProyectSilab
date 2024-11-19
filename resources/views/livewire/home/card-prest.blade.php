<div class="bg-white dark:bg-gray-800 relative " 
    x-data="{ open: false, days: 7 }">
    <div class="flex flex-col text-black dark:text-white text-lg font-semibold mb-3 sm:w-sm">
        <div class="flex justify-between">
            <p class="text-center text-xl pb-2 flex-1">Préstamos cercanos a vencer.</p>
            <svg xmlns="http://www.w3.org/2000/svg" height="24px" viewBox="0 -960 960 960" width="24px"
                class="ml-2 fill-black dark:fill-white cursor-pointer" @click="open = !open" <!-- Activador del menú -->
                >
                <path
                    d="m370-80-16-128q-13-5-24.5-12T307-235l-119 50L78-375l103-78q-1-7-1-13.5v-27q0-6.5 1-13.5L78-585l110-190 119 50q11-8 23-15t24-12l16-128h220l16 128q13 5 24.5 12t22.5 15l119-50 110 190-103 78q1 7 1 13.5v27q0 6.5-2 13.5l103 78-110 190-118-50q-11 8-23 15t-24 12L590-80H370Zm70-80h79l14-106q31-8 57.5-23.5T639-327l99 41 39-68-86-65q5-14 7-29.5t2-31.5q0-16-2-31.5t-7-29.5l86-65-39-68-99 42q-22-23-48.5-38.5T533-694l-13-106h-79l-14 106q-31 8-57.5 23.5T321-633l-99-41-39 68 86 64q-5 15-7 30t-2 32q0 16 2 31t7 30l-86 65 39 68 99-42q22 23 48.5 38.5T427-266l13 106Zm42-180q58 0 99-41t41-99q0-58-41-99t-99-41q-59 0-99.5 41T342-480q0 58 40.5 99t99.5 41Zm-2-140Z" />
            </svg>
        </div>
        <!-- Menú desplegable -->
        <div x-show="open" @click.away="open = false"
            class="absolute right-0 mt-1 w-48 bg-white dark:bg-gray-700 border border-gray-300 dark:border-gray-600 rounded shadow-lg px-2 py-2">

            <label for="daysInput" class="block text-sm font-medium text-gray-700 dark:text-gray-300">
                Rango de días:
            </label>

            <input type="number" id="daysInput" x-model="days" min="1"
                class="mt-1 w-full px-2 py-1 border text-black border-gray-300 dark:border-gray-600 rounded focus:outline-none" />

            <p x-show="days <= 0" class="text-red-500 text-sm mt-1">El número de días debe ser mayor a 0.</p>

            <button x-show="days > 0" @click="$wire.actualizarDias(days); open = false"
                class="mt-2 w-full bg-blue-500 hover:bg-blue-600 text-white py-1 rounded">
                Aplicar
            </button>
        </div>
    </div>
    <div class="sm:grid sm:grid-cols-3 gap-4 items-start flex flex-col">
        @foreach ($prestamos as $prestamo)
        @if (!$prestamos || $prestamos->isEmpty())
            <p>Sin prestamos cercanos</p>
        @else
        <div class="flex flex-col items-start">
            <div class="flex">
                <span class="mr-2 text-black dark:text-white text-base font-normal">•</span>
                <p class="text-black dark:text-white text-base font-normal">
                    {{ $prestamo->solicitante->nombre }} {{ $prestamo->solicitante->apellido_p }}
                    {{ $prestamo->solicitante->apellido_m }}
                </p>
            </div>
            @foreach ($prestamo->detalles as $detalle)
                <p class="pl-4 text-black dark:text-white text-xs font-normal">Material:
                    {{ $detalle->material->nombre }}</p>
                <p class="ml-4 text-black dark:text-white text-xs font-normal bg-red-500">Vence:
                    {{ \Carbon\Carbon::parse($detalle->fecha_devolucion)->format('d/m/Y') }}</p>
            @endforeach
        </div>
        @endif
            
        @endforeach
    </div>
</div>
