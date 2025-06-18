<div>
    <x-layouts.auth.card>
        <x-slot name="logo">
            <x-app-logo-icon class="w-20 h-20" />
        </x-slot>

        <div class="mb-4 text-sm text-gray-600">
            @if ($showingRecoveryCodeForm)
                {{ __('Please confirm access to your account by entering one of your emergency recovery codes.') }}
            @else
                {{ __('Please confirm access to your account by entering the authentication code provided by your authenticator application.') }}
            @endif
        </div>

        <form wire:submit="verifyCode">
            @if ($showingRecoveryCodeForm)
                <div class="mt-4">
                    <flux:input wire:model="recovery_code" :label="__('Recovery Code')" type="text" required autofocus autocomplete="one-time-code" />
                </div>
            @else
                <div class="mt-4">
                    <flux:input wire:model="code" :label="__('Code')" type="text" required autofocus autocomplete="one-time-code" />
                </div>
            @endif

            @if (config('settings.two_factor_trust') !== null)
                <div class="mt-4 flex items-center">
                    <input wire:model="rememberDevice" id="remember_device" type="checkbox" class="rounded border-gray-300 text-indigo-600 shadow-sm focus:ring-indigo-500">
                    <label for="remember_device" class="ml-2 block text-sm text-gray-600">
                        {{ __('Trust this device for :days days', ['days' => config('settings.two_factor_trust')]) }}
                    </label>
                </div>
            @endif

            <div class="flex items-center justify-end mt-4">
                <button type="button" class="text-sm text-gray-600 hover:text-gray-900 underline cursor-pointer" wire:click="toggleRecoveryCodeForm">
                    @if ($showingRecoveryCodeForm)
                        {{ __('Use an authentication code') }}
                    @else
                        {{ __('Use a recovery code') }}
                    @endif
                </button>

                <flux:button variant="primary" type="submit" class="ml-4">
                    {{ __('Verify') }}
                </flux:button>
            </div>
        </form>
    </x-layouts.auth.card>
</div>
