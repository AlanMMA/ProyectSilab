<div>
    <x-danger-button wire:click="$set('open', 'true')">
        Agregar
    </x-danger-button>
    <x-dialog-modal wire:model.live="open">
        <x-slot name="title">
            Agregar un encargado
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
                <x-label value="Apellido paterno:"></x-label>
                <x-input wire:model="apellido_p" wire:keyup="update('apellido_p')" type="text" class="w-full"></x-input>
                @error('apellido_p')
                <span class="text-red-500 text-sm">{{$message}}</span>
                @enderror
            </div>
            <div class="mb-4">
                <x-label value="Apellido materno:"></x-label>
                <x-input wire:model="apellido_m" wire:keyup="update('apellido_m')" type="text" class="w-full"></x-input>
                @error('apellido_m')
                <span class="text-red-500 text-sm">{{$message}}</span>
                @enderror
            </div>
            <div class="mb-4">
                <x-label value="Asignar un laboratorio:"></x-label>
                <select name="id_laboratorio" id="id_laboratorio-{{ $dato['id'] ?? 'new' }}" wire:model="id_laboratorio"
                    class="border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 focus:border-indigo-500 dark:focus:border-indigo-600 focus:ring-indigo-500 dark:focus:ring-indigo-600 rounded-md shadow-sm">
                    @foreach ($laboratorios as $id => $nombre)
                    <option value="{{ $id }}">{{ $id }} {{ $nombre }}</option>
                    @endforeach
                </select>
                @error('id_laboratorio')
                <span class="text-red-500 text-sm">{{ $message }}</span>
                @enderror
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