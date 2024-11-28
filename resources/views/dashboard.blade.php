<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Dashboard') }}
        </h2>
    </x-slot>
    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 relative">
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-xl sm:rounded-lg">
                <div class="w-full flex flex-col justify-center items-center p-4 text-center">
                    <div class="mb-6">
                        <div>
                            <p class="text-blue-tec text-7xl dark:neon-light">SILAB</p>
                        </div>

                        <div class="text-center">
                            <p class="text-[#1B396A] dark:text-white">Sistema de Inventario de Laboratorios</p>
                        </div>
                    </div>
                    <div class="py-6 px-6 dark:bg-gray-800">
                        @php
                            $horario = now()->setTimezone('America/Mexico_City')->format('H:i');
                            $horaActual = strtotime($horario);
                            $mañana = strtotime('04:00');
                            $mañanaFin = strtotime('11:59');
                            $tade = strtotime('12:00');
                            $tardeFin = strtotime('17:59');
                        @endphp

                        @if ($horaActual >= $mañana && $horaActual <= $mañanaFin)
                            <p class="text-2xl font-semibold text-[#1B396A] dark:text-white">Buenos días</p>
                        @elseif ($horaActual >= $tade && $horaActual <= $tardeFin)
                            <p class="text-2xl font-semibold text-[#1B396A] dark:text-white">Buenas tardes</p>
                        @else
                            <p class="text-2xl font-semibold text-[#1B396A] dark:text-white">Buenas noches</p>
                        @endif
                        @auth
                            @if (auth()->user()->id_rol == 1)
                                <p class="mt-6 text-2xl font-medium text-gray-700 dark:text-gray-300">
                                    Bienvenido, encargado <br> <span class="text-red-600">{{ auth()->user()->name }}</span>
                                </p>
                            @elseif(auth()->user()->id_rol == 2)
                                <p class="mt-6 text-2xl font-medium text-gray-700 dark:text-gray-300">
                                    Bienvenido, alumno de servicio <br> <span
                                        class="text-red-600">{{ auth()->user()->name }}</span>
                                </p>
                            @elseif(auth()->user()->id_rol == 7)
                                <p class="mt-6 text-2xl font-medium text-gray-700 dark:text-gray-300">
                                    Bienvenido, jefe de departamento <br> <span
                                        class="text-red-600">{{ auth()->user()->name }}</span>
                                </p>
                            @endif
                        @endauth
                    </div>
                    <div class="w-full h-max flex sm:flex-row flex-col sm:items-start items-center gap-4">
                        <x-dashboard-card>
                            @livewire('home.card-prest')
                        </x-dashboard-card>
                        <x-dashboard-card>
                            @livewire('home.card-stock')
                        </x-dashboard-card>
                    </div>
                    <div>
                        <p class="text-[#1B396A] dark:text-white text-2xl  mt-4">Desarrolladores.</p>
                        <p class="text-black dark:text-white text-sm  mt-1">Alan Mauricio Morales Argüello.</p>
                        <p class="text-black dark:text-white text-sm  mt-1">Esdras Nehemías Morales Monjaras.</p>
                    </div>
                </div>
            </div>
        </div>

    </div>
</x-app-layout>