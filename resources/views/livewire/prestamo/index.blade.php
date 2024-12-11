<div class="h-full overflow-y-auto">
    @php
    $Gerente = auth()->user()->id_rol;
    @endphp
    @if ($Gerente != 7 && $datos->count() >= 2)
    <div class="relative inline-block text-right w-full px-6">
        <!-- Botón que despliega el menú -->
        <button class="bg-red-500 hover:bg-red-600 text-white font-bold py-2 px-4 rounded" onclick="toggleMenu(event)">
            Eliminar prestamos
        </button>
        <!-- Menú desplegable -->
        <div id="dropdownMenu"
            class="mr-6 hidden absolute right-0 mt-2 w-64 bg-white dark:bg-gray-800 border border-gray-300 dark:border-gray-600 rounded-lg shadow-lg z-50">
            <div class="flex justify-center pt-2">
                <label class="block text-base font-medium text-gray-700 dark:text-gray-200">Eliga un
                    rango
                    de fechas:</label>
            </div>
            <div class="p-4">
                <label for="fechaInicial" class="block text-sm font-medium text-gray-700 dark:text-gray-200">Fecha
                    Inicial:</label>
                <input type="date" id="fechaInicial" wire:model="fechaInicial"
                    class="mt-1 block w-full rounded-md border-gray-300 dark:border-gray-600 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 dark:bg-gray-700 dark:text-white sm:text-sm">

                <label for="fechaFinal" class="block text-sm font-medium text-gray-700 dark:text-gray-200 mt-4">Fecha
                    Final:</label>
                <input type="date" id="fechaFinal" wire:model="fechaFinal"
                    class="mt-1 block w-full rounded-md border-gray-300 dark:border-gray-600 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 dark:bg-gray-700 dark:text-white sm:text-sm">

                <button onclick="confirmDeletion()"
                    class="mt-4 bg-red-600 hover:bg-red-700 text-white font-bold py-2 px-4 rounded w-full">
                    Eliminar Registros
                </button>
            </div>
        </div>
    </div>
    @endif
    <div class="relative shadow-md">
        @if ($Gerente == 7)
        <div class="py-4 px-6 flex flex-col w-full justify-end items-center gap-4 sm:flex-row">
            <p class="text-lg font-bold text-black dark:text-white">Préstamos del laboratorio:</p>
            <select name="" wire:model.live="SelectEncargado"
                class="w-min border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 focus:border-indigo-500 dark:focus:border-indigo-600 focus:ring-indigo-500 dark:focus:ring-indigo-600 rounded-md shadow-sm">
                <option value="0">Elija un laboratorio</option>
                <option value="-1">Mostrar todos los préstamos</option>
                @foreach ($labs as $lab)
                <option value="{{ $lab->id }}">{{ $lab->id }} {{ $lab->nombre }}</option>
                @endforeach
            </select>
        </div>
        @endif
        <div class="py-4 px-6 block items-center gap-4 w-full sm:flex ">
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
                    <x-confirm-button :href="route('prestamosc')">Agregar</x-confirm-button>
                </div>
                @endif
            </div>
            <x-input class="sm:flex-1 w-full mb-4 sm:mb-0" name="search" placeholder="¿Qué desea buscar?"
                wire:model.live="search" type="text"></x-input>
            @if ($Gerente != 7)
            <div class="w-auto sm:flex justify-center mb-4 sm:mb-0 hidden">
                <x-confirm-button :href="route('prestamosc')">Agregar</x-confirm-button>
            </div>
            @endif
        </div>
        <div x-data="{ open: false }"
            class="relative inline-block text-left py-4 px-6 items-center gap-4 w-full sm:-my-px">
            <button @click="open = !open"
                class="bg-blue-600 hover:bg-blue-500 text-white py-2 px-4 rounded-md inline-flex items-center">
                Exportar
                <svg class="w-4 h-4 ml-2 -mr-1" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                    stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
                </svg>
            </button>
            <div x-show="open" @click.away="open = false"
                class="absolute mt-2 bg-white border border-gray-200 divide-y divide-gray-100 rounded-md shadow-lg outline-none">
                <a href="{{ route('prestamos.pdf', [
                    'search' => $search ?? '',
                    'sort' => $sort ?? 'id',
                    'direc' => $direc ?? 'asc',
                    'cant' => $cant ?? 10,
                    'encargado' => $SelectEncargado ?? '',
                ]) }}" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100 hover:text-gray-900"
                    @click="open = false">
                    Exportar a PDF
                </a>
            </div>
        </div>
        @if ($datos->count())
        <div class="px-6 mb-4 overflow-y-auto max-h-[60vh] sm:max-h-full">
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
                            wire:click="order('solicitante_nombre')">
                            <div class="flex items-center justify-center">
                                Solicitante
                                @if ($sort == 'solicitante_nombre')
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
                        @if ($SelectEncargado == -1)
                        <th scope="col" class="cursor-pointer px-6 py-3 text-center"
                            wire:click="order('encargado_nombre')">
                            <div class="flex items-center justify-center">
                                Encargado
                                @if ($sort == 'encargado_nombre')
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
                            wire:click="order('laboratorio_nombre')">
                            <div class="flex items-center justify-center">
                                Laboratorio
                                @if ($sort == 'laboratorio_nombre')
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
                        @endif
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
                            {{ $dato->solicitante_nombre }} {{ $dato->solicitante_apellido_p }}
                            {{ $dato->solicitante_apellido_m }}
                        </td>
                        <td class="px-6 py-2 text-center font-medium  whitespace-nowrap dark:text-white">
                            {{ $dato->solicitante_tipo }}
                        </td>
                        @if ($SelectEncargado == -1)
                        <td class="px-6 py-2 text-center font-medium  whitespace-nowrap dark:text-white">
                            {{ $dato->encargado_nombre }} {{ $dato->encargado_apellido_p }}
                            {{ $dato->encargado_apellido_m }}
                        </td>
                        <td class="px-6 py-2 text-center font-medium  whitespace-nowrap dark:text-white">
                            {{ $dato->laboratorio_nombre }}
                        </td>
                        @endif
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
                            <a class="bg-red-600 hover:bg-red-500 pt-2 pb-1 px-2 rounded-md cursor-pointer"
                                wire:click="$dispatch('destroy', { id: {{ $dato->id }}, nombre: '{{ $dato->id }}' })">
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
        @if ($mostrarModal)
        <!-- Modal Overlay -->
        <div x-data="{ open: true }" x-show="open" @close-modal.window="open = false"
            x-transition:enter="transition ease-out duration-300 transform"
            x-transition:enter-start="opacity-0 translate-y-4" x-transition:enter-end="opacity-100 translate-y-0"
            x-transition:leave="transition ease-in duration-200 transform"
            x-transition:leave-start="opacity-100 translate-y-0" x-transition:leave-end="opacity-0 translate-y-4"
            class="fixed inset-0 flex items-center justify-center z-50 bg-gray-900 bg-opacity-50 backdrop-blur-sm">

            <!-- Modal Content -->
            <div class="bg-white dark:bg-gray-800 rounded-lg shadow-lg w-11/12 overflow-y-auto p-4">
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
        <div class="px-6 py-4">
            @if ($search)
            <p class="bg-white px-6 py-4 text-center">
                No hay resultados que coincidan con su búsqueda.
            </p>
            @elseif ($SelectEncargado && $encargados2)
            <p class="bg-white px-6 py-4 text-center">
                {{-- El encargado <br>
                {{ $encargados2->nombre }} {{ $encargados2->apellido_p }} {{ $encargados2->apellido_m }} <br>
                actualmente no cuenta con préstamos realizados. --}}
                El laboratorio no cuenta con préstamos.
            </p>
            @elseif (!$search && !$SelectEncargado && $Gerente == 7)
            <p class="bg-white px-6 py-4 text-center">
                Primero seleccione un laboratorio para ver su información.
            </p>
            @else
            <p class="bg-white px-6 py-4 text-center">
                Actualmente no hay datos en esta tabla.
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
        @if (session('error'))
                console.log('Mensaje de error capturado');
                Swal.fire({
                    icon: 'error',
                    title: 'Error',
                    text: "{{ session('error') }}",
                    confirmButtonText: 'Aceptar'
                });
            @endif
    </script>
    @endpush

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
                    Livewire.dispatch('proceedDestroy', { id: event.id });
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



    {{--
    @push('js')
    <script>
        function confirmDeletion() {
                const fechaInicial = document.getElementById('fechaInicial').value;
                const fechaFinal = document.getElementById('fechaFinal').value;

                if (!fechaInicial || !fechaFinal) {
                    Swal.fire({
                        icon: 'warning',
                        title: 'Campos vacíos',
                        text: 'Por favor, completa ambas fechas antes de proceder.',
                    });
                    return;
                }

                if (fechaInicial > fechaFinal) {
                    Swal.fire({
                        icon: 'warning',
                        title: 'Fechas incompatibles.',
                        text: 'La fecha inicial no puede ser mayor a la fecha final.',
                    });
                    return;
                }


                Livewire.dispatch('deleteByDateRange', fechaInicial, fechaFinal);

                Swal.fire({
                    title: '¿Estás seguro?',
                    text: `Esto eliminará los registros entre ${fechaInicial} y ${fechaFinal}.`,
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#d33',
                    cancelButtonColor: '#3085d6',
                    confirmButtonText: 'Sí, eliminar',
                    cancelButtonText: 'Cancelar',
                }).then((result) => {
                    if (result.isConfirmed) {
                        // Aquí puedes invocar un método Livewire o realizar una solicitud
                        @this.call('deleteByDateRange', fechaInicial, fechaFinal);
                        Swal.fire('Eliminado', 'Los registros han sido eliminados.', 'success');
                    }
                });
            }
    </script>
    @endpush --}}

    @push('js')
    <script>
        function confirmDeletion() {
                const fechaInicial = document.getElementById('fechaInicial').value;
                const fechaFinal = document.getElementById('fechaFinal').value;



                if (!fechaInicial || !fechaFinal) {
                    Swal.fire({
                        icon: 'warning',
                        title: 'Campos vacíos',
                        text: 'Por favor, completa ambas fechas antes de proceder.',
                    });
                    return;
                }

                if (fechaInicial > fechaFinal) {
                    Swal.fire({
                        icon: 'warning',
                        title: 'Fechas incompatibles.',
                        text: 'La fecha inicial no puede ser mayor a la fecha final.',
                    });
                    return;
                }

                // Aquí se muestra la alerta de confirmación antes de invocar el método de eliminación
                Swal.fire({
                    title: '¿Estás seguro?',
                    text: `Esto eliminará los registros entre ${fechaInicial} y ${fechaFinal}.`,
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#d33',
                    cancelButtonColor: '#3085d6',
                    confirmButtonText: 'Sí, eliminar',
                    cancelButtonText: 'Cancelar',
                }).then((result) => {
                    if (result.isConfirmed) {
                        // Solo se llama al método de eliminación si el usuario confirma
                        @this.call('deleteByDateRange', fechaInicial, fechaFinal);
                    }
                });

                Livewire.on('deletionError', (message) => {
                    Swal.fire({
                        icon: 'error',
                        title: 'Error al eliminar',
                        text: message,
                    });
                });

                Livewire.on('deletionSuccess', (message) => {
                    Swal.fire({
                        icon: 'success',
                        title: 'Registros eliminados con exito.',
                        text: message,
                    });
                });


            }
    </script>
    @endpush


    {{-- @push('js')
    <script>
        document.addEventListener('livewire:load', () => {
            // Evento para cuando no se encuentran registros
            Livewire.on('registroNoEncontrado', () => {
                Swal.fire({
                    icon: 'warning',
                    title: 'Sin registros',
                    text: 'No se encontraron registros en el rango de fechas especificado.',
                });
            });
    
            // Evento para confirmar la eliminación
            Livewire.on('confirmDeletion', (fechaInicial, fechaFinal) => {
                Swal.fire({
                    title: '¿Estás seguro?',
                    text: `Esto eliminará los registros entre ${fechaInicial} y ${fechaFinal}.`,
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#d33',
                    cancelButtonColor: '#3085d6',
                    confirmButtonText: 'Sí, eliminar',
                    cancelButtonText: 'Cancelar',
                }).then((result) => {
                    if (result.isConfirmed) {
                        Livewire.dispatch('deleteByDateRange', [fechaInicial, fechaFinal]);
                        Swal.fire('Eliminado', 'Los registros han sido eliminados.', 'success');
                    }
                });
            });
        });
    
        function confirmDeletion() {
            const fechaInicial = document.getElementById('fechaInicial').value;
            const fechaFinal = document.getElementById('fechaFinal').value;
    
            if (!fechaInicial || !fechaFinal) {
                Swal.fire({
                    icon: 'warning',
                    title: 'Campos vacíos',
                    text: 'Por favor, completa ambas fechas antes de proceder.',
                });
                return;
            }
    
            if (fechaInicial > fechaFinal) {
                Swal.fire({
                    icon: 'warning',
                    title: 'Fechas incompatibles',
                    text: 'La fecha inicial no puede ser mayor a la fecha final.',
                });
                return;
            }
    
            // Emite el evento de confirmación de eliminación, pasando las fechas
            Livewire.dispatch('confirmDeletion', fechaInicial, fechaFinal);
        }
    </script>
    @endpush
    --}}




    @push('js')
    <script>
        function toggleMenu(event) {
                const menu = document.getElementById('dropdownMenu');
                menu.classList.toggle('hidden');
            }
    </script>
    @endpush
</div>