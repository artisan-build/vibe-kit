<?php

declare(strict_types=1);

namespace App\Livewire\Settings;

use Illuminate\Support\Facades\Auth;
use Livewire\Component;
use PragmaRX\Google2FAQRCode\Google2FA as Google2FAQRCode;

class TwoFactorAuthentication extends Component
{
    public bool $showingQrCode = false;

    public bool $showingRecoveryCodes = false;

    public bool $showingConfirmationForm = false;

    public bool $showingTwoFactorDisabledForm = false;

    public string $code = '';

    public string $confirmationCode = '';

    /**
     * Mount the component.
     */
    public function mount(): void
    {
        // Only show the component if 2FA is enabled in the config
        if (! config('settings.two_factor_authentication')) {
            return;
        }
    }

    /**
     * Render the component.
     */
    public function render(): \Illuminate\View\View
    {
        return view('livewire.settings.two-factor-authentication');
    }

    /**
     * Enable two factor authentication for the user.
     */
    public function enableTwoFactorAuthentication(): void
    {
        if (! config('settings.two_factor_authentication')) {
            return;
        }

        $user = Auth::user();

        // Generate new 2FA secret and recovery codes
        $user->generateTwoFactorSecret();
        $user->generateRecoveryCodes();
        $user->save();

        $this->showingQrCode = true;
        $this->showingConfirmationForm = true;
    }

    /**
     * Confirm two factor authentication.
     */
    public function confirmTwoFactorAuthentication(): void
    {
        if (! config('settings.two_factor_authentication')) {
            return;
        }

        $user = Auth::user();

        if (! $user->verifyTwoFactorCode($this->confirmationCode)) {
            $this->addError('confirmationCode', __('The provided two factor authentication code was invalid.'));

            return;
        }

        $user->confirmTwoFactor();

        $this->showingQrCode = false;
        $this->showingConfirmationForm = false;
        $this->showingRecoveryCodes = true;
        $this->confirmationCode = '';

        $this->dispatch('two-factor-authentication-confirmed');
    }

    /**
     * Display the user's recovery codes.
     */
    public function showRecoveryCodes(): void
    {
        if (! config('settings.two_factor_authentication')) {
            return;
        }

        if (! Auth::user()->two_factor_enabled) {
            return;
        }

        $this->showingRecoveryCodes = true;
    }

    /**
     * Generate new recovery codes for the user.
     */
    public function regenerateRecoveryCodes(): void
    {
        if (! config('settings.two_factor_authentication')) {
            return;
        }

        if (! Auth::user()->two_factor_enabled) {
            return;
        }

        $user = Auth::user();
        $user->generateRecoveryCodes();
        $user->save();

        $this->showingRecoveryCodes = true;

        $this->dispatch('recovery-codes-generated');
    }

    /**
     * Show the two factor authentication disabled confirmation form.
     */
    public function showTwoFactorDisabledForm(): void
    {
        if (! config('settings.two_factor_authentication')) {
            return;
        }

        $this->showingTwoFactorDisabledForm = true;
    }

    /**
     * Disable two factor authentication for the user.
     */
    public function disableTwoFactorAuthentication(): void
    {
        if (! config('settings.two_factor_authentication')) {
            return;
        }

        $user = Auth::user();

        if (! $user->verifyTwoFactorCode($this->code)) {
            $this->addError('code', __('The provided two factor authentication code was invalid.'));

            return;
        }

        $user->disableTwoFactor();
        $user->forgetTrustedDevice();

        $this->showingTwoFactorDisabledForm = false;
        $this->code = '';

        $this->dispatch('two-factor-authentication-disabled');
    }

    /**
     * Get the SVG QR code for the user's two factor authentication.
     */
    public function getTwoFactorQrCodeSvgProperty(): string
    {
        $user = Auth::user();

        if (! $user->two_factor_secret) {
            return '';
        }

        $google2fa = new Google2FAQRCode;

        return $google2fa->getQRCodeInline(
            config('app.name'),
            $user->email,
            $user->two_factor_secret
        );
    }

    /**
     * Get the user's recovery codes.
     *
     * @return array<int, string>
     */
    public function getRecoveryCodesProperty(): array
    {
        $user = Auth::user();

        if (! $user->two_factor_recovery_codes) {
            return [];
        }

        return $user->getRecoveryCodes();
    }
}
