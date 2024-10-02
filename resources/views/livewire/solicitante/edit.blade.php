<div>
    <a wire:click="$set('open', true)" class="material-symbols-outlined  font-bold text-white py-2 px-4 rounded cursor-pointer bg-green-500">
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
                <x-input wire:model="dato.nombre" wire:keyup="update('dato.nombre')" type="text" class="w-full"></x-input>
                @error('dato.nombre')
                    <span class="text-red-500 text-sm">{{$message}}</span>
                @enderror
            </div>
            <div class="mb-4">
                <x-label value="Apellido paterno:"></x-label>
                <x-input wire:model="dato.apellido_p" wire:keyup="update('dato.apellido_p')" type="text"
                    class="w-full"></x-input>
                    @error('dato.apellido_p')
                    <span class="text-red-500 text-sm">{{$message}}</span>
                @enderror
            </div>
            <div class="mb-4">
                <x-label value="Apellido materno:"></x-label>
                <x-input wire:model="dato.apellido_m" wire:keyup="update('dato.apellido_m')" type="text"
                    class="w-full"></x-input>
                    @error('dato.apellido_m')
                    <span class="text-red-500 text-sm">{{$message}}</span>
                @enderror
            </div>
            <div class="mb-4">
                <x-label value="Asignar un area:"></x-label>
                <select name="id_area" id="id_area-{{ $dato['id'] ?? 'new' }}" wire:model="dato.id_area"
                    class="border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 focus:border-indigo-500 dark:focus:border-indigo-600 focus:ring-indigo-500 dark:focus:ring-indigo-600 rounded-md shadow-sm">
                    @foreach ($areas as $id => $nombre)
                        <option value="{{ $id }}">{{ $id }} {{ $nombre }}</option>
                    @endforeach
                </select>
                @error('dato.id_area')
                    <span class="text-red-500 text-sm">{{ $message }}</span>
                @enderror
            </div>
            <div class="mb-4">
                <x-label value="Tipo de solicitante:"></x-label>
                <select wire:model.live="dato.tipo"
                    class="border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 focus:border-indigo-500 dark:focus:border-indigo-600 focus:ring-indigo-500 dark:focus:ring-indigo-600 rounded-md shadow-sm">
                    <option value="docente">Docente</option>
                    <option value="alumno">Alumno</option>
                </select>
                @error('dato.tipo')
                    <span class="text-red-500 text-sm">{{ $message }}</span>
                @enderror
            </div>
            @if ($dato['tipo'] === 'alumno')
                <div class="mb-4">
                    <x-label value="Número de control:"></x-label>
                    <x-input wire:model="dato.numero_control" type="text" class="w-full"></x-input>
                    @error('dato.numero_control')
                        <span class="text-red-500 text-sm">{{ $message }}</span>
                    @enderror
                </div>
            @endif
        </x-slot>
        
        <x-slot name="footer">
            <div class="gap-4">
                <x-secondary-button class="h-full" wire:click="$set('open',false)">
                    Cancel
                </x-secondary-button>
                
                <x-danger-button wire:click="save" wire:loading.remove wire:target="save">
                    Editar
                </x-danger-button>
                <span wire:loading wire:target="save">Cargando ...</span>
            </div>
        </x-slot>
    </x-dialog-modal>
</div>
