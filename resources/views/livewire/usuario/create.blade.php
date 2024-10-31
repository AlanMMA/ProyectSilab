<div>
    <x-confirm-button wire:click="$set('open', 'true')">
        Agregar
    </x-confirm-button>


    <x-dialog-modal wire:model.live="open">
        <x-slot name="title">
            Agregar un nuevo alumno de servicio social
        </x-slot>

        <x-slot name="content">
            <div class="mb-4">
                <x-label value="Nombre:"></x-label>
                <x-input wire:model="name" wire:keyup="update('name')" type="text" value="{{ old('name') }}"
                    class="w-full"></x-input>
                <x-input-error for="name"></x-input-error>
            </div>
            <div class="mb-4">
                <x-label value="Correo electronico:"></x-label>
                <x-input wire:model="email" wire:keyup="update('email')" type="text" value="{{ old('email') }}"
                    class="w-full"></x-input>
                <x-input-error for="email"></x-input-error>
            </div>
            <div class="mb-4">
                <x-label value="Contraseña:"></x-label>

                <div class="relative w-full flex items-center">

                    <x-input wire:model="password" wire:keyup="update('password')" :type="$showPassword ? 'text' : 'password'"
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
                <x-label value="Encargado:"></x-label>
                @auth
                    <x-input type="text" class="w-full"
                        value="{{ $nombreE }} {{ $apellido_p }} {{ $apellido_m }} = {{ auth()->user()->id_encargado }}"
                        disabled></x-input>
                    <input type="hidden" wire:model="id_encargado">
                    @error('id_encargado')
                        <span class="text-red-500 text-sm">{{ $message }}</span>
                    @enderror
                @endauth
            </div>

            {{-- @if ($id_rol == 0)

            @elseif ($id_rol == 1)
                <div class="mb-4">
                    <x-label value="Encargado:"></x-label>
                    <select name="id_encargado" wire:model="id_encargado"
                        class="border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 focus:border-indigo-500 dark:focus:border-indigo-600 focus:ring-indigo-500 dark:focus:ring-indigo-600 rounded-md shadow-sm">
                        <option value="0">Seleccione a un encargado</option>
                        @foreach ($encargados as $encargado)
                            <option value="{{ $encargado->id }}">{{ $encargado->nombre }} {{ $encargado->apellido_p }}
                                {{ $encargado->apellido_m }}</option>
                        @endforeach
                    </select>
                    @error('id_encargado')
                        <span class="text-red-500 text-sm">{{ $message }}</span>
                    @enderror
                </div>
            @else
                <div class="mb-4">
                    <x-label value="Encargado:"></x-label>
                    @auth
                        <x-input type="text" class="w-full"
                            value="{{ $nombreE }} {{ $apellido_p }} {{ $apellido_m }} = {{ auth()->user()->id_encargado }}"
                            disabled></x-input>
                        <input type="hidden" wire:model="id_encargado">
                        @error('id_encargado')
                        <span class="text-red-500 text-sm">{{ $message }}</span>
                    @enderror
                    @endauth
                </div>
            @endif --}}
        </x-slot>

        <x-slot name="footer">
            <div class="gap-4">
                <x-secondary-button class="h-full" wire:click="$set('open',false)">
                    Cancel
                </x-secondary-button>

                <x-confirm-button wire:click="save" wire:loading.remove wire:target="save">
                    Agregar

                </x-confirm-button>
                <span wire:loading wire:target="save">Cargando ...</span>
            </div>
        </x-slot>
    </x-dialog-modal>
</div>
