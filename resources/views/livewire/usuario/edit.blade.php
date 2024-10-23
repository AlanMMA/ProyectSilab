<div>
    <a wire:click="$set('open', true)"
        class="material-symbols-outlined  font-bold text-white py-2 px-2 rounded cursor-pointer bg-yellow-500">
        <span class="material-symbols-outlined">
            edit
        </span>
    </a>


    <x-dialog-modal wire:model="open">
        <x-slot name="title">
            Editar el registro
        </x-slot>

        <x-slot name="content">
            <div class="mb-4">
                <x-label value="Nombre:"></x-label>
                <x-input type="text" wire:model="dato.name" class="w-full mt-2"></x-input>
                <x-input-error for="dato.name"></x-input-error>
            </div>
            <div class="mb-4">
                <x-label value="Correo electronico:"></x-label>
                <x-input type="text" wire:model="dato.email" class="w-full mt-2"></x-input>
                <x-input-error for="dato.email"></x-input-error>
            </div>
            <div class="mb-4">
                <x-label value="Asignar un rol:"></x-label>
                <select name="id_rol" wire:model.live="dato.id_rol"
                    class="border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 focus:border-indigo-500 dark:focus:border-indigo-600 focus:ring-indigo-500 dark:focus:ring-indigo-600 rounded-md shadow-sm">
                    <option value="0">Seleccione un rol</option>
                    @foreach ($roles as $id => $nombre)
                    <option value="{{ $id }}">{{ $nombre }}</option>
                    @endforeach
                </select>
                @error('id_rol')
                <span class="text-red-500 text-sm">{{ $message }}</span>
                @enderror
            </div>

            @if ($dato['id_rol'] == 0)

            @elseif ($dato['id_rol'] == 1)
            <div class="mb-4">
                <x-label value="Encargado:"></x-label>
                <select name="id_encargado" wire:model="dato.id_encargado"
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
                <input type="hidden" wire:model="dato.id_encargado" value="{{ auth()->user()->id }}">
                @endauth
            </div>
            @endif

        </x-slot>

        <x-slot name="footer">
            <div class="gap-4">
                <x-secondary-button class="h-full" wire:click="$set('open',false)">
                    Cancel
                </x-secondary-button>

                <x-confirm-button onclick="confirmSave('{{ $dato['name'] }}')" wire:loading.remove wire:target="save">
                    Editar
                </x-confirm-button>
                <span wire:loading wire:target="save">Cargando ...</span>
            </div>
        </x-slot>
    </x-dialog-modal>

    @push('js')
    <script>
        function confirmSave(name) {
            Swal.fire({
                title: "¿Estás seguro de editar el registro?",
                text: "Registro: " + name,
                icon: "warning",
                showCancelButton: true,
                confirmButtonColor: "#3085d6",
                cancelButtonColor: "#d33",
                confirmButtonText: "Aceptar",
                cancelButtonText: "Cancelar"
            }).then((result) => {
                if (result.isConfirmed) {
                    Livewire.dispatch('saveConfirmed');
                }
            });
        }

        Livewire.on('saveConfirmed', () => {
            @this.call('save');
        });
    </script>
    @endpush

</div>