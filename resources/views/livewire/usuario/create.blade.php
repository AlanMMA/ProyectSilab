<div>
    <x-danger-button wire:click="$set('open', 'true')">
        Agregar
    </x-danger-button>


    <x-dialog-modal wire:model.live="open">
        <x-slot name="title">
            Agregar un nuevo usuario
        </x-slot>

        <x-slot name="content">
            <div class="mb-4">
                <x-label value="Nombre:"></x-label>
                <x-input wire:model="name" wire:keyup="update('name')" type="text" value="{{old('name')}}" class="w-full"></x-input>
                <x-input-error for="name"></x-input-error>
            </div>
            <div class="mb-4">
                <x-label value="Correo electronico:"></x-label>
                <x-input wire:model="email" wire:keyup="update('email')" type="text" value="{{old('email')}}" class="w-full"></x-input>
                <x-input-error for="email"></x-input-error>
            </div>
            <div class="mb-4">
                <x-label value="Contraseña:"></x-label>

                <div class="relative w-full flex items-center">

                    <x-input id="password" wire:model="password" wire:keyup="update('password')" :type="$showPassword ? 'text' : 'password'"
                        class="w-full pr-12"></x-input>

                    <button type="button" wire:click="togglePasswordVisibility"
                        class="ml-auto px-3 flex items-center focus:outline-none">
                        @if ($showPassword)
                            <span class="material-symbols-outlined ">
                                visibility
                            </span>
                        @else
                            <span class="material-symbols-outlined">
                                visibility_off
                            </span>
                        @endif
                    </button>
                </div>
                <x-input-error for="password"></x-input-error>
            </div>

            <div class="mb-4">
                <x-label value="Asignar un rol:"></x-label>
                <select name="id_rol" id="id_rol-{{ $dato['id'] ?? 'new' }}" wire:model="id_rol"
                    class="border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 focus:border-indigo-500 dark:focus:border-indigo-600 focus:ring-indigo-500 dark:focus:ring-indigo-600 rounded-md shadow-sm">
                    @foreach ($roles as $id => $nombre)
                        <option value="{{ $id }}">{{ $nombre }}</option>
                    @endforeach
                </select>
                @error('id_rol')
                    <span class="text-red-500 text-sm">{{ $message }}</span>
                @enderror
            </div>

            <div class="mb-4">
                <x-label value="Encargado:"></x-label>
                @auth
                    <x-input type="text" class="w-full"
                        value="{{ $nombreE }} {{ $apellido_p }} {{ $apellido_m }} = {{ auth()->user()->id_encargado }}"
                        disabled></x-input>
                    <input type="hidden" wire:model="id_encargado" value="{{ auth()->user()->id }}">
                @endauth
            </div>
        </x-slot>

        <x-slot name="footer">
            <div class="gap-4">
                <x-secondary-button class="h-full" wire:click="$set('open',false)">
                    Cancel
                </x-secondary-button>

                <x-danger-button wire:click="save" wire:loading.remove wire:target="save">
                    Agregar

                </x-danger-button>
                <span wire:loading wire:target="save">Cargando ...</span>
            </div>
        </x-slot>
    </x-dialog-modal>
</div>
