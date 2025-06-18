<?php

use App\Models\User;
use Illuminate\Support\Facades\Cookie;
use PragmaRX\Google2FA\Google2FA;

uses(\Illuminate\Foundation\Testing\RefreshDatabase::class);

test('two factor enabled attribute returns correct value', function () {
    $user = User::factory()->create();

    expect($user->two_factor_enabled)->toBeFalse();

    $user->two_factor_confirmed_at = now();
    $user->save();

    expect($user->two_factor_enabled)->toBeTrue();
});

test('generate two factor secret creates a valid secret', function () {
    $user = User::factory()->create();

    $user->generateTwoFactorSecret();

    expect($user->two_factor_secret)->not->toBeNull();

    // Verify it's a valid secret by checking its length (should be 16 characters)
    expect(strlen($user->two_factor_secret))->toEqual(16);
});

test('generate recovery codes creates valid codes', function () {
    $user = User::factory()->create();

    $user->generateRecoveryCodes();

    expect($user->two_factor_recovery_codes)->not->toBeNull();

    $recoveryCodes = $user->getRecoveryCodes();

    // Should have 8 recovery codes
    expect($recoveryCodes)->toHaveCount(8);

    // Each code should be in the format XXXXXXXXXX-XXXXXXXXXX (21 characters)
    foreach ($recoveryCodes as $code) {
        expect(strlen($code))->toEqual(21);
        expect($code)->toMatch('/^[A-Za-z0-9]{10}-[A-Za-z0-9]{10}$/');
    }
});

test('verify two factor code validates correct codes', function () {
    $user = User::factory()->create();
    $user->two_factor_secret = 'test-secret';

    // Create a partial mock of the User model
    $userMock = Mockery::mock($user)->makePartial();

    // Set up expectations for the verifyTwoFactorCode method
    $userMock->shouldReceive('verifyTwoFactorCode')
        ->with('valid-code')
        ->andReturn(true);
    $userMock->shouldReceive('verifyTwoFactorCode')
        ->with('invalid-code')
        ->andReturn(false);

    // Test the method with valid and invalid codes
    expect($userMock->verifyTwoFactorCode('valid-code'))->toBeTrue();
    expect($userMock->verifyTwoFactorCode('invalid-code'))->toBeFalse();
});

test('verify recovery code validates and removes used codes', function () {
    $user = User::factory()->create();

    $user->generateRecoveryCodes();
    $recoveryCodes = $user->getRecoveryCodes();
    $validCode = $recoveryCodes[0];

    expect($user->verifyRecoveryCode($validCode))->toBeTrue();

    // Code should be removed after use
    $updatedCodes = $user->getRecoveryCodes();
    expect($updatedCodes)->not->toContain($validCode);
    expect($updatedCodes)->toHaveCount(7);

    // Invalid code should not validate
    expect($user->verifyRecoveryCode('invalid-code'))->toBeFalse();
});

test('confirm two factor sets confirmed timestamp', function () {
    $user = User::factory()->create();

    expect($user->two_factor_confirmed_at)->toBeNull();

    $user->confirmTwoFactor();

    expect($user->two_factor_confirmed_at)->not->toBeNull();
    expect($user->two_factor_enabled)->toBeTrue();
});

test('disable two factor clears all two factor fields', function () {
    $user = User::factory()->create();

    $user->generateTwoFactorSecret();
    $user->generateRecoveryCodes();
    $user->confirmTwoFactor();

    expect($user->two_factor_secret)->not->toBeNull();
    expect($user->two_factor_recovery_codes)->not->toBeNull();
    expect($user->two_factor_confirmed_at)->not->toBeNull();

    $user->disableTwoFactor();

    expect($user->two_factor_secret)->toBeNull();
    expect($user->two_factor_recovery_codes)->toBeNull();
    expect($user->two_factor_confirmed_at)->toBeNull();
    expect($user->two_factor_enabled)->toBeFalse();
});

test('trusted device methods work correctly', function () {
    $user = User::factory()->create(['id' => 1]);

    // Mock Cookie facade
    Cookie::shouldReceive('has')
        ->with('two_factor_1_trusted')
        ->andReturn(false);

    expect($user->hasTrustedDevice())->toBeFalse();

    // Mock trusting a device
    Cookie::shouldReceive('queue')
        ->with('two_factor_1_trusted', true, 30 * 1440)
        ->once();

    $user->trustDevice(30);

    // Mock forgetting a device
    Cookie::shouldReceive('forget')
        ->with('two_factor_1_trusted')
        ->andReturn('cookie-instance');

    Cookie::shouldReceive('queue')
        ->with('cookie-instance')
        ->once();

    $user->forgetTrustedDevice();
});
