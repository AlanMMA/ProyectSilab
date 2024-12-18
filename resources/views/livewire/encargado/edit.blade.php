<div>
    <a wire:click="openModal"
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
                <x-input wire:model="dato.nombre" wire:keyup="update('dato.nombre')" type="text" class="w-full">
                </x-input>
                @error('dato.nombre')
                <span class="text-red-500 text-sm">{{$message}}</span>
                @enderror
            </div>
            <div class="mb-4">
                <x-label value="Apellido paterno:"></x-label>
                <x-input wire:model="dato.apellido_p" wire:keyup="update('dato.apellido_p')" type="text" class="w-full">
                </x-input>
                @error('dato.apellido_p')
                <span class="text-red-500 text-sm">{{$message}}</span>
                @enderror
            </div>
            <div class="mb-4">
                <x-label value="Apellido materno:"></x-label>
                <x-input wire:model="dato.apellido_m" wire:keyup="update('dato.apellido_m')" type="text" class="w-full">
                </x-input>
                @error('dato.apellido_m')
                <span class="text-red-500 text-sm">{{$message}}</span>
                @enderror
            </div>
            {{-- <div class="mb-4">
                <x-label value="Asignar un encargado:"></x-label>
                <select name="id_laboratorio" id="id_laboratorio-{{ $dato['id'] ?? 'new' }}"
                    wire:model="dato.id_laboratorio"
                    class="border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 focus:border-indigo-500 dark:focus:border-indigo-600 focus:ring-indigo-500 dark:focus:ring-indigo-600 rounded-md shadow-sm">
                    @foreach ($laboratorios as $id => $nombre)
                    <option value="{{ $id }}">{{ $id }} {{ $nombre }}</option>
                    @endforeach
                </select>
                @error('dato.id_laboratorio')
                <span class="text-red-500 text-sm">{{ $message }}</span>
                @enderror
            </div> --}}
            <div class="mb-4">
                <x-label value="Asignar un laboratorio:" />
                <select name="id_laboratorio" id="id_laboratorio-{{ $dato['id'] ?? 'new' }}"
                    wire:model="dato.id_laboratorio" wire:change="verificarLaboratorio"
                    class="border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 focus:border-indigo-500 dark:focus:border-indigo-600 focus:ring-indigo-500 dark:focus:ring-indigo-600 rounded-md shadow-sm">
                    <option value="">Seleccione un laboratorio</option>
                    @foreach ($laboratorios as $id => $nombre)
                    <option value="{{ $id }}">{{ $nombre }}</option>
                    @endforeach
                </select>

                @error('dato.id_laboratorio')
                <span class="text-red-500 text-sm">{{ $message }}</span>
                @enderror
            </div>
            <div class="mb-4">
                <x-label value="Estado del usuario:"></x-label>
                <select wire:model.live="result.id_estado"
                    class="border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 focus:border-indigo-500 dark:focus:border-indigo-600 focus:ring-indigo-500 dark:focus:ring-indigo-600 rounded-md shadow-sm">
                    @foreach ($estados as $estado)
                        <option value="{{ $estado->id }}">{{$estado->nombre}}</option>
                    @endforeach
                </select>
                <x-input-error for="dato.id_estado"></x-input-error>
            </div>

            <div class="mb-4">
                <x-label value="Usuario:"></x-label>
                <x-input wire:model="result.name" wire:keyup="update('result.name')" type="text" class="w-full" disabled>
                </x-input>
                @error('result.name')
                <span class="text-red-500 text-sm">{{$message}}</span>
                @enderror
            </div>
            <div class="mb-4">
                <x-label value="Correo electronico:"></x-label>
                <x-input wire:model="result.email" wire:keyup="update('result.email')" type="text" class="w-full" disabled>
                </x-input>
                @error('result.email')
                <span class="text-red-500 text-sm">{{$message}}</span>
                @enderror
            </div>

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

            Livewire.on('alert2', message => {
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

</div>