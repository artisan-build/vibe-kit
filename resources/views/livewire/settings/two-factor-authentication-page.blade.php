<section class="w-full">
    @include('partials.settings-heading')

    <x-settings.layout :heading="__('Two Factor Authentication')" :subheading="__('Add additional security to your account using two factor authentication')">
        @if (config('settings.two_factor_authentication'))
            <div class="mt-6 space-y-6">
                @if (! auth()->user()->two_factor_enabled)
                    <div>
                        <flux:text>
                            {{ __('When two factor authentication is enabled, you will be prompted for a secure, random token during authentication. You may retrieve this token from your phone\'s Google Authenticator application.') }}
                        </flux:text>

                        <div class="mt-4">
                            <flux:button wire:click="enableTwoFactorAuthentication" variant="primary">
                                {{ __('Enable') }}
                            </flux:button>
                        </div>
                    </div>
                @else
                    <div>
                        <flux:text>
                            {{ __('Two factor authentication is now enabled. Scan the following QR code using your phone\'s authenticator application.') }}
                        </flux:text>

                        <div class="mt-4 flex flex-wrap gap-3">
                            <flux:button wire:click="showRecoveryCodes" variant="primary">
                                {{ __('Show Recovery Codes') }}
                            </flux:button>

                            <flux:button wire:click="regenerateRecoveryCodes">
                                {{ __('Regenerate Recovery Codes') }}
                            </flux:button>

                            <flux:button wire:click="showTwoFactorDisabledForm" variant="danger">
                                {{ __('Disable') }}
                            </flux:button>
                        </div>
                    </div>
                @endif

                <!-- QR Code Modal -->
                @if ($showingQrCode)
                    <flux:card class="mt-6 p-6">
                        <flux:text class="font-medium">
                            {{ __('Scan this QR code with your authenticator app:') }}
                        </flux:text>

                        <div class="my-4">
                            <img src="{{ $this->twoFactorQrCodeSvg }}" alt="QR Code">
                        </div>

                        @if ($showingConfirmationForm)
                            <div class="mt-4">
                                <flux:text class="font-medium">
                                    {{ __('Enter the code from your authenticator app to confirm setup:') }}
                                </flux:text>

                                <div class="mt-3 flex items-center gap-3">
                                    <flux:input
                                        wire:model="confirmationCode"
                                        type="text"
                                        placeholder="{{ __('Enter code') }}"
                                    />

                                    <flux:button wire:click="confirmTwoFactorAuthentication" variant="primary">
                                        {{ __('Confirm') }}
                                    </flux:button>
                                </div>

                                @error('confirmationCode')
                                    <flux:error>{{ $message }}</flux:error>
                                @enderror
                            </div>
                        @endif
                    </flux:card>
                @endif

                <!-- Recovery Codes Modal -->
                @if ($showingRecoveryCodes)
                    <flux:card class="mt-6">
                        <flux:text class="font-medium">
                            {{ __('Store these recovery codes in a secure password manager. They can be used to recover access to your account if your two factor authentication device is lost.') }}
                        </flux:text>

                        <div class="mt-4 grid grid-cols-2 gap-2">
                            @foreach ($this->recoveryCodes as $code)
                                <flux:card class="font-mono text-sm">{{ $code }}</flux:card>
                            @endforeach
                        </div>
                    </flux:card>
                @endif

                <!-- Disable 2FA Form -->
                @if ($showingTwoFactorDisabledForm)
                    <flux:card class="mt-6">
                        <flux:text class="font-medium">
                            {{ __('Enter the code from your authenticator app to disable two factor authentication:') }}
                        </flux:text>

                        <div class="mt-3 flex items-center gap-3">
                            <flux:input
                                wire:model="code"
                                type="text"
                                placeholder="{{ __('Enter code') }}"
                            />

                            <flux:button wire:click="disableTwoFactorAuthentication" variant="danger">
                                {{ __('Disable') }}
                            </flux:button>
                        </div>

                        @error('code')
                            <flux:error>{{ $message }}</flux:error>
                        @enderror
                    </flux:card>
                @endif
            </div>
        @endif
    </x-settings.layout>
</section>
