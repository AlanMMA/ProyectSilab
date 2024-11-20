<div>
    <a wire:click="openModal"
        class="material-symbols-outlined  font-bold text-white py-2 px-2 rounded cursor-pointer bg-yellow-400 hover:bg-yellow-300">
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
                <x-input type="text" wire:model="result.nombre" class="w-full mt-2"></x-input>
                <x-input-error for="result.nombre"></x-input-error>
            </div>
            <div class="mb-4">
                <x-label value="Apellido paterno:"></x-label>
                <x-input type="text" wire:model="result.apellido_pS" class="w-full mt-2"></x-input>
                <x-input-error for="result.apellido_pS"></x-input-error>
            </div>
            <div class="mb-4">
                <x-label value="Apellido materno:"></x-label>
                <x-input type="text" wire:model="result.apellido_mS" class="w-full mt-2"></x-input>
                <x-input-error for="result.apellido_mS"></x-input-error>
            </div>
            <div class="mb-4">
                <x-label value="Numero de control:"></x-label>
                <x-input type="text" wire:model="result.no_control" class="w-full mt-2"></x-input>
                <x-input-error for="result.no_control"></x-input-error>
            </div>
            <div class="mb-4">
                <x-label value="correo electronico:"></x-label>
                <x-input type="text" disabled wire:model="dato.email" class="w-full mt-2"></x-input>
            </div>
            <div class="mb-4">
                <x-label value="Estado del usuario:"></x-label>
                <select wire:model.live="dato.id_estado"
                    class="border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 focus:border-indigo-500 dark:focus:border-indigo-600 focus:ring-indigo-500 dark:focus:ring-indigo-600 rounded-md shadow-sm">
                    @foreach ($estados as $estado)
                    <option value="{{ $estado->id }}">{{$estado->nombre}}</option>
                    @endforeach
                </select>
                <x-input-error for="dato.id_estado"></x-input-error>
            </div>

            {{-- <div class="mb-4">
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
            @endif --}}
        </x-slot>

        <x-slot name="footer">
            <div class="gap-4">
                <x-secondary-button class="h-full" wire:click="$set('open',false)">
                    Cancel
                </x-secondary-button>

                <x-confirm-button wire:click="confirmSave" wire:loading.remove wire:target="confirmSave">
                    Editar
                </x-confirm-button>
                <span wire:loading wire:target="save">Cargando ...</span>
            </div>
        </x-slot>
    </x-dialog-modal>

    @push('js')
    <script>
        Livewire.on('showConfirmation', (mensaje) => {
                Swal.fire({
                    title: "¿Estás seguro de editar el registro?",
                    html: mensaje,
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
            });
    </script>
    @endpush

    @push('js')
    <script>
        document.addEventListener('DOMContentLoaded', () => {
            Livewire.on('alert1', message => {
                setTimeout(() => {
                    Swal.fire({
                        title: 'Alerta',
                        text: message,
                        icon: 'warning',
                        confirmButtonText: 'OK'
                    });
                }, 0);
            });

        });
    </script>
    @endpush

</div>