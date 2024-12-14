<div class="relative h-full overflow-y-auto">
    @php
        $Gerente = auth()->user()->id_rol;
    @endphp
    <div class="relative shadow-md max-h-screen">
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
                @if ($Gerente != 7)
                    <div class="w-auto flex justify-center sm:hidden items-center ml-4">
                        @livewire('Solicitante.Create')
                    </div>
                @endif
            </div>
            <x-input class="sm:flex-1 w-full mb-4 sm:mb-0" name="search" placeholder="¿Qué desea buscar?"
                wire:model.live="search" type="text"></x-input>
            @if ($Gerente != 7)
                <div class="w-auto sm:flex justify-center mb-4 sm:mb-0 hidden">
                    @livewire('Solicitante.Create')
                </div>
            @endif
        </div>

        @if ($datos->count())
            <div class="px-6 overflow-y-auto max-h-[60vh] sm:max-h-full">
                <table class="w-full text-sm text-left rtl:text-right text-gray-500 dark:text-gray-400">
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
                                wire:click="order('nombre')">
                                <div class="flex items-center justify-center">
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
                            <th scope="col" class="cursor-pointer px-6 py-3 text-center"
                                wire:click="order('apellido_p')">
                                <div class="flex items-center justify-center">
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
                            <th scope="col" class="cursor-pointer px-6 py-3 text-center"
                                wire:click="order('id_area')">
                                <div class="flex items-center justify-center">
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
                            <th scope="col" class="cursor-pointer px-6 py-3 text-center" wire:click="order('tipo')">
                                <div class="flex items-center justify-center">
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
                            @if ($Gerente != 7)
                                <th scope="col" class="px-6 py-3 text-center">
                                    <div class="flex items-center justify-center">
                                        Acciones
                                    </div>
                                </th>
                            @endif
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($datos as $dato)
                            <tr wire:key="solicitante-{{ $dato->id }}"
                                class=" odd:bg-white odd:dark:bg-gray-900 even:bg-[#D2D9D3] even:text-blue-tec odd: text-black even:dark:bg-gray-800 border-b dark:border-gray-700">
                                <th scope="row"
                                    class="px-6 py-2 text-center font-medium  whitespace-nowrap dark:text-white">
                                    {{ $dato->id }}
                                </th>
                                <td class="px-6 py-2 text-center font-medium  whitespace-nowrap dark:text-white">
                                    {{ $dato->nombre }}
                                </td>
                                <td class="px-6 py-2 text-center font-medium  whitespace-nowrap dark:text-white">
                                    {{ $dato->apellido_p }} {{ $dato->apellido_m }}
                                </td>
                                <td class="px-6 py-2 text-center font-medium  whitespace-nowrap dark:text-white">
                                    {{ $dato->area->nombre }}
                                </td>
                                <td class="px-6 py-2 text-center font-medium  whitespace-nowrap dark:text-white">
                                    {{ $dato->tipo }}
                                </td>
                                @if ($Gerente != 7)
                                    <td class="px-6 py-2 flex justify-center items-center gap-2">
                                        @livewire('Solicitante.Edit', ['dato' => $dato], key('edit-' . $dato->id))
                                        <a class="bg-red-600 hover:bg-red-500 pt-2 pb-1 px-2 rounded-md cursor-pointer"
                                            wire:click="$dispatch('destroy', { id: {{ $dato->id }}, nombre: '{{ $dato->nombre }}' })">
                                            <span class="material-symbols-outlined text-white">
                                                delete
                                            </span>
                                        </a>
                                    </td>
                                @endif
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        @else
        <div class="px-6 py-4">
            @if ($search)
            <p class="bg-white px-6 py-4 text-center">
                No hay resultados que coincidan con su búsqueda.
            </p>
            @else
            <p class="bg-white px-6 py-4 text-center">
                Actualmente no hay datos en esta tabla.
            </p>
            @endif
        </div>
        @endif

        <!-- Paginación -->
        <div class="px-6 py-3">
            {{ $datos->onEachSide(1)->links() }}
        </div>
    </div>

    {{-- @push('js')
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
    @endpush --}}

    @push('js')
    <script>
        Livewire.on('destroy', event => {
            Swal.fire({
                title: "¿Estás seguro de realizar esta acción?",
                text: "Registro: " + event.id,
                icon: "warning",
                showCancelButton: true,
                confirmButtonColor: "#3085d6",
                cancelButtonColor: "#d33",
                confirmButtonText: "Aceptar"
            }).then((result) => {
                if (result.isConfirmed) {
                    // Disparar el evento para ejecutar el método destroy en Livewire
                    Livewire.dispatch('destroyPost', { id: event.id });
                    console.log('se ejecutó bien');
                }
            });
        });
    
        Livewire.on('deletionError', message => {
            Swal.fire({
                title: "Error",
                text: message,
                icon: "error"
            });
        });
    
        Livewire.on('deletionSuccess', message => {
            Swal.fire({
                title: "Operación exitosa",
                text: message,
                icon: "success"
            });
        });
    </script>
    @endpush
</div>
