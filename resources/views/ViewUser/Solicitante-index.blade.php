
<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-white leading-tight">
            {{__('Solicitantes')}}
        </h2>
    </x-slot>

    <div class="py-6 sm:py-12">
        <div class="max-w-full md:max-w-5xl lg:max-w-7xl mx-auto sm:px-4 md:px-6 lg:px-8">
            @livewire('solicitante.index')
        </div>
    </div>

</x-app-layout>