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
                <x-input type="text" wire:model="dato.nombre" class="w-full mt-2"></x-input>
                <x-input-error for="dato.nombre"></x-input-error>
            </div>
        </x-slot>

        <x-slot name="footer">
            <div class="gap-4">
                <x-secondary-button class="h-full" wire:click="$set('open',false)">
                    Cancel
                </x-secondary-button>

                <x-confirm-button onclick="confirmSave('{{ $dato['nombre'] }}')" wire:loading.remove wire:target="save">
                    Editar
                </x-confirm-button>
                <span wire:loading wire:target="save">Cargando ...</span>
            </div>
        </x-slot>
    </x-dialog-modal>

    @push('js')
    <script>
        function confirmSave(nombre) {
            Swal.fire({
                title: "¿Estás seguro de editar el registro?",
                text: "Registro: " + nombre,
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
        }

        Livewire.on('saveConfirmed', () => {
            @this.call('save');
        });
    </script>
    @endpush
    
</div>