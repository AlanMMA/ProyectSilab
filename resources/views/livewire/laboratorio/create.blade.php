<div>
    <x-danger-button wire:click="$set('open', 'true')">
        Agregar
    </x-danger-button>


    <x-dialog-modal wire:model.live="open">
        <x-slot name="title">
            Agregar un nuevo laboratorio
        </x-slot>

        <x-slot name="content">
            <div class="mb-4">
                <x-label value="Nombre:"></x-label>
                <x-input wire:model="nombre" wire:keyup="update('nombre')" type="text" class="w-full"></x-input>
                <x-input-error for="nombre"></x-input-error>
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