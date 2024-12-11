<div class="h-full overflow-y-auto">
    @php
    $Gerente = auth()->user()->id_rol;
    @endphp
    <div class="relative shadow-md">
        <div class="py-4 px-6 flex flex-col w-full justify-end items-center gap-4 sm:flex-row">
            @if ($Gerente == 7)
            <p class="text-lg font-bold text-black dark:text-white">Alumnos de servicio del encargado:</p>
            <select name="" wire:model.live="SelectEncargado"
                class="w-min border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 focus:border-indigo-500 dark:focus:border-indigo-600 focus:ring-indigo-500 dark:focus:ring-indigo-600 rounded-md shadow-sm">
                <option value="0">Elija un encargado</option>
                @foreach ($encargados as $encargado)
                <option value="{{ $encargado->id }}">{{ $encargado->id }} {{ $encargado->nombre }}
                    {{ $encargado->apellido_p }}
                    {{ $encargado->apellido_m }}</option>
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
                @if ($Gerente != 7)
                <div class="w-auto flex justify-center sm:hidden items-center ml-4">
                    @livewire('Usuario.Create')
                </div>
                @endif
            </div>
            <x-input class="sm:flex-1 w-full mb-4 sm:mb-0" name="search" placeholder="¿Qué desea buscar?"
                wire:model.live="search" type="text"></x-input>
            @if ($Gerente != 7)
            <div class="w-auto sm:flex justify-center mb-4 sm:mb-0 hidden">
                @livewire('Usuario.Create')
            </div>
            @endif
        </div>
        @if ($datos->count())
        <div class="px-6 max-h-[70vh] overflow-y-auto">
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
                        <th scope="col" class=" px-6 py-3 cursor-pointer" wire:click="order('name')">
                            <div class="flex items-center justify-center">
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
                            <div class="flex items-center justify-center">
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
                        <th scope="col" class="px-6 py-3 cursor-pointer" wire:click="order('no_control')">
                            <div class="flex items-center justify-center">
                                No.Control
                                @if ($sort == 'no_control')
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
                        <th scope="col" class="px-6 py-3 cursor-pointer" wire:click="order('id_estado')">
                            <div class="flex items-center justify-center">
                                Estado
                                @if ($sort == 'id_estado')
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
                        <th scope="col" class="px-6 py-3 ">
                            <div class="flex items-center justify-center">
                                Acciones
                            </div>
                        </th>
                        @endif
                    </tr>
                </thead>
                <tbody>
                    @foreach ($datos as $dato)
                    <tr wire:key="user-{{ $dato->id }}"
                        class=" odd:bg-white odd:dark:bg-gray-900 even:bg-[#D2D9D3] even:text-blue-tec odd: text-black even:dark:bg-gray-800 border-b dark:border-gray-700">
                        <th scope="row" class="px-6 py-2 text-center font-medium  whitespace-nowrap dark:text-white">
                            {{ $dato->alumnos->id }}
                        </th>
                        <td class="px-6 py-2 text-center font-medium  whitespace-nowrap dark:text-white">
                            {{ $dato->name }}
                        </td>
                        <td class="px-6 py-2 text-center font-medium  whitespace-nowrap dark:text-white">
                            {{ $dato->email }}
                        </td>
                        <td class="px-6 py-2 text-center font-medium  whitespace-nowrap dark:text-white cursor-pointer"
                            wire:click="mostrarDetalle({{ $dato->id_ss }})">
                            {{ $dato->alumnos->no_control }}
                        </td>
                        <td class="px-6 py-2 text-center font-medium  whitespace-nowrap dark:text-white">
                            {{ $dato->estado->nombre }}
                        </td>
                        @if ($Gerente != 7)
                        <td class="px-6 py-2 flex justify-center items-center gap-2">
                            @livewire('Usuario.edit', ['dato' => $dato], key('edit-' . $dato->id))

                            <a class="bg-red-600 hover:bg-red-500 pt-2 pb-1 px-2 rounded-md cursor-pointer"
                                wire:click="$dispatch('destroy', { id: {{ $dato->id }}, nombre: '{{ $dato->name }}' })">
                                <span class="material-symbols-outlined text-white">
                                    delete
                                </span>
                            </a>
                            @livewire('Usuario.PasswordReset', ['dato' => $dato], key('PasswordReset-' . $dato->id))
                        </td>
                        @endif
                    </tr>
                    @endforeach

                </tbody>
            </table>
        </div>
        @if ($mostrarModal)
        <div x-data="{ open: true }" x-show="open" @close-modal.window="open = false"
            x-transition:enter="transition ease-out duration-300 transform"
            x-transition:enter-start="opacity-0 translate-y-4" x-transition:enter-end="opacity-100 translate-y-0"
            x-transition:leave="transition ease-in duration-200 transform"
            x-transition:leave-start="opacity-100 translate-y-0" x-transition:leave-end="opacity-0 translate-y-4"
            class="fixed inset-0 flex items-center justify-center z-50 bg-gray-900 bg-opacity-50 backdrop-blur-sm">

            <!-- Modal Content -->
            <div class="bg-white dark:bg-gray-800 rounded-lg shadow-lg w-11/12 sm:w-max overflow-y-auto p-4">

                <div class="flex p-4 justify-between items-center">
                    <div class="flex flex-col">
                        <h2 class="text-black dark:text-white">Datos del alumno.</h2>
                        <h2 class="text-black dark:text-white">{{ $alumnoSeleccionado->no_control ?? 'Cargando...' }}
                        </h2>
                    </div>
                    <button wire:click="cerrarModal" @click="open = false"
                        class="bg-red-500 hover:bg-red-600 text-white font-bold py-2 px-4 rounded">
                        Cerrar
                    </button>
                </div>
                @livewire('usuario.alumno-detalle', ['id' => $AlumnoId])
            </div>
        </div>
        @endif
        @else
        <div class="px-6 py-4">
            @if ($search)
            <p class="bg-white px-6 py-4">
                No hay resultados que coincidan con su busqueda.
            </p>
            @elseif ($SelectEncargado && $encargados2)
            <p class="bg-white px-6 py-4 text-center">
                El encargado <br>
                {{ $encargados2->nombre }} {{ $encargados2->apellido_p }} {{ $encargados2->apellido_m }} <br>
                actualmente no cuenta con alumnos de servicio social.
            </p>
            @else
            <p class="bg-white px-6 py-4 text-center">
                Actualmente no hay datos registrados en la tabla.
            </p>
            @endif
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