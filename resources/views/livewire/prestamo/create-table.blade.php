<div class="h-full w-min flex flex-col gap-6 pr-6">
    <div class="flex flex-col items-center gap-4">
        <div class="w-full flex justify-end gap-6 items-center">
            <button class="bg-red-600 hover:bg-red-500 pt-2 pb-1 px-2 rounded-md cursor-pointer"
                wire:click = "confirmarSeleccion2" @if (!$solicitanteSeleccionadoT) hidden @endif>
                <span class="material-symbols-outlined text-white">
                    close
                </span>
            </button>   
            <x-input type="text" wire:model="solicitanteInfo" readonly></x-input>
            <p class="text-black dark:text-white">Prestamo No.{{ $idPrest }}</p>
        </div>
    </div>
    <div class="w-full flex flex-col justify-center gap-12">
        <table class="w-full text-sm text-left rtl:text-right text-gray-500 dark:text-gray-400 ">
            <thead class="text-xs text-white uppercase bg-blue-tec dark:bg-gray-700 dark:text-gray-400 w-auto ">
                <tr>
                    <th scope="col" class=" px-6 py-3">
                        <div class="flex items-center w-full ">
                            ID
                        </div>
                    </th>
                    <th scope="col" class="px-6 py-3">
                        <div class="flex items-center w-full">
                            Material
                        </div>
                    </th>
                    <th scope="col" class="px-6 py-3">
                        <div class="flex items-center w-full">
                            Cantidad
                        </div>
                    </th>
                    <th scope="col" class="px-6 py-3 ">
                        <div class="flex items-center w-max">
                            Fecha de prestamo
                        </div>
                    </th>
                    <th scope="col" class="px-6 py-3 ">
                        <div class="flex items-center w-max">
                            Fecha de devolución
                        </div>
                    </th>
                    <th scope="col" class="px-6 py-3 ">
                        <div class="text-center w-max">
                            Observaciones
                        </div>
                    </th>
                    <th scope="col" class="px-6 py-3 ">
                        <div class="flex items-center w-max">
                        </div>
                    </th>
                </tr>
            </thead>
            <tbody>
                @foreach ($selectedMaterials as $index => $material)
                    <tr
                    class=" odd:bg-white odd:dark:bg-gray-900 even:bg-gray-50 even:dark:bg-gray-800 border-b dark:border-gray-700">
                        <th scope="row" class="px-6 py-4 font-medium text-gray-900 whitespace-nowrap dark:text-white">
                            {{ $index + 1 }}</th>
                        <td class="px-6 py-3 text-center text-black dark:text-white">{{ $material['nombre'] }}</td>
                        <td class="px-6 py-3 content-center text-black dark:text-white" contenteditable="true">
                            <input type="number" min="1"
                                wire:model.live="selectedMaterials.{{ $index }}.cantidad"
                                class="w-full border-none text-end bg-transparent text-black dark:text-white">
                        </td>
                        <td class="px-6 py-3 text-center text-black dark:text-white">{{ $material['fechaPrestamo'] }}</td>
                        <td class="px-6 py-3 text-center text-black dark:text-white">{{ $material['fechaDev'] }}</td>
                        <td class="px-6 py-3 text-center" contenteditable="true">
                            <textarea wire:model.live="selectedMaterials.{{ $index }}.observacion" type="text" placeholder="Escriba aquí"
                                class="w-full text-xs bg-transparent text-black dark:text-white"></textarea>
                        </td>
                        <td class="px-6 py-3 text-center">
                            <button class="bg-red-600 hover:bg-red-500 pt-2 pb-1 px-2 rounded-md cursor-pointer"
                                wire:click="removeMaterial({{ $index }})"
                                onClick="console.log('Índice:', {{ $index }})">
                                <span class="material-symbols-outlined text-white">
                                    delete
                                </span>
                            </button>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
        <div class="flex justify-center">
            <x-confirm-button wire:click="confirmarPrestamo" class="{{ $buttonTable ? '' : 'hidden' }}">
                confirmar <br> prestamo
            </x-confirm-button>
        </div>
    </div>

</div>



@push('js')
    <script>
        Livewire.on('confirmarSeleccion2', event => {
            Swal.fire({
                title: "¿Está seguro de reiniciar el prestamo?",
                text: "Se reiniciará la tabla con materiales seleccionado.",
                icon: "warning",
                showCancelButton: true,
                confirmButtonColor: "#3085d6",
                cancelButtonColor: "#d33",
                confirmButtonText: "Confirmar"
            }).then((result) => {
                if (result.isConfirmed) {
                    Livewire.dispatch('resetDatos');
                    Swal.fire({
                        title: "¡Proceso exitoso!",
                        text: "Proceso reiniciado.",
                        icon: "success"
                    });
                }
            });
        });
    </script>
@endpush

@push('js')
    <script>
        Livewire.on('DeleteOp', index => {
            Swal.fire({
                title: "ADVERTENCIA",
                text: "¿Está seguro de quitar el siguiente material del préstamo?",
                icon: "warning",
                showCancelButton: true,
                confirmButtonColor: "#3085d6",
                cancelButtonColor: "#d33",
                confirmButtonText: "Confirmar"
            }).then((result) => {
                if (result.isConfirmed) {
                    Livewire.dispatch('removeMaterial', index);
                    Swal.fire({
                        title: "¡Proceso exitoso!",
                        text: "Material eliminado.",
                        icon: "success"
                    });
                }
            });
        });
    </script>
@endpush

@push('js')
    <script>
        Livewire.on('confirmarPrestamo', () => {
            Swal.fire({
                title: "Confirmación",
                text: "¿Desea realizar el préstamo?",
                icon: "warning",
                showCancelButton: true,
                confirmButtonColor: "#3085d6",
                cancelButtonColor: "#d33",
                confirmButtonText: "Sí, confirmar",
                cancelButtonText: "Cancelar"
            }).then((result) => {
                if (result.isConfirmed) {
                    Livewire.dispatch('guardarPrestamo');
                }
            });
        });
    </script>
@endpush


@push('js')
    <script>
        Livewire.on('ErrorPrestamo', (mensaje) => {
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

</div>
