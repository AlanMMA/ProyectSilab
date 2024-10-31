<div class="h-full overflow-y-auto">
    <div class="relative shadow-md">
        <div class="py-4 px-6 block items-center gap-4 w-full sm:flex">
            <div class="flex items-center justify-center gap-1 mb-4 sm:mb-0">
                <span class="text-gray-900 dark:text-white">Mostrar</span>
                <select wire:model.live="cant"
                    class="border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 focus:border-indigo-500 dark:focus:border-indigo-600 focus:ring-indigo-500 dark:focus:ring-indigo-600 rounded-md shadow-sm">
                    <option value="10">10</option>
                    <option value="25">25</option>
                    <option value="50">50</option>
                    <option value="100">100</option>
                </select>
                <span class="text-gray-900 dark:text-white">Entrada</span>
                <div class="w-auto flex justify-center sm:hidden items-center ml-4">
                    <x-confirm-button :href="route('prestamosc')">Agregar</x-confirm-button>
                </div>
            </div>
            <x-input class="sm:flex-1 w-full mb-4 sm:mb-0" name="search" placeholder="¿Qué desea buscar?"
                wire:model.live="search" type="text"></x-input>
            <div class="w-auto sm:flex justify-center mb-4 sm:mb-0 hidden">
                <x-confirm-button :href="route('prestamosc')">Agregar</x-confirm-button>
            </div>
        </div>

        @if ($datos->count())
        <div class="px-6 overflow-y-auto max-h-[60vh] sm:max-h-full">
            <table class="w-full text-sm text-left rtl:text-right text-gray-500 dark:text-gray-400 ">
                <thead class="text-xs text-white uppercase bg-blue-tec dark:bg-gray-700 dark:text-gray-400 w-full">
                    <tr>
                        <th scope="col" class="cursor-pointer px-6 py-3 text-center" wire:click="order('id')">
                            <div class="flex items-center justify-center">
                                ID
                                @if ($sort == 'id')
                                @if ($direc == 'asc')
                                <span class="material-symbols-outlined">
                                    vertical_align_bottom
                                </span>
                                @else
                                <span class="material-symbols-outlined">
                                    vertical_align_top
                                </span>
                                @endif
                                @else
                                <span class="material-symbols-outlined">
                                    unfold_more
                                </span>
                                @endif
                            </div>
                        </th>
                        <th scope="col" class="cursor-pointer px-6 py-3 text-center"
                            wire:click="order('fecha_prestamo')">
                            <div class="flex items-center justify-center">
                                Fecha de prestamo
                                @if ($sort == 'fecha_prestamo')
                                @if ($direc == 'asc')
                                <span class="material-symbols-outlined">vertical_align_bottom</span>
                                @else
                                <span class="material-symbols-outlined">vertical_align_top</span>
                                @endif
                                @else
                                <span class="material-symbols-outlined">unfold_more</span>
                                @endif
                            </div>
                        </th>
                        <th scope="col" class="cursor-pointer px-6 py-3 text-center"
                            wire:click="order('id_solicitante')">
                            <div class="flex items-center justify-center">
                                Solicitante
                                @if ($sort == 'id_solicitante')
                                @if ($direc == 'asc')
                                <span class="material-symbols-outlined">vertical_align_bottom</span>
                                @else
                                <span class="material-symbols-outlined">vertical_align_top</span>
                                @endif
                                @else
                                <span class="material-symbols-outlined">unfold_more</span>
                                @endif
                            </div>
                        </th>
                        <th scope="col" class="cursor-pointer px-6 py-3 text-center" wire:click="order('tipo')">
                            <div class="flex items-center justify-center">
                                Tipo
                                @if ($sort == 'tipo')
                                @if ($direc == 'asc')
                                <span class="material-symbols-outlined">vertical_align_bottom</span>
                                @else
                                <span class="material-symbols-outlined">vertical_align_top</span>
                                @endif
                                @else
                                <span class="material-symbols-outlined">unfold_more</span>
                                @endif
                            </div>
                        </th>
                        <th scope="col" class="cursor-pointer px-6 py-3 text-center hidden"
                            wire:click="order('id_encargado')">
                            <div class="flex items-center justify-center">
                                Encargado
                                @if ($sort == 'id_encargado')
                                @if ($direc == 'asc')
                                <span class="material-symbols-outlined">vertical_align_bottom</span>
                                @else
                                <span class="material-symbols-outlined">vertical_align_top</span>
                                @endif
                                @else
                                <span class="material-symbols-outlined">unfold_more</span>
                                @endif
                            </div>
                        </th>
                        <th scope="col" class="px-6 py-3 text-center">
                            <div class="flex items-center justify-center">
                                Ver detalle
                            </div>
                        </th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($datos as $dato)
                    <tr wire:key="material-{{ $dato->prestamo_id }}"
                        class=" odd:bg-white odd:dark:bg-gray-900 even:bg-[#D2D9D3] even:text-blue-tec odd: text-black even:dark:bg-gray-800 border-b dark:border-gray-700">
                        <th scope="row" class="px-6 py-2 text-center font-medium  whitespace-nowrap dark:text-white">
                            {{ $dato->id }}
                        </th>
                        <td class="px-6 py-2 text-center font-medium  whitespace-nowrap dark:text-white">
                            {{ $dato->fecha_prestamo }}
                        </td>
                        <td class="px-6 py-2 text-center font-medium  whitespace-nowrap dark:text-white">
                            {{ $dato->solicitante_nombre }} {{ $dato->solicitante_apellido_p }} {{
                            $dato->solicitante_apellido_m }}
                        </td>
                        <td class="px-6 py-2 text-center font-medium  whitespace-nowrap dark:text-white">
                            {{ $dato->solicitante_tipo }}
                        </td>
                        <td class="px-6 py-2 text-center font-medium  whitespace-nowrap dark:text-white hidden">
                            {{ $dato->encargado_nombre }} {{ $dato->encargado_apellido_p }} {{
                            $dato->encargado_apellido_m }}
                        </td>
                        <td class="px-6 py-2 flex justify-center items-center gap-2">
                            <button wire:click="verDetalle({{ $dato->id }})"
                                class="px-6 py-2 text-center font-medium  whitespace-nowrap dark:text-white">
                                <svg xmlns="http://www.w3.org/2000/svg" height="28px" viewBox="0 -960 960 960"
                                    width="28px" class="fill-black dark:fill-white">>
                                    <path d="M200-800v241-1 400-640 200-200Zm80 400h140q9-23 22-43t30-37H280v80Zm0
                                        160h127q-5-20-6.5-40t.5-40H280v80ZM200-80q-33 0-56.5-23.5T120-160v-640q0-33
                                        23.5-56.5T200-880h320l240 240v100q-19-8-39-12.5t-41-6.5v-41H480v-200H200v640h241q16
                                        24 36 44.5T521-80H200Zm460-120q42 0 71-29t29-71q0-42-29-71t-71-29q-42 0-71 29t-29
                                        71q0 42 29 71t71 29ZM864-40 756-148q-21 14-45.5 21t-50.5 7q-75
                                        0-127.5-52.5T480-300q0-75 52.5-127.5T660-480q75 0 127.5 52.5T840-300q0 26-7
                                        50.5T812-204L920-96l-56 56Z" />
                                </svg>
                            </button>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        @if($mostrarModal)
        <!-- Modal Overlay -->
        <div x-data="{ open: true }" x-show="open" @close-modal.window="open = false"
            x-transition:enter="transition ease-out duration-300 transform"
            x-transition:enter-start="opacity-0 translate-y-4" x-transition:enter-end="opacity-100 translate-y-0"
            x-transition:leave="transition ease-in duration-200 transform"
            x-transition:leave-start="opacity-100 translate-y-0" x-transition:leave-end="opacity-0 translate-y-4"
            class="fixed inset-0 flex items-center justify-center z-50 bg-gray-900 bg-opacity-50 backdrop-blur-sm">

            <!-- Modal Content -->
            <div class="bg-white dark:bg-gray-800 rounded-lg shadow-lg w-10/12 overflow-y-auto p-4">
                <!-- Close Button -->
                <div class="flex justify-end p-4">
                    <button wire:click="cerrarModal" @click="open = false"
                        class="bg-red-500 hover:bg-red-600 text-white font-bold py-2 px-4 rounded">
                        Cerrar
                    </button>
                </div>
                <!-- Livewire Component -->
                @livewire('prestamo.detalle', ['id' => $prestamoId])
            </div>
        </div>
        @endif

        @else
        <div class="px-6 py-4 bg-white border-r-4">
            No hay resultados
            <br>
            ¿Desea agregarlo?
        </div>
        @endif

        <div class="px-6 py-3">
            {{ $datos->onEachSide(1)->links() }}
        </div>
    </div>
    @push('js')
    <script>
        Livewire.on('destroy', event => {
                Swal.fire({
                    title: "¿Estás seguro de eliminar el registro?",
                    text: "Registro: " + event.nombre,
                    icon: "warning",
                    showCancelButton: true,
                    confirmButtonColor: "#3085d6",
                    cancelButtonColor: "#d33",
                    confirmButtonText: "Aceptar"
                }).then((result) => {
                    if (result.isConfirmed) {
                        Livewire.dispatch('destroyPost', {
                            id: event.id
                        });
                        Swal.fire({
                            title: "Operación exitosa",
                            text: "Ha eliminado el registro: " + event.nombre,
                            icon: "success"
                        });
                    }
                });
            });
    </script>
    @endpush
</div>