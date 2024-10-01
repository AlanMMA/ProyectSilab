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
            <div class="mt-10">
                <x-label value="Nombre:"></x-label>
                <x-input type="text" wire:model="dato.name" class="w-full mt-2"></x-input>
                <x-input-error for="dato.name"></x-input-error>
            </div>
            <div class="mt-10">
                <x-label value="Correo electronico:"></x-label>
                <x-input type="text" wire:model="dato.email" class="w-full mt-2"></x-input>
                <x-input-error for="dato.email"></x-input-error>
            </div>
            <div class="mt-10">
                <x-label value="Asignar un rol:"></x-label>
                <select name="id_rol" id="id_rol-{{ $dato['id'] ?? 'new' }}" wire:model="dato.id_rol"
                    class="border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 focus:border-indigo-500 dark:focus:border-indigo-600 focus:ring-indigo-500 dark:focus:ring-indigo-600 rounded-md shadow-sm">
                    @foreach ($roles as $id => $nombre)
                        <option value="{{ $id }}">{{ $nombre }}</option>
                    @endforeach
                </select>
                @error('dato.id_rol')
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
                    Editar
                </x-danger-button>
                <span wire:loading wire:target="save">Cargando ...</span>
            </div>
        </x-slot>
    </x-dialog-modal>
</div>
