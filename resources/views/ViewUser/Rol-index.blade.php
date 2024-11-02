<x-app-layout>
    <x-slot name="header">
        <div class="w-full h-full flex justify-center items-center">
            <h2 class="font-extrabold text-xl text-black dark:text-white leading-tight">
                {{ __('Roles') }}
            </h2>
        </div>
    </x-slot>

    <div class="py-6">
        <div class="max-w-full md:max-w-5xl lg:max-w-7xl mx-auto sm:px-4 md:px-6 lg:px-8 h-full overflow-y-auto">
            @livewire('rol.index')
        </div>
    </div>

</x-app-layout>