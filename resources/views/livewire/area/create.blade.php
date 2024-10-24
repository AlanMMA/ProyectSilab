<div>

    <x-confirm-button wire:click="$set('open', 'true')">
        Agregar
    </x-confirm-button>


    <x-dialog-modal wire:model.live="open">
        <x-slot name="title">
            Agregar una nueva area
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

                <x-confirm-button onclick="confirmSave2()" wire:loading.remove wire:target="save">
                    Agregar
                </x-confirm-button>
                <span wire:loading wire:target="save">Cargando ...</span>
            </div>
        </x-slot>
    </x-dialog-modal>

    @push('js')
    <script>
        function confirmSave2(nombre) {
            Swal.fire({
                title: "¿Estás seguro de guardar el registro?",
                icon: "warning",
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
        }

        Livewire.on('saveConfirmed2', () => {
            @this.call('save');
        });
    </script>
    @endpush

</div>