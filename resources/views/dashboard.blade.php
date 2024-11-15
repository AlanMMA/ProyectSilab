<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Dashboard') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
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
                            $horario = now()->setTimezone('America/Mexico_City')->subHours(1)->format('H:i');
                        @endphp

                        @if ($horario >= '4:00' && $horario <= '11:59')
                            <p class="text-2xl font-semibold text-[#1B396A] dark:text-white">Buenos días</p>
                        @elseif ($horario >= '12:00' && $horario <= '17:59')
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
                                Bienvenido, alumno de servicio <br> <span class="text-red-600">{{ auth()->user()->name }}</span>
                            </p>
                            @elseif(auth()->user()->id_rol == 7)
                            <p class="mt-6 text-2xl font-medium text-gray-700 dark:text-gray-300">
                                Bienvenido, jefe de departamento <br> <span class="text-red-600">{{ auth()->user()->name }}</span>
                            </p>
                            @endif

                        @endauth
                        <p></p>
                    </div>
                    <div class="w-full h-auto flex sm:flex-row flex-col sm:items-start items-center gap-4">
                        <x-dashboard-card>
                           @livewire('home.card-prest')
                        </x-dashboard-card>
                        <x-dashboard-card>
                            @livewire('home.card-stock')
                        </x-dashboard-card>
                    </div>
                </div>
            </div>
        </div>

    </div>
</x-app-layout>
