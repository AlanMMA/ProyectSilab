<div>
    <x-form-section submit="validatePassword">
        <x-slot name="title">
            {{ __('Update Security Key') }}
        </x-slot>
    
        <x-slot name="description">
            {{ __('Change your security password, which you can use as a backup if you forget your main password.') }}
        </x-slot>
    
        <x-slot name="form">
            <div class="col-span-6 sm:col-span-4">
                <x-label for="current_SK" value="{{ __('Current Security Key') }}" />
                <x-input id="current_SK" type="password" class="mt-1 block w-full" wire:model.defer="state.current_SK" autocomplete="current-password" />
                <x-input-error for="state.current_SK" class="mt-2" />
            </div>
    
            <div class="col-span-6 sm:col-span-4">
                <x-label for="SK" value="{{ __('New Security Key') }}" />
                <x-input id="SK" type="password" class="mt-1 block w-full" wire:model.defer="state.SK" autocomplete="new-SK" />
                <x-input-error for="state.SK" class="mt-2" />
            </div>
    
            <div class="col-span-6 sm:col-span-4">
                <x-label for="SK_confirm" value="{{ __('Confirm Security Key') }}" />
                <x-input id="SK_confirm" type="password" class="mt-1 block w-full" wire:model.defer="state.SK_confirm" autocomplete="new-password" />
                <x-input-error for="state.SK_confirm" class="mt-2" />
            </div>
        </x-slot>
    
        <x-slot name="actions">
            <x-action-message class="me-3" on="saved">
                {{ __('Saved.') }}
            </x-action-message>
    
            <x-button type="submit" wire:loading.attr="disabled">
                {{ __('Save') }}
            </x-button>
        </x-slot>
    </x-form-section>
</div>
