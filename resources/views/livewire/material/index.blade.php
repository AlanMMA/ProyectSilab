<div class="h-full overflow-y-auto">
    <div class="relative shadow-md">
        <div class="py-4 px-6 flex flex-col w-full justify-end items-center gap-4 sm:flex-row">
            @php
            $Gerente = auth()->user()->id_rol;
            @endphp
            @if ($Gerente == 7)
            <p class="text-lg font-bold text-black dark:text-white">Materiales del encargado:</p>
            <select name="" wire:model.live="SelectEncargado"
                class="w-min border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 focus:border-indigo-500 dark:focus:border-indigo-600 focus:ring-indigo-500 dark:focus:ring-indigo-600 rounded-md shadow-sm">
                <option value="0">Elija un encargado</option>
                @foreach ($encargados as $encargado)
                <option value="{{$encargado->id}}">{{$encargado->id}} {{$encargado->nombre}} {{$encargado->apellido_p}}
                    {{$encargado->apellido_m}}</option>
                @endforeach
            </select>
            @endif

        </div>
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
                    @livewire('Material.Create')
                </div>
            </div>
            <x-input class="sm:flex-1 w-full mb-4 sm:mb-0" name="search" placeholder="¿Qué desea buscar?"
                wire:model.live="search" type="text"></x-input>

            <div class="w-auto sm:flex justify-center mb-4 sm:mb-0 hidden">
                @livewire('Material.Create')
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
                        <th scope="col" class="cursor-pointer px-6 py-3 text-center" wire:click="order('nombre')">
                            <div class="flex items-center justify-center">
                                Nombre
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
                        <th scope="col" class="cursor-pointer px-6 py-3 text-center" wire:click="order('id_marca')">
                            <div class="flex items-center justify-center">
                                Marca
                                @if ($sort == 'id_marca')
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
                        <th scope="col" class="cursor-pointer px-6 py-3 text-center" wire:click="order('modelo')">
                            <div class="flex items-center justify-center">
                                Modelo
                                @if ($sort == 'modelo')
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
                        <th scope="col" class="cursor-pointer px-6 py-3 text-center" wire:click="order('id_categoria')">
                            <div class="flex items-center justify-center">
                                Categoria
                                @if ($sort == 'id_categoria')
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
                        <th scope="col" class="cursor-pointer px-6 py-3 text-center" wire:click="order('stock')">
                            <div class="flex items-center justify-center">
                                Stock
                                @if ($sort == 'stock')
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
                        <th scope="col" class="cursor-pointer px-6 py-3 text-center" wire:click="order('descripcion')">
                            <div class="flex items-center justify-center">
                                Descripcion
                                @if ($sort == 'descripcion')
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
                        <th scope="col" class="cursor-pointer px-6 py-3 text-center" wire:click="order('localizacion')">
                            <div class="flex items-center justify-center">
                                Localizacion
                                @if ($sort == 'localizacion')
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
                        <th scope="col" class="cursor-pointer px-6 py-3 text-center" wire:click="order('id_encargado')">
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
                    <tr wire:key="material-{{ $dato->id }}"
                        class=" odd:bg-white odd:dark:bg-gray-900 even:bg-[#D2D9D3] even:text-blue-tec odd: text-black even:dark:bg-gray-800 border-b dark:border-gray-700">
                        <th scope="row" class="px-6 py-2 text-center font-medium  whitespace-nowrap dark:text-white">
                            {{ $dato->id }}
                        </th>
                        <td class="px-6 py-2 text-center font-medium  whitespace-nowrap dark:text-white">
                            {{ $dato->nombre }}
                        </td>
                        <td class="px-6 py-2 text-center font-medium  whitespace-nowrap dark:text-white">
                            {{ $dato->marca->nombre }}
                        </td>
                        <td class="px-6 py-2 text-center font-medium  whitespace-nowrap dark:text-white">
                            {{ $dato->modelo }}
                        </td>
                        <td class="px-6 py-2 text-center font-medium  whitespace-nowrap dark:text-white">
                            {{ $dato->categoria->nombre }}
                        </td>
                        <td class="px-6 py-2 text-center font-medium  whitespace-nowrap dark:text-white">
                            {{ $dato->stock }}
                        </td>
                        <td class="px-6 py-2 text-center font-medium  whitespace-nowrap dark:text-white">
                            {{ $dato->descripcion }}
                        </td>
                        <td class="px-6 py-2 text-center font-medium  whitespace-nowrap dark:text-white">
                            {{ $dato->localizacion }}
                        </td>
                        <td class="px-6 py-2 text-center font-medium  whitespace-nowrap dark:text-white">
                            {{ $dato->encargado->nombre }}
                        </td>
                        @if ($Gerente != 7)
                        <td class="px-6 py-4 flex justify-center items-center gap-2">

                            @livewire('Material.Edit', ['dato' => $dato], key('edit-' . $dato->id))

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
        @if ($Gerente == 7)
        <div class="py-4 px-6 bg-white flex justify-around gap-6">
            Seleccione a un encargado para poder ver los datos
            <svg xmlns="http://www.w3.org/2000/svg" height="24px" viewBox="0 -960 960 960" width="24px" fill="#000">
                <path d="M440-160v-487L216-423l-56-57 320-320 320 320-56 57-224-224v487h-80Z" />
            </svg>
        </div>
        @else
        <div class="py-4 px-6 bg-white">
            No hay resultados con esos caracteres
        </div>
        @endif

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