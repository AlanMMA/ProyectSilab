<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-900 dark:text-white leading-tight">
            {{ __('Entradas al inventario') }}
        </h2>
    </x-slot>

    <div class="py-12 px-4">
        <div class="max-w-full md:max-w-5xl lg:max-w-7xl mx-auto sm:px-4 md:px-6 lg:px-8 h-full overflow-y-auto">
            @livewire('material.stock-up')
        </div>
    </div>

</x-app-layout>