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
            <div class="mt-10">
                <x-label value="Nombre:"></x-label>
                <x-input type="text" wire:model="dato.nombre" wire:keyup="update('dato.nombre')" class="w-full mt-2"></x-input>
                <x-input-error for="dato.nombre"></x-input-error>
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
        Livewire.on('showConfirmation', (dato) => {
            const oldDate = dato[0];
            const newDate = dato[1];
            Swal.fire({
                title: "¿Estás seguro de editar el registro?",
                html: `<p>Actual: ${oldDate}</p><p>Nuevo: ${newDate}</p>`,
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