<?php

declare(strict_types=1);

namespace App\Livewire\Auth;

use Illuminate\Support\Facades\Auth;
use Livewire\Attributes\Layout;
use Livewire\Component;

#[Layout('components.layouts.auth')]
class TwoFactorChallenge extends Component
{
    public string $code = '';

    public string $recovery_code = '';

    public bool $showingRecoveryCodeForm = false;

    public bool $rememberDevice = false;

    /**
     * Render the component.
     */
    public function render(): \Illuminate\View\View
    {
        return view('livewire.auth.two-factor-challenge');
    }

    /**
     * Toggle between recovery code form and regular code form.
     */
    public function toggleRecoveryCodeForm(): void
    {
        $this->showingRecoveryCodeForm = ! $this->showingRecoveryCodeForm;
        $this->code = '';
        $this->recovery_code = '';
    }

    /**
     * Verify the two factor authentication code.
     */
    public function verifyCode(): void
    {
        $user = Auth::user();

        if (! $user) {
            return;
        }

        if ($this->showingRecoveryCodeForm) {
            if (! $user->verifyRecoveryCode($this->recovery_code)) {
                $this->addError('recovery_code', __('The recovery code entered is invalid.'));

                return;
            }
        } else {
            if (! $user->verifyTwoFactorCode($this->code)) {
                $this->addError('code', __('The code entered is invalid.'));

                return;
            }
        }

        if ($this->rememberDevice) {
            $user->trustDevice(config('settings.two_factor_trust'));
        }

        $this->redirectIntended(default: route('dashboard', absolute: false), navigate: true);
    }
}
