<x-form-section submit="validatePassword">
    <x-slot name="title">
        {{ __('Update Password') }}
    </x-slot>

    <x-slot name="description">
        {{ __('Ensure your account is using a long, random password to stay secure.') }}
    </x-slot>

    <x-slot name="form">
        <div class="col-span-6 sm:col-span-4">
            <x-label for="current_password" value="{{ __('Current Password') }}" />
            <div class="relative w-full flex items-center">
                <x-input id="current_password" type="password" :type="$showPassword ? 'text' : 'password'"
                    class="mt-1 block w-full" wire:model="state.current_password" autocomplete="current-password" />
                <button type="button" wire:click="togglePasswordVisibility"
                    class="ml-auto px-3 flex items-center focus:outline-none">
                    @if ($showPassword)
                    <span class="material-symbols-outlined text-black dark:text-gray-400">
                        visibility
                    </span>
                    @else
                    <span class="material-symbols-outlined text-black dark:text-gray-400">
                        visibility_off
                    </span>
                    @endif
                </button>
            </div>
            <x-input-error for="state.current_password" class="mt-2" />
        </div>

        <div class="col-span-6 sm:col-span-4">
            <x-label for="password" value="{{ __('New Password') }}" />
            <div class="relative w-full flex items-center">
                <x-input id="password" type="password" :type="$showPassword2 ? 'text' : 'password'"
                    class="mt-1 block w-full" wire:model="state.password" autocomplete="new-password" />
                <button type="button" wire:click="togglePasswordVisibility2"
                    class="ml-auto px-3 flex items-center focus:outline-none">
                    @if ($showPassword2)
                    <span class="material-symbols-outlined text-black dark:text-gray-400">
                        visibility
                    </span>
                    @else
                    <span class="material-symbols-outlined text-black dark:text-gray-400">
                        visibility_off
                    </span>
                    @endif
                </button>
            </div>
            <x-input-error for="state.password" class="mt-2" />
        </div>

        <div class="col-span-6 sm:col-span-4">
            <x-label for="password_confirmation" value="{{ __('Confirm Password') }}" />
            <div class="relative w-full flex items-center">
                <x-input id="password_confirmation" type="password" :type="$showPassword3 ? 'text' : 'password'"
                    class="mt-1 block w-full" wire:model="state.password_confirmation" autocomplete="new-password" />
                <button type="button" wire:click="togglePasswordVisibility3"
                    class="ml-auto px-3 flex items-center focus:outline-none">
                    @if ($showPassword3)
                    <span class="material-symbols-outlined text-black dark:text-gray-400">
                        visibility
                    </span>
                    @else
                    <span class="material-symbols-outlined text-black dark:text-gray-400">
                        visibility_off
                    </span>
                    @endif
                </button>
            </div>
            <x-input-error for="state.password_confirmation" class="mt-2" />
        </div>
    </x-slot>

    <x-slot name="actions">
        <x-action-message class="me-3" on="saved">
            {{ __('Saved.') }}
        </x-action-message>

        <x-button>
            {{ __('Save') }}
        </x-button>
    </x-slot>


    @push('js')
    <script>
        Livewire.on('showConfirmation', () => {
            Swal.fire({
                title: "¿Estás seguro de actualizar la contraseña?",
                icon: "warning",
                showCancelButton: true,
                confirmButtonColor: "#3085d6",
                cancelButtonColor: "#d33",
                confirmButtonText: "Aceptar",
                cancelButtonText: "Cancelar"
            }).then((result) => {
                if (result.isConfirmed) {
                    Livewire.dispatch('updatePassword');
                }
            });
        });
    </script>
    @endpush

</x-form-section>