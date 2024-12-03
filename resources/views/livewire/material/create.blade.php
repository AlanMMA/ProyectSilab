<div>
    <x-confirm-button wire:click="$set('open', 'true')">
        Agregar
    </x-confirm-button>
    <x-dialog-modal wire:model.live="open">
        <x-slot name="title">
            Agregar material
        </x-slot>
        <x-slot name="content">
            <div class="mb-4">
                <x-label value="Nombre:"></x-label>
                <x-input wire:model="nombre" wire:keyup="update('nombre')" type="text" class="w-full"></x-input>
                @error('nombre')
                <span class="text-red-500 text-sm">{{$message}}</span>
                @enderror
            </div>
            <div class="mb-4">
                <x-label value="Elegir marca:"></x-label>
                <select name="id_marca" id="id_marca-{{ $dato['id'] ?? 'new' }}" wire:model.live="id_marca"
                    class="border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 focus:border-indigo-500 dark:focus:border-indigo-600 focus:ring-indigo-500 dark:focus:ring-indigo-600 rounded-md shadow-sm">
                    <option value="0">Elija la marca del material</option>
                    @foreach ($marcas as $id => $nombre)
                    <option value="{{ $id }}">{{ $id }} {{ $nombre }}</option>
                    @endforeach
                </select>
                @error('id_marca')
                <span class="text-red-500 text-sm">{{ $message }}</span>
                @enderror
            </div>
            <div class="mb-4">
                <x-label value="Modelo:"></x-label>
                <x-input wire:model="modelo" wire:keyup="update('modelo')" type="text" class="w-full"></x-input>
                @error('modelo')
                <span class="text-red-500 text-sm">{{$message}}</span>
                @enderror
            </div>
            <div class="mb-4">
                <x-label value="Elegir categoria:"></x-label>
                <select name="id_categoria" id="id_categoria-{{ $dato['id'] ?? 'new' }}" wire:model.live="id_categoria"
                    class="border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 focus:border-indigo-500 dark:focus:border-indigo-600 focus:ring-indigo-500 dark:focus:ring-indigo-600 rounded-md shadow-sm">
                    <option value="0">Elija la categoria del material</option>
                    @foreach ($categorias as $id => $nombre)
                    <option value="{{ $id }}">{{ $id }} {{ $nombre }}</option>
                    @endforeach
                </select>
                @error('id_categoria')
                <span class="text-red-500 text-sm">{{ $message }}</span>
                @enderror
            </div>
            <div class="mb-4">
                <x-label value="Stock:"></x-label>
                <x-input wire:model="stock" wire:keyup="update('stock')" type="number" min="1" class="w-full"></x-input>
                @error('stock')
                <span class="text-red-500 text-sm">{{$message}}</span>
                @enderror
            </div>
            <div class="mb-4">
                <x-label value="Descripcion:"></x-label>
                <x-input wire:model="descripcion" wire:keyup="update('descripcion')" type="text" class="w-full">
                </x-input>
                @error('descripcion')
                <span class="text-red-500 text-sm">{{$message}}</span>
                @enderror
            </div>
            <div class="mb-4">
                <x-label value="Elegir localización:"></x-label>
                <select name="id_localizacion" id="id_localizacion-{{ $dato['id'] ?? 'new' }}"
                    wire:model.live="id_localizacion"
                    class="border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 focus:border-indigo-500 dark:focus:border-indigo-600 focus:ring-indigo-500 dark:focus:ring-indigo-600 rounded-md shadow-sm">
                    <option value="0">Elija la localizacion del producto</option>
                    @foreach ($localizaciones as $localizacion)
                    <option value="{{ $localizacion->id }}">{{ $localizacion->nombre }}</option>
                    @endforeach
                </select>
                @error('id_localizacion')
                <span class="text-red-500 text-sm">{{ $message }}</span>
                @enderror
            </div>
            <div class="mb-4">
                <x-label value="Encargado:"></x-label>
                @auth
                <x-input type="text" class="w-full"
                    value="{{ $nombreE }} {{ $apellido_p }} {{ $apellido_m }} = {{ auth()->user()->name }}" disabled>
                </x-input>
                <input type="hidden" wire:model="id_encargado" value="{{ $id_encargado }}">
                @endauth
            </div>
        </x-slot>
        <x-slot name="footer">
            <div class="gap-4">
                <x-secondary-button class="h-full" wire:click="$set('open',false)">
                    Cancel
                </x-secondary-button>
                <x-confirm-button wire:click="confirmSave2" wire:loading.remove wire:target="confirmSave2">
                    Agregar
                </x-confirm-button>
                <span wire:loading wire:target="save">Cargando ...</span>
            </div>
        </x-slot>
    </x-dialog-modal>

    @push('js')
    <script>
        Livewire.on('showConfirmation2', () => {
            Swal.fire({
                title: "¿Estás seguro de agregar este registro?",
                icon: "question",
                showCancelButton: true,
                confirmButtonColor: "#3085d6",
                cancelButtonColor: "#d33",
                confirmButtonText: "Aceptar",
                cancelButtonText: "Cancelar"
            }).then((result) => {
                if (result.isConfirmed) {
                    Livewire.dispatch('saveConfirmed2');
                }
            });
        });
    </script>
    @endpush

</div>