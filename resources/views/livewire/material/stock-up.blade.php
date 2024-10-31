<div class="h-auto w-auto flex gap-6">
    <div class="sm:w-1/4 w-max mb-4 flex flex-col gap-4 justify-center items-center relative">
        <div class="relative w-full">
            <x-label class="text-black dark:text-white" value="Material:"></x-label>
            <input type="text" wire:model.live="materialSearch" placeholder="Escriba para buscar material..."
                class="w-full border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 focus:border-indigo-500 dark:focus:border-indigo-600 focus:ring-indigo-500 dark:focus:ring-indigo-600 rounded-md shadow-sm"
                autocomplete="off" />
            @if (strlen($materialSearch) > 0 && !$selectedMaterial)
                @if (count($materiales) > 0)
                    <ul
                        class="absolute z-10 mt-2 border rounded-md max-h-40 overflow-y-auto bg-blue-tec text-white dark:bg-white dark:text-blue-tec w-full">
                        @foreach ($materiales as $material)
                            <li class="px-4 py-2 hover:bg-gray-100 hover:text-blue-tec dark:hover:bg-blue-tec dark:hover:text-white cursor-pointer"
                                wire:click="selectMaterial({{ $material->id }})">
                                {{ $material->nombre }} ({{ $material->modelo }})
                            </li>
                        @endforeach
                    </ul>
                @else
                    <div
                        class="absolute z-10 mt-2 border rounded-md bg-blue-tec text-white dark:bg-white dark:text-blue-tec p-2 w-full">
                        <p>No encuentras el material? Agregalo:</p>
                        <a href="{{ route('material') }}"
                            class="mt-2 bg-green-500 hover:bg-green-700 text-white font-bold py-1 px-2 rounded">Agregar</a>
                    </div>
                @endif
            @endif
        </div>
        <div class="w-full flex flex-col">
            <x-label class="text-black dark:text-white" value="Stock actual:"></x-label>
            <x-input class="mb-4" type="text" value="{{ $stockActual }}" readonly></x-input>
            <x-label class="text-black dark:text-white" value="Ingrese la cantidad a dar de alta:"></x-label>
            <x-input type="number" min='1' wire:model="newStock"></x-input>
            <x-confirm-button class="mt-4 w-min flex self-center"
                wire:click="agregarMaterial">Agregar</x-confirm-button>
        </div>
    </div>

    <div class="sm:w-full w-max flex flex-col gap-4 mt-1">
        <x-confirm-button class="flex self-end {{ !$materialSeleccionado ? 'hidden' : '' }}"
            wire:click="confirmarAgregarMaterial">Dar de alta</x-confirm-button>
        <div class=" sm:overflow-y-auto max-h-[60vh] sm:max-h-full w-full">
            <table class="w-full text-sm text-left rtl:text-right text-gray-500 dark:text-gray-400">
                <thead class="text-xs text-white uppercase bg-blue-tec dark:bg-gray-700 dark:text-gray-400 w-full">
                    <tr>
                        <th scope="col" class="cursor-pointer px-6 py-3 " wire:click="order('id')">
                            <div class="flex items-center justify-center">
                                ID
                            </div>
                        </th>
                        <th scope="col" class=" px-6 py-3 cursor-pointer" wire:click="order('nombre')">
                            <div class="flex items-center justify-center">
                                Material
                            </div>
                        </th>
                        <th scope="col" class="px-6 py-3 ">
                            <div class="flex items-center justify-center">
                                Cantidad
                            </div>
                        </th>
                        <th scope="col" class="px-6 py-3 ">
                            <div class="flex items-center justify-center">
                                Modelo
                            </div>
                        </th>
                        <th scope="col" class="px-6 py-3 ">
                            <div class="flex items-center justify-center">
                                Observacion
                            </div>
                        </th>
                        <th scope="col" class="px-6 py-3 ">
                            <div class="flex items-center justify-center">

                            </div>
                        </th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($tMateriales as $material)
                        <tr
                            class=" odd:bg-white odd:dark:bg-gray-900 even:bg-[#D2D9D3] even:text-blue-tec odd: text-black even:dark:bg-gray-800 border-b dark:border-gray-700">

                            <td class="px-6 py-2 text-center font-medium  whitespace-nowrap dark:text-white">
                                {{ $material['id'] }}
                            </td>
                            <td class="px-6 py-2 text-center font-medium  whitespace-nowrap dark:text-white">
                                {{ $material['nombre'] }}
                            </td>
                            <td class="px-6 py-2 text-center font-medium  whitespace-nowrap dark:text-white">
                                {{ $material['cantidad'] }}
                            </td>
                            <td class="px-6 py-2 text-center font-medium whitespace-nowrap dark:text-white">
                                {{ $material['modelo'] }}
                            </td>
                            <td class="px-6 py-2 text-center font-medium whitespace-nowrap dark:text-white">
                                {{ $material['observacion'] }}
                            </td>
                            <td class="px-6 py-2 text-center font-medium whitespace-nowrap dark:text-white">
                                <button class="bg-red-600 hover:bg-red-500 pt-2 pb-1 px-2 rounded-md cursor-pointer"
                                    wire:click="eliminarMaterial({{ $material['id'] }})">
                                    <span class="material-symbols-outlined text-white">
                                        delete
                                    </span>
                                </button>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>

    @push('js')
        <script>
            Livewire.on('errorStock', (mensaje) => {
                Swal.fire({
                    icon: 'error',
                    title: 'Error',
                    text: mensaje,
                    confirmButtonColor: '#d33',
                    confirmButtonText: 'Aceptar'
                });
            });
        </script>
    @endpush

    @push('js')
        <script>
            Livewire.on('exitostock', (mensaje) => {
                Swal.fire({
                    icon: 'success',
                    title: 'Proceso exitoso',
                    text: mensaje,
                    confirmButtonColor: '#3085d6',
                    confirmButtonText: 'Aceptar'
                });
            });
        </script>
    @endpush

    @push('js')
        <script>
            Livewire.on('confirmarStock', () => {
                Swal.fire({
                    title: "Confirmación",
                    text: "¿Desea dar de alta a los materiales?",
                    icon: "warning",
                    showCancelButton: true,
                    confirmButtonColor: "#3085d6",
                    cancelButtonColor: "#d33",
                    confirmButtonText: "Sí, confirmar",
                    cancelButtonText: "Cancelar"
                }).then((result) => {
                    if (result.isConfirmed) {
                        Livewire.dispatch('guardarCambios');
                    }
                });
            });
        </script>
    @endpush


</div>
