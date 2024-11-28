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
                <x-label value="Asignar una marca:"></x-label>
                <select name="id_marca" id="id_marca-{{ $dato['id'] ?? 'new' }}" wire:model.live="dato.id_marca"
                    class="border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 focus:border-indigo-500 dark:focus:border-indigo-600 focus:ring-indigo-500 dark:focus:ring-indigo-600 rounded-md shadow-sm">
                    <option value="0">Elija la marca del producto</option>
                    @foreach ($marcas as $id => $nombre)
                    <option value="{{ $id }}">{{ $id }} {{ $nombre }}</option>
                    @endforeach
                </select>
                @error('dato.id_marca')
                <span class="text-red-500 text-sm">{{ $message }}</span>
                @enderror
            </div>
            <div class="mb-4">
                <x-label value="Modelo:"></x-label>
                <x-input wire:model="dato.modelo" wire:keyup="update('dato.modelo')" type="text" class="w-full">
                </x-input>
                @error('dato.modelo')
                <span class="text-red-500 text-sm">{{$message}}</span>
                @enderror
            </div>
            <div class="mb-4">
                <x-label value="Asignar una categoria:"></x-label>
                <select name="id_categoria" id="id_categoria-{{ $dato['id'] ?? 'new' }}"
                    wire:model.live="dato.id_categoria"
                    class="border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 focus:border-indigo-500 dark:focus:border-indigo-600 focus:ring-indigo-500 dark:focus:ring-indigo-600 rounded-md shadow-sm">
                    <option value="0">Elija la categoria del material</option>
                    @foreach ($categorias as $id => $nombre)
                    <option value="{{ $id }}">{{ $id }} {{ $nombre }}</option>
                    @endforeach
                </select>
                @error('dato.id_categoria')
                <span class="text-red-500 text-sm">{{ $message }}</span>
                @enderror
            </div>
            <div class="mb-4">
                <x-label value="Stock:"></x-label>
                <x-input wire:model="dato.stock" disabled wire:keyup="update('dato.stock')" type="text" class="w-full">
                </x-input>
                @error('dato.stock')
                <span class="text-red-500 text-sm">{{$message}}</span>
                @enderror
            </div>
            <div class="mb-4">
                <x-label value="Descripcion:"></x-label>
                <x-input wire:model="dato.descripcion" wire:keyup="update('dato.descripcion')" type="text"
                    class="w-full">
                </x-input>
                @error('dato.descripcion')
                <span class="text-red-500 text-sm">{{$message}}</span>
                @enderror
            </div>
            <div class="mb-4">
                <x-label value="Asignar una categoria:"></x-label>
                <select name="id_localizacion" id="id_localizacion-{{ $dato['id'] ?? 'new' }}"
                    wire:model.live="dato.id_localizacion"
                    class="border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 focus:border-indigo-500 dark:focus:border-indigo-600 focus:ring-indigo-500 dark:focus:ring-indigo-600 rounded-md shadow-sm">
                    <option value="0">Elija una localizacion</option>
                    @foreach ($localizaciones as $localizacion)
                    <option value="{{ $localizacion->id }}">{{ $localizacion->id }} {{ $localizacion->nombre }}</option>
                    @endforeach
                </select>
                @error('dato.id_localizacion')
                <span class="text-red-500 text-sm">{{ $message }}</span>
                @enderror
            </div>
            <div class="mb-4">
                <x-label value="Encargado actual:"></x-label>
                <x-input value="{{ $dato['id_encargado'] }} - {{ $nombre_completo[$dato['id_encargado']] }}" type="text"
                    class="w-full" disabled />
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
        Livewire.on('showConfirmation', () => {
            Swal.fire({
                title: "¿Estás seguro de editar el registro?",
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