<div>
    @if (config('settings.two_factor_authentication'))
        <div class="mt-10 sm:mt-0">
            <div class="md:grid md:grid-cols-3 md:gap-6">
                <div class="md:col-span-1">
                    <div class="px-4 sm:px-0">
                        <h3 class="text-lg font-medium text-gray-900">Two Factor Authentication</h3>
                        <p class="mt-1 text-sm text-gray-600">
                            Add additional security to your account using two factor authentication.
                        </p>
                    </div>
                </div>

                <div class="mt-5 md:mt-0 md:col-span-2">
                    <div class="px-4 py-5 sm:p-6 bg-white shadow sm:rounded-lg">
                        @if (! auth()->user()->two_factor_enabled)
                            <div>
                                <p class="text-sm text-gray-600">
                                    When two factor authentication is enabled, you will be prompted for a secure, random token during authentication. You may retrieve this token from your phone's Google Authenticator application.
                                </p>

                                <button type="button" wire:click="enableTwoFactorAuthentication" class="mt-4 px-4 py-2 bg-gray-800 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-gray-700 focus:bg-gray-700 active:bg-gray-900 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 transition ease-in-out duration-150">
                                    Enable
                                </button>
                            </div>
                        @else
                            <div>
                                <p class="text-sm text-gray-600">
                                    Two factor authentication is now enabled. Scan the following QR code using your phone's authenticator application.
                                </p>

                                <div class="mt-4">
                                    <div class="flex items-center">
                                        <button type="button" wire:click="showRecoveryCodes" class="px-4 py-2 bg-gray-800 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-gray-700 focus:bg-gray-700 active:bg-gray-900 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 transition ease-in-out duration-150">
                                            Show Recovery Codes
                                        </button>

                                        <button type="button" wire:click="regenerateRecoveryCodes" class="ml-3 px-4 py-2 bg-white border border-gray-300 rounded-md font-semibold text-xs text-gray-700 uppercase tracking-widest shadow-sm hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 transition ease-in-out duration-150">
                                            Regenerate Recovery Codes
                                        </button>

                                        <button type="button" wire:click="showTwoFactorDisabledForm" class="ml-3 px-4 py-2 bg-red-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-red-500 active:bg-red-700 focus:outline-none focus:ring-2 focus:ring-red-500 focus:ring-offset-2 transition ease-in-out duration-150">
                                            Disable
                                        </button>
                                    </div>
                                </div>
                            </div>
                        @endif

                        <!-- QR Code Modal -->
                        @if ($showingQrCode)
                            <div class="mt-4 p-4 bg-gray-100 rounded-lg">
                                <div class="text-sm font-medium text-gray-900 mb-2">
                                    Scan this QR code with your authenticator app:
                                </div>
                                <div class="mb-4">
                                    <img src="{{ $this->twoFactorQrCodeSvg }}" alt="QR Code">
                                </div>

                                @if ($showingConfirmationForm)
                                    <div>
                                        <div class="text-sm font-medium text-gray-900 mb-2">
                                            Enter the code from your authenticator app to confirm setup:
                                        </div>
                                        <div class="flex items-center">
                                            <input type="text" wire:model="confirmationCode" class="mt-1 block w-1/2 border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm" placeholder="Enter code">
                                            <button type="button" wire:click="confirmTwoFactorAuthentication" class="ml-3 px-4 py-2 bg-gray-800 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-gray-700 focus:bg-gray-700 active:bg-gray-900 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 transition ease-in-out duration-150">
                                                Confirm
                                            </button>
                                        </div>
                                        @error('confirmationCode') <span class="text-sm text-red-600">{{ $message }}</span> @enderror
                                    </div>
                                @endif
                            </div>
                        @endif

                        <!-- Recovery Codes Modal -->
                        @if ($showingRecoveryCodes)
                            <div class="mt-4 p-4 bg-gray-100 rounded-lg">
                                <div class="text-sm font-medium text-gray-900 mb-2">
                                    Store these recovery codes in a secure password manager. They can be used to recover access to your account if your two factor authentication device is lost.
                                </div>
                                <div class="grid grid-cols-2 gap-2 mt-2">
                                    @foreach ($this->recoveryCodes as $code)
                                        <div class="p-2 bg-white rounded font-mono text-sm">{{ $code }}</div>
                                    @endforeach
                                </div>
                            </div>
                        @endif

                        <!-- Disable 2FA Form -->
                        @if ($showingTwoFactorDisabledForm)
                            <div class="mt-4 p-4 bg-gray-100 rounded-lg">
                                <div class="text-sm font-medium text-gray-900 mb-2">
                                    Enter the code from your authenticator app to disable two factor authentication:
                                </div>
                                <div class="flex items-center">
                                    <input type="text" wire:model="code" class="mt-1 block w-1/2 border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm" placeholder="Enter code">
                                    <button type="button" wire:click="disableTwoFactorAuthentication" class="ml-3 px-4 py-2 bg-red-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-red-500 active:bg-red-700 focus:outline-none focus:ring-2 focus:ring-red-500 focus:ring-offset-2 transition ease-in-out duration-150">
                                        Disable
                                    </button>
                                </div>
                                @error('code') <span class="text-sm text-red-600">{{ $message }}</span> @enderror
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    @endif
</div>
