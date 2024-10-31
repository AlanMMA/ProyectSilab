<x-app-layout>
    <x-slot name="header">
        <div class="w-full flex justify-between">
            <h2 class="font-semibold text-xl text-gray-900 dark:text-white leading-tight">
                {{__('Recibir prestamo')}}
            </h2>
            
        </div>
    </x-slot>

    <div class="py-6 sm:py-12">
        <div class="max-w-full md:max-w-5xl lg:max-w-7xl mx-auto sm:px-4 md:px-6 lg:px-8">
           @livewire('prestamo.up-prestamo')
        </div>
    </div>

</x-app-layout>