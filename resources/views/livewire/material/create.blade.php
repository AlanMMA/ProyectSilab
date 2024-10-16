<div>
<<<<<<< HEAD
    <x-confirm-button wire:click="$set('open', 'true')">
        Agregar
    </x-confirm-button>
=======

    <x-confirm-button wire:click="$set('open', 'true')">
        Agregar
    </x-confirm-button>

>>>>>>> e690989cfa22198064767d330e771a5175501afb
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
                <select name="id_marca" id="id_marca-{{ $dato['id'] ?? 'new' }}" wire:model="id_marca"
                    class="border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 focus:border-indigo-500 dark:focus:border-indigo-600 focus:ring-indigo-500 dark:focus:ring-indigo-600 rounded-md shadow-sm">
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
                <select name="id_categoria" id="id_categoria-{{ $dato['id'] ?? 'new' }}" wire:model="id_categoria"
                    class="border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 focus:border-indigo-500 dark:focus:border-indigo-600 focus:ring-indigo-500 dark:focus:ring-indigo-600 rounded-md shadow-sm">
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
                <x-input wire:model="stock" wire:keyup="update('stock')" type="text" class="w-full"></x-input>
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
                <x-label value="Localizacion:"></x-label>
                <x-input wire:model="localizacion" wire:keyup="update('localizacion')" type="text" class="w-full">
                </x-input>
                @error('localizacion')
                <span class="text-red-500 text-sm">{{$message}}</span>
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
                <x-confirm-button wire:click="save" wire:loading.remove wire:target="save">
                    Agregar
                </x-confirm-button>
                <span wire:loading wire:target="save">Cargando ...</span>
            </div>
        </x-slot>
    </x-dialog-modal>
</div>