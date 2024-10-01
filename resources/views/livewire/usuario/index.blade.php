<div>
    <div class="relative overflow-x-auto shadow-md sm:rounded-lg">
        <div class="px-6 py-4 flex items-center gap-4">
            <div class="flex items-center gap-1">
                <span class="text-white">Mostrar</span>
                <select wire:model.live="cant"
                    class="border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 focus:border-indigo-500 dark:focus:border-indigo-600 focus:ring-indigo-500 dark:focus:ring-indigo-600 rounded-md shadow-sm">
                    <option value="10">10</option>
                    <option value="25">25</option>
                    <option value="50">50</option>
                    <option value="100">100</option>
                </select>
                <span class="text-white">Entrada</span>
            </div>
            <x-input class="flex-1 mr-4" name="search" placeholder="¿Qué desea buscar?" wire:model.live="search"
                type="text"></x-input>

            @livewire('Usuario.Create')
        </div>
        @if ($datos->count())
            <table class="w-full text-sm text-left rtl:text-right text-gray-500 dark:text-gray-400 ">
                <thead class="text-xs text-gray-700 uppercase bg-gray-50 dark:bg-gray-700 dark:text-gray-400 w-full">
                    <tr>
                        <th scope="col" class="cursor-pointer px-6 py-3 " wire:click="order('id')">
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
                        <th scope="col" class=" px-6 py-3 cursor-pointer" wire:click="order('name')">
                            <div class="flex items-center w-full">
                                Nombre
                                @if ($sort == 'name')
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
                        <th scope="col" class="px-6 py-3 cursor-pointer" wire:click="order('email')">
                            <div class="flex items-center w-full">
                                Email
                                @if ($sort == 'email')
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
                            <div class="flex items-center w-full">
                                Rol
                            </div>
                        </th>
                        <th scope="col" class="px-6 py-3 ">
                            <div class="flex items-center">
                                Acciones
                            </div>
                        </th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($datos as $dato)
                        <tr wire:key="categoria-{{ $dato->id }}"
                            class=" odd:bg-white odd:dark:bg-gray-900 even:bg-gray-50 even:dark:bg-gray-800 border-b dark:border-gray-700">
                            <th scope="row"
                                class="px-6 py-4 font-medium text-gray-900 whitespace-nowrap dark:text-white">
                                {{ $dato->id }}
                            </th>
                            <td class="px-6 py-4">
                                {{ $dato->name }}
                            </td>
                            <td class="px-6 py-4">
                                {{ $dato->email }}
                            </td>
                            <td class="px-6 py-4">
                                {{ $dato->rol->nombre }}
                            </td>

                            <td class="px-6 py-4 flex items-center bg-green-500">
                                @livewire('Usuario.edit', ['dato' => $dato], key('edit-' . $dato->id))

                                <a class="bg-red-600 hover:bg-red-500 pt-1 pb-2 px-2 rounded-md cursor-pointer"
                                    wire:click="$dispatch('destroy', { id: {{ $dato->id }}, nombre: '{{ $dato->name }}' })">
                                    <span class="material-symbols-outlined text-white">
                                        delete
                                    </span>
                                </a>

                            </td>
                        </tr>
                    @endforeach

                </tbody>
            </table>
        @else
            <div class="px-6 py-4 bg-white border-r-4">
                No hay resultados
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
