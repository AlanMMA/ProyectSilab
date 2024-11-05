<div class="flex flex-col gap-12">
    <div class="px-6 flex sm:justify-between items-center flex-col sm:flex-row gap-6">
        <div class="sm:w-max w-full flex items-center gap-6">
            <div class="relative w-full">
                <x-label value="Escriba el solicitante:"></x-label>
                <input @if (!$solicitanteSeleccionado2) disabled @endif type="text" wire:model.live="search" placeholder="Buscar solicitante..."
                    class="w-full mt-1 border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 focus:border-indigo-500 dark:focus:border-indigo-600 focus:ring-indigo-500 dark:focus:ring-indigo-600 rounded-md shadow-sm"
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
                            <p>Sin resultados</p>
                        </div>
                    @endif
                @endif
            </div>

            <button @if (!$solicitanteSeleccionado2) hidden @endif class="bg-green-600 hover:bg-green-500 pt-2 pb-1 px-2 rounded-md cursor-pointer"
                wire:click="filtrarDetalles">
                <span class="material-symbols-outlined text-white">
                    check
                </span>
            </button>
            <button @if (!$solicitanteSeleccionado) hidden @endif class="bg-red-600 hover:bg-red-500 pt-2 pb-1 px-2 rounded-md cursor-pointer"
                wire:click="resetView">
                <span class="material-symbols-outlined text-white">
                    close
                </span>
            </button>
        </div>
        <button @if (!$solicitanteSeleccionado) hidden @endif wire:click="confirmarDevolucion"
            class="w-min h-min text-sm bg-green-600 hover:bg-green-500 pt-2 pb-1 px-2 rounded-md cursor-pointer text-white">
            Devolver Seleccionados
        </button>
    </div>

    <div class="px-6 overflow-y-auto max-h-[60vh] sm:max-h-full">
        <table class="w-full text-sm text-left rtl:text-right text-gray-500 dark:text-gray-400">
            <thead class="text-xs text-white uppercase bg-blue-tec dark:bg-gray-700 dark:text-gray-400 w-full">
                <tr>
                    <th scope="col" class="px-6 py-3 ">
                        <div class="flex items-center justify-center">

                        </div>
                    </th>
                    <th scope="col" class=" px-6 py-3 " >
                        <div class="flex items-center justify-center">
                            ID
                        </div>
                    </th>
                    <th scope="col" class=" px-6 py-3 ">
                        <div class="flex items-center justify-center">
                            Fecha de devolución
                        </div>
                    </th>
                    <th scope="col" class="px-6 py-3 ">
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
                            Estado
                        </div>
                    </th>
                    <th scope="col" class="px-6 py-3 ">
                        <div class="flex items-center justify-center">
                            Observacion
                        </div>
                    </th>

                </tr>
            </thead>
            <tbody>
                @foreach ($detalles as $detalle)
                    <tr wire:key="area-{{ $detalle->id }}"
                        class=" odd:bg-white odd:dark:bg-gray-900 even:bg-[#D2D9D3] even:text-blue-tec odd: text-black even:dark:bg-gray-800 border-b dark:border-gray-700">
                        <th scope="row" class="px-6 py-2 text-center font-medium  whitespace-nowrap dark:text-white">
                            <input type="checkbox" wire:click="toggleDetalleSelection({{ $detalle->id }})"
                                @if (in_array($detalle->id, $selectedDetalles)) checked @endif>
                        </th>
                        <td class="px-6 py-2 text-center font-medium  whitespace-nowrap dark:text-white">
                            {{ $detalle->id_prestamo }}
                        </td>
                        <td class="px-6 py-2 text-center font-medium  whitespace-nowrap dark:text-white">
                            {{ $detalle->fecha_devolucion }}
                        </td>
                        <td class="px-6 py-2 text-center font-medium  whitespace-nowrap dark:text-white">
                            {{ $detalle->materialDP->nombre }}
                        </td>
                        <td class="px-6 py-2 text-center font-medium  whitespace-nowrap dark:text-white">
                            {{ $detalle->cantidad }}
                        </td>
                        <td
                            class="px-6 py-2 text-center font-medium whitespace-nowrap 
        {{ $detalle->EstadoPrestamo == 'pendiente' ? 'text-orange-600 dark:text-orange-500' : ($detalle->EstadoPrestamo == 'atrasado' ? 'text-red-600 dark:text-red-500' : 'text-black') }}">
                            {{ $detalle->EstadoPrestamo }}
                        </td>

                        <td class="px-6 py-2 text-center font-medium  whitespace-nowrap dark:text-white">
                            {{ $detalle->observacion }}
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>


    @push('js')
        <script>
            Livewire.on('confirmarDevolucionMaterial', () => {
                Swal.fire({
                    title: "¿Estás seguro?",
                    text: "¿Deseas devolver los materiales seleccionados?",
                    icon: "warning",
                    showCancelButton: true,
                    confirmButtonColor: "#3085d6",
                    cancelButtonColor: "#d33",
                    confirmButtonText: "Sí, devolver",
                    cancelButtonText: "Cancelar"
                }).then((result) => {
                    if (result.isConfirmed) {
                        Livewire.dispatch('devolverMaterial');
                    }
                });
            });
            Livewire.on('devolucionExitosa', () => {
                Swal.fire({
                    title: "Devolución completada",
                    text: "Los materiales han sido devueltos.",
                    icon: "success",
                    confirmButtonColor: "#3085d6",
                    confirmButtonText: "Aceptar"
                });
            });
            Livewire.on('noHaySeleccion', () => {
                Swal.fire({
                    title: "Advertencia",
                    text: "No has seleccionado ningún material para devolver.",
                    icon: "warning",
                    confirmButtonColor: "#f0ad4e",
                    confirmButtonText: "Aceptar"
                });
            });
            Livewire.on('sinPrestamosPendientes', () => {
                Swal.fire({
                    title: "Sin préstamos pendientes",
                    text: "El solicitante seleccionado no tiene préstamos pendientes.",
                    icon: "info",
                    confirmButtonColor: "#3085d6",
                    confirmButtonText: "Aceptar"
                });
            });
        </script>
    @endpush


</div>
