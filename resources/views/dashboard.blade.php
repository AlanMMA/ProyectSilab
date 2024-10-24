<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Dashboard') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-xl sm:rounded-lg">
                <div class="w-full flex flex-col justify-center items-center p-4">
                        <p class="text-blue-tec text-7xl dark:neon-light">SILAB</p>                 
                      
                    <div>
                        <p class="text-[#1B396A] dark:text-white">Sistemas de Inventario de LABoratorios</p>
                    </div>
                    <div class="">
                        @php
                            $horario = now()->setTimezone('America/Mexico_City')->subHours(1)->format('H:i');
                        @endphp

                        @if ($horario >= '6:00' && $horario <= '11:59')
                            <p class="text-[#1B396A] dark:text-white">Buenos días</p>
                        @elseif ($horario >= '12:00' && $horario <= '17:59')
                            <p class="text-[#1B396A] dark:text-white">Buenas tardes</p>
                        @else
                            <p class="text-[#1B396A] dark:text-white">Buenas noches</p>
                        @endif

                        @auth
                            <p class="text-red-600">Bienvenido usuario {{ auth()->user()->name }}</p>
                        @endauth

                        
                    </div>
                </div>
            </div>
        </div>
        
    </div>
</x-app-layout>
