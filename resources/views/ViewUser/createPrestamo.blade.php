<x-app-layout>
    <x-slot name="header">
        <div class="w-full h-full flex justify-center items-center">
            <h2 class="font-extrabold text-xl text-black dark:text-white leading-tight">
                {{ __('Realizando prestamo') }}
            </h2>
        </div>
    </x-slot>

    <div class="mt-6 mb-6">
        <div class="max-w-full md:max-w-5xl lg:max-w-7xl mx-auto sm:px-4 md:px-6 lg:px-8 h-full overflow-y-auto">
            <div class="flex gap-6">
                @livewire('Prestamo.CreateForm')
                @livewire('Prestamo.CreateTable')
            </div>
        </div>
    </div>
    </div>

</x-app-layout>
