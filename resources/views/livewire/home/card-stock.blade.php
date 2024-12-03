{{-- <div class="bg-white dark:bg-gray-800 text-lg  mb-3 sm:w-sm">
    <p class="text-black dark:text-white text-center font-semibold text-xl pb-2">Materiales con poco stock:</p>
    <ul class="text-black dark:text-white list-disc pl-4">
        @foreach ($materiales as $material)
            <li class="ml-0 text-base leading-tight flex items-center relative">
                <span class="mr-2 text-black dark:text-white text-xs font-normal">•</span>
                {{ $material->nombre }} - Stock: {{ $material->stock }}
            </li>
        @endforeach
    </ul>
</div> --}}

<div class="bg-white dark:bg-gray-800 relative text-lg  mb-3 sm:w-sm " x-data="{ open: false,  stockk: @entangle('stockk')}">
    <div class="flex flex-col text-black dark:text-white text-lg font-semibold mb-3 sm:w-sm">
        <div class="flex justify-between w-full">
            <p class="text-black dark:text-white text-center font-semibold text-xl pb-2">Materiales con poco stock.</p>
            <svg xmlns="http://www.w3.org/2000/svg" height="24px" viewBox="0 -960 960 960" width="24px"
                class="ml-2 fill-black dark:fill-white cursor-pointer" @click="open = !open" <!-- Activador del menú -->
                >
                <path
                    d="m370-80-16-128q-13-5-24.5-12T307-235l-119 50L78-375l103-78q-1-7-1-13.5v-27q0-6.5 1-13.5L78-585l110-190 119 50q11-8 23-15t24-12l16-128h220l16 128q13 5 24.5 12t22.5 15l119-50 110 190-103 78q1 7 1 13.5v27q0 6.5-2 13.5l103 78-110 190-118-50q-11 8-23 15t-24 12L590-80H370Zm70-80h79l14-106q31-8 57.5-23.5T639-327l99 41 39-68-86-65q5-14 7-29.5t2-31.5q0-16-2-31.5t-7-29.5l86-65-39-68-99 42q-22-23-48.5-38.5T533-694l-13-106h-79l-14 106q-31 8-57.5 23.5T321-633l-99-41-39 68 86 64q-5 15-7 30t-2 32q0 16 2 31t7 30l-86 65 39 68 99-42q22 23 48.5 38.5T427-266l13 106Zm42-180q58 0 99-41t41-99q0-58-41-99t-99-41q-59 0-99.5 41T342-480q0 58 40.5 99t99.5 41Zm-2-140Z" />
            </svg>
        </div>
        <!-- Menú desplegable -->
        <div x-show="open" @click.away="open = false"
            class="absolute right-0 mt-1 w-48 bg-white dark:bg-gray-700 border border-gray-300 dark:border-gray-600 rounded shadow-lg px-2 py-2 z-50">

            <label for="stockkInput" class="block text-sm font-medium text-gray-700 dark:text-gray-300">
                Mostrar materiales con stock menor a:
            </label>

            <input type="number" id="stockkInput" x-model="stockk" wire:model.debounce.500ms="stockk" min="1"
                class="mt-1 w-full px-2 py-1 border text-black border-gray-300 dark:border-gray-600 rounded focus:outline-none" />


            <p x-show="stockk <= 0" class="text-red-500 text-sm mt-1">El número de días debe ser mayor a 0.</p>

            <button x-show="stockk > 0" wire:click="ActualizarStock"
                class="mt-2 w-full bg-blue-500 hover:bg-blue-600 text-white py-1 rounded">
                Aplicar
            </button>
        </div>
    </div>
    @if (!$materiales || $materiales->isEmpty())
        <p class="text-center text-gray-500 dark:text-gray-300">Materiales con suficiente stock.</p>
    @else
        <ul class="text-black dark:text-white list-disc pl-4 z-0">
            @foreach ($materiales as $material)
                <li class="ml-0 text-base leading-tight flex items-center relative">
                    <span class="mr-2 text-black dark:text-white text-xs font-normal">•</span>
                    {{ $material->nombre }} - Stock: {{ $material->stock }}
                </li>
            @endforeach
        </ul>
    @endif
</div>
