<div>
    <a wire:click="$set('open', true)"
        class="material-symbols-outlined  font-bold text-white py-2 px-2 rounded cursor-pointer bg-orange-600 hover:bg-orange-500">
        <span class="material-symbols-outlined">
            Lock_reset
        </span>
    </a>

    <x-dialog-modal wire:model.live="open">
        <x-slot name="title">
            Cambio de contraseña del usuario {{$dato->name}}.
        </x-slot>
        <x-slot name="content">
            <div class="mb-4">
                <x-label value="Contraseña:"></x-label>
                <div class="relative w-full flex items-center">
                    <x-input wire:model="password" wire:keyup="update('password')" :type="$showPassword ? 'text' : 'password'"
                        class="w-full pr-12"></x-input>
                    <button type="button" wire:click="togglePasswordVisibility"
                        class="ml-auto px-3 flex items-center focus:outline-none">
                        @if ($showPassword)
                            <span class="material-symbols-outlined">visibility</span>
                        @else
                            <span class="material-symbols-outlined">visibility_off</span>
                        @endif
                    </button>
                </div>
                <x-input-error for="password"></x-input-error>
            </div>

            <div class="mb-4">
                <x-label value="Confirmar contraseña:"></x-label>
                <div class="relative w-full flex items-center">
                    <x-input wire:model="password_confirmation" wire:keyup="update('password_confirmation')"
                        :type="$showPassword2 ? 'text' : 'password'" class="w-full pr-12"></x-input>
                    <button type="button" wire:click="togglePasswordVisibility2"
                        class="ml-auto px-3 flex items-center focus:outline-none">
                        @if ($showPassword2)
                            <span class="material-symbols-outlined">visibility</span>
                        @else
                            <span class="material-symbols-outlined">visibility_off</span>
                        @endif
                    </button>
                </div>
                <x-input-error for="password_confirmation"></x-input-error> <!-- Nueva validación -->
            </div>
        </x-slot>
        <x-slot name="footer">
            <div class="gap-4">
                <x-secondary-button class="h-full" wire:click="$set('open',false)">
                    Cancel
                </x-secondary-button>

                <x-confirm-button wire:click="resetPass" wire:loading.remove wire:target="confirmSave2">
                    Guardar cambios
                </x-confirm-button>
                <span wire:loading wire:target="save">Cargando ...</span>
            </div>
        </x-slot>
    </x-dialog-modal>

    @push('js')
    <script>
        Livewire.on('Confirm', (mensaje) => {
            Swal.fire({
                title: "¿Estás seguro de cambiar la contraseña al usuario?",
                html: "Recuerde hacerle saber su nueva contraseña.",
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
