<div class="relative h-full overflow-y-auto">
    <div class="relative shadow-md max-h-screen">
        <div class="py-4 px-6 block items-center gap-4 w-full sm:flex">
            <div class="flex items-center justify-center gap-1 mb-4 sm:mb-0">
                <span class="text-white">Mostrar</span>
                <select wire:model.live="cant"
                    class="border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 focus:border-indigo-500 dark:focus:border-indigo-600 focus:ring-indigo-500 dark:focus:ring-indigo-600 rounded-md shadow-sm">
                    <option value="10">10</option>
                    <option value="25">25</option>
                    <option value="50">50</option>
                    <option value="100">100</option>
                </select>
                <span class="text-white">Entrada</span>
                <div class="w-auto flex justify-center sm:hidden items-center ml-4">
                    @livewire('Solicitante.Create')
                </div>
            </div>
            <x-input class="sm:flex-1 w-full mb-4 sm:mb-0" name="search" placeholder="¿Qué desea buscar?" wire:model.live="search"
                type="text"></x-input>
            <div class="w-auto sm:flex justify-center mb-4 sm:mb-0 hidden">
                @livewire('Solicitante.Create')
            </div>
        </div>
        
        
        @if ($datos->count())
            <div class="px-6 overflow-y-auto max-h-[60vh]">
                <table class="w-full text-sm text-left rtl:text-right text-gray-500 dark:text-gray-400">
                    <thead class="text-xs text-gray-700 uppercase bg-gray-50 dark:bg-gray-700 dark:text-gray-400 w-full">
                        <tr>
                            <th scope="col" class="cursor-pointer px-6 py-3" wire:click="order('id')">
                                <div class="flex items-center w-full ">
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
                            <th scope="col" class=" px-6 py-3 cursor-pointer" wire:click="order('nombre')">
                                <div class="flex items-center w-full">
                                    Nombres
                                    @if ($sort == 'nombre')
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
                            <th scope="col" class=" px-6 py-3 cursor-pointer" wire:click="order('apellido_p')">
                                <div class="flex items-center w-full">
                                    Apellidos
                                    @if ($sort == 'apellido_p')
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
                            <th scope="col" class=" px-6 py-3 cursor-pointer" wire:click="order('id_area')">
                                <div class="flex items-center w-full">
                                    Area
                                    @if ($sort == 'id_area')
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
                            <th scope="col" class=" px-6 py-3 cursor-pointer" wire:click="order('tipo')">
                                <div class="flex items-center w-full">
                                    Rango
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
                            <th scope="col" class="px-6 py-3">
                                <div class="flex items-center">
                                    Acciones
                                </div>
                            </th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($datos as $dato)
                            <tr wire:key="solicitante-{{ $dato->id }}"
                                class="odd:bg-white odd:dark:bg-gray-900 even:bg-gray-50 even:dark:bg-gray-800 border-b dark:border-gray-700">
                                <th scope="row"
                                    class="px-6 py-4 font-medium text-gray-900 whitespace-nowrap dark:text-white">
                                    {{ $dato->id }}
                                </th>
                                <td class="px-6 py-4">
                                    {{ $dato->nombre }}
                                </td>
                                <td class="px-6 py-4">
                                    {{ $dato->apellido_p }} {{$dato->apellido_m}}
                                </td>
                                <td class="px-6 py-4">
                                    {{ $dato->area->nombre }}
                                </td>
                                <td class="px-6 py-4">
                                    {{ $dato->tipo }}
                                </td>

                                <td class="px-6 py-4 flex items-center bg-green-500">
                                    @livewire('Solicitante.Edit', ['dato' => $dato], key('edit-' . $dato->id))
                                    <a class="bg-red-600 hover:bg-red-500 pt-1 pb-2 px-2 rounded-md cursor-pointer"
                                        wire:click="$dispatch('destroy', { id: {{ $dato->id }}, nombre: '{{ $dato->nombre }}' })">
                                        <span class="material-symbols-outlined text-white">
                                            delete
                                        </span>
                                    </a>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        @else
            <div class="px-6 py-4 bg-white border-r-4">
                No hay resultados
                <br>
                ¿Desea agregarlo?
            </div>
        @endif

        <!-- Paginación -->
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
