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
                <x-input type="text" wire:model="dato.nombre" class="w-full mt-2"></x-input>
                <x-input-error for="dato.nombre"></x-input-error>
            </div>
            <div class="mt-10">
                <x-label value="Limite de encargados:"></x-label>
                <x-input type="text" wire:model="dato.num_max_encargado" class="w-full mt-2"></x-input>
                <x-input-error for="dato.num_max_encargado"></x-input-error>
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
        Livewire.on('showConfirmation', (mensaje) => {
            Swal.fire({
                title: "¿Estás seguro de editar el registro?",
                html: mensaje,
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

    @push('js')
    <script>
        document.addEventListener('DOMContentLoaded', () => {
            Livewire.on('alert1', message => {
                setTimeout(() => {
                    Swal.fire({
                        title: 'Alerta',
                        text: message,
                        icon: 'warning',
                        confirmButtonText: 'OK'
                    });
                }, 0);
            });

        });
    </script>
    @endpush

</div>