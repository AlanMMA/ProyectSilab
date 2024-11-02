<div>

    <x-confirm-button wire:click="$set('open', 'true')">
        Agregar
    </x-confirm-button>

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

                <x-confirm-button wire:click="confirmSave2" wire:loading.remove wire:target="confirmSave2">
                    Agregar
                </x-confirm-button>
                <span wire:loading wire:target="save">Cargando ...</span>
            </div>
        </x-slot>
    </x-dialog-modal>

    @push('js')
    <script>
        Livewire.on('showConfirmation2', (nombre) => {
            console.log(nombre);
            Swal.fire({
                title: "¿Estás seguro de agregar este registro?",
                icon: "question",
                text: "Registro: " + nombre,
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