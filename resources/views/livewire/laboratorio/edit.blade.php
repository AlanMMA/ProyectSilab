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
            <div class="mt-10">
                <x-label value="Nombre:"></x-label>
                <input type="text" wire:model="dato.nombre" class="w-full mt-2">
                <x-input-error for="dato.nombre"></x-input-error>

            </div>
        </x-slot>

        <x-slot name="footer">
            <div class="gap-4">
                <x-secondary-button class="h-full" wire:click="$set('open',false)">
                    Cancel
                </x-secondary-button>

                <x-confirm-button wire:click="save" wire:loading.remove wire:target="save">
                    Editar
                </x-confirm-button>
                <span wire:loading wire:target="save">Cargando ...</span>
            </div>
        </x-slot>
    </x-dialog-modal>
</div>