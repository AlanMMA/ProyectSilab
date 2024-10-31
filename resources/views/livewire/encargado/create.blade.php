<div>

    <x-confirm-button wire:click="$set('open', 'true')">
        Agregar
    </x-confirm-button>

    <x-dialog-modal wire:model.live="open">
        <x-slot name="title">
            Agregar un encargado
        </x-slot>
        <x-slot name="content">
            <div class="mb-4">
                <x-label value="Nombre:"></x-label>
                <x-input wire:model="nombre" wire:keyup="update('nombre')" type="text" class="w-full"></x-input>
                @error('nombre')
                    <span class="text-red-500 text-sm">{{ $message }}</span>
                @enderror
            </div>
            <div class="mb-4">
                <x-label value="Apellido paterno:"></x-label>
                <x-input wire:model="apellido_p" wire:keyup="update('apellido_p')" type="text"
                    class="w-full"></x-input>
                @error('apellido_p')
                    <span class="text-red-500 text-sm">{{ $message }}</span>
                @enderror
            </div>
            <div class="mb-4">
                <x-label value="Apellido materno:"></x-label>
                <x-input wire:model="apellido_m" wire:keyup="update('apellido_m')" type="text"
                    class="w-full"></x-input>
                @error('apellido_m')
                    <span class="text-red-500 text-sm">{{ $message }}</span>
                @enderror
            </div>
            <div class="mb-4">
                <x-label value="Asignar un laboratorio:" />
                <select name="id_laboratorio" id="id_laboratorio-{{ $dato['id'] ?? 'new' }}" wire:model="id_laboratorio"
                    wire:change="verificarLaboratorio"
                    class="border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 focus:border-indigo-500 dark:focus:border-indigo-600 focus:ring-indigo-500 dark:focus:ring-indigo-600 rounded-md shadow-sm">
                    <option value="0">Seleccione un laboratorio</option>
                    @foreach ($laboratorios as $id => $nombre)
                        <option value="{{ $id }}">{{ $nombre }}</option>
                    @endforeach
                </select>

                @error('id_laboratorio')
                    <span class="text-red-500 text-sm">{{ $message }}</span>
                @enderror
            </div>

            <p class="text-lg text-center text-[#111827] dark:text-white font-bold">Datos de usuario</p>

            <div class="mb-4">
                <x-label value="Usuario:"></x-label>
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
                <x-input-error for="id_rol"></x-input-error>
                <x-input-error for="id_encargado"></x-input-error>
            </div>
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


    @push('js')
        <script>
            document.addEventListener('DOMContentLoaded', () => {
                Livewire.on('alert', message => {
                    setTimeout(() => {
                        Swal.fire({
                            title: 'Alerta',
                            text: 'Este laboratorio ya está asignado a otro encargado.',
                            icon: 'warning',
                            confirmButtonText: 'OK'
                        });
                    }, 0);
                });
            });
        </script>
    @endpush
</div>
