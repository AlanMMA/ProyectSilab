<div class="h-auto w-auto">
    <div class="mx-4">
        <x-label value="Tipo de solicitante:"></x-label>
        <select wire:model.live="cant" @if (!$solicitanteSeleccionado2) disabled @endif
            class="border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 focus:border-indigo-500 dark:focus:border-indigo-600 focus:ring-indigo-500 dark:focus:ring-indigo-600 rounded-md shadow-sm">
            <option value="0">Seleccione el tipo de solicitante</option>
            <option value="alumno">Alumno</option>
            <option value="docente">Docente</option>
        </select>
    </div>

    <div class="mt-6 mx-4 flex gap-4 justify-center items-center">
        <div class="relative w-full">
            <x-label value="Solicitante:"></x-label>
            <input @if (!$solicitanteSeleccionado2) disabled @endif type="text" wire:model.live="search"
                placeholder="Escriba para buscar solicitante..."
                class="w-full border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 focus:border-indigo-500 dark:focus:border-indigo-600 focus:ring-indigo-500 dark:focus:ring-indigo-600 rounded-md shadow-sm"
                autocomplete="off" />
            @if (strlen($search) > 0 && !$selectedSolicitante)
                @if (count($solicitantes) > 0)
                    <ul
                        class="absolute z-10 mt-2 border rounded-md max-h-60 overflow-y-auto bg-blue-tec text-white dark:bg-white dark:text-blue-tec">
                        @foreach ($solicitantes as $solicitante)
                            <li class="px-4 py-2 hover:bg-gray-100 hover:text-blue-tec dark:hover:bg-blue-tec dark:hover:text-white cursor-pointer"
                                wire:click="selectSolicitante({{ $solicitante->id }})">
                                {{ $solicitante->tipo }}: {{ $solicitante->nombre }} {{ $solicitante->apellido_p }}
                            </li>
                        @endforeach
                    </ul>
                @else
                    <div
                        class="absolute z-10 mt-2 border rounded-md bg-blue-tec text-white dark:bg-white dark:text-blue-tec p-2">
                        <p>No encuentras el usuario? Agregalo:</p>
                        <a href="{{ route('solicitante') }}"
                            class="mt-2 bg-green-500 hover:bg-green-700 text-white font-bold py-1 px-2 rounded">Agregar</a>
                    </div>
                @endif
            @endif
        </div>
        

        <button class="bg-green-600 hover:bg-green-500 pt-2 pb-1 px-2 rounded-md cursor-pointer"
            wire:click="confirmarSeleccion" @if (!$solicitanteSeleccionado2) disabled @endif>
            <span class="material-symbols-outlined text-white">
                check
            </span>
        </button>
    </div>

    <div class="mt-6 mx-4">
        <x-label value="Fecha de prestamo:"></x-label>
        <x-input wire:model="fechaPrestamo" class="w-full" type="text" disabled></x-input>
    </div>
    <div class="mt-6 mx-4">
        <x-label value="Fecha de devolución:"></x-label>
        <x-input wire:model="fechaDev" class="w-full" type="date"></x-input>
    </div>
    <div class="mt-6 mx-4">
        <x-label value="Seleccione el material:"></x-label>
        <select wire:model.live = "selectMat"
            class="w-full border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 focus:border-indigo-500 dark:focus:border-indigo-600 focus:ring-indigo-500 dark:focus:ring-indigo-600 rounded-md shadow-sm"
            @if (!$solicitanteSeleccionado) disabled @endif>
            <option value="0">Seleccione un material</option>
            @foreach ($materiales as $material)
                <option value="{{ $material['id'] }}">{{ $material['nombre'] }}</option>
            @endforeach
        </select>
    </div>
    <div class="mt-6 mx-4">
        <x-label value="Cantidad:"></x-label>
        <x-input wire:model="Cantidad" class="w-full" type="number" min="1" placeholder="Ingrese cifra"
            :disabled="!$solicitanteSeleccionado"></x-input>
    </div>
    <div class="mt-6 pb-6 flex justify-center">
        <x-confirm-button wire:click="addMaterial" :disabled="!$solicitanteSeleccionado">Agregar</x-confirm-button>
    </div>
    @push('js')
        <script>
            Livewire.on('confirmarSeleccion', event => {

                const solicitanteData = event;
                if (Array.isArray(event) && event.length > 0) {
                    const solicitanteData = event[0];

                    Swal.fire({
                        title: "Confirmación",
                        text: "¿Desea realizar un prestamo a la siguiente persona? " + solicitanteData
                            .solicitante + " (ID: " + solicitanteData.id + ")",
                        icon: "warning",
                        showCancelButton: true,
                        confirmButtonColor: "#3085d6",
                        cancelButtonColor: "#d33",
                        confirmButtonText: "Sí, confirmar",
                        cancelButtonText: "Cancelar"
                    }).then((result) => {
                        if (result.isConfirmed) {
                            console.log("ID enviado a guardarSolicitante:", solicitanteData);
                            Livewire.dispatch('guardarSolicitante', [{
                                id: solicitanteData.id,
                                solicitante: solicitanteData.solicitante,
                            }]);
                            Livewire.dispatch('BlockDat');

                            Swal.fire({
                                title: "Confirmado",
                                text: "El solicitante ha sido seleccionado.",
                                icon: "success"
                            });
                        }
                    });
                } else {
                    console.error("No se encontró información del solicitante.");
                    Swal.fire({
                        title: "Error",
                        text: "Por favor, seleccione un solicitante válido.",
                        icon: "error"
                    });
                }
            });

            Livewire.on('solicitanteNoSeleccionado', () => {
                Swal.fire({
                    title: "Error",
                    text: "Por favor, seleccione un solicitante válido.",
                    icon: "error"
                });
            });

            Livewire.on('solicitanteNoEncontrado', () => {
                Swal.fire({
                    title: "Error",
                    text: "No se encontró el solicitante.",
                    icon: "error"
                });
            });
        </script>
    @endpush

    @push('js')
        <script>
            Livewire.on('mostrarErrorFecha', (mensaje) => {
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
