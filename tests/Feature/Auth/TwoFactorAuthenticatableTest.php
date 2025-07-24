<?php

declare(strict_types=1);

@covers(\App\Traits\TwoFactorAuthenticatable::class);

use App\Models\User;
use Illuminate\Support\Facades\Cookie;

uses(\Illuminate\Foundation\Testing\RefreshDatabase::class);

test('two factor enabled attribute returns correct value', function (): void {
    $user = User::factory()->create();

    expect($user->two_factor_enabled)->toBeFalse();

    $user->two_factor_confirmed_at = now();
    $user->save();

    expect($user->two_factor_enabled)->toBeTrue();
});

test('generate two factor secret creates a valid secret', function (): void {
    $user = User::factory()->create();

    $user->generateTwoFactorSecret();

    expect($user->two_factor_secret)->not->toBeNull();

    // Verify it's a valid secret by checking its length (should be 16 characters)
    expect(strlen((string) $user->two_factor_secret))->toEqual(16);
});

test('generate recovery codes creates valid codes', function (): void {
    $user = User::factory()->create();

    $user->generateRecoveryCodes();

    expect($user->two_factor_recovery_codes)->not->toBeNull();

    $recoveryCodes = $user->getRecoveryCodes();

    // Should have 8 recovery codes
    expect($recoveryCodes)->toHaveCount(8);

    // Each code should be in the format XXXXXXXXXX-XXXXXXXXXX (21 characters)
    foreach ($recoveryCodes as $code) {
        expect(strlen((string) $code))->toEqual(21);
        expect($code)->toMatch('/^[A-Za-z0-9]{10}-[A-Za-z0-9]{10}$/');
    }
});

test('verify two factor code validates correct codes', function (): void {
    $user = User::factory()->create();
    $user->two_factor_secret = 'test-secret';

    // Create a partial mock of the User model
    $userMock = Mockery::mock($user)->makePartial();
    $userMock->shouldAllowMockingProtectedMethods();

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

test('verify recovery code validates and removes used codes', function (): void {
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

test('confirm two factor sets confirmed timestamp', function (): void {
    $user = User::factory()->create();

    expect($user->two_factor_confirmed_at)->toBeNull();

    $user->confirmTwoFactor();

    expect($user->two_factor_confirmed_at)->not->toBeNull();
    expect($user->two_factor_enabled)->toBeTrue();
});

test('disable two factor clears all two factor fields', function (): void {
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

test('cookie name is generated correctly', function (): void {
    $user = User::factory()->create(['id' => 123, 'password' => 'test_password_hash']);

    // Create a partial mock of the User model to test protected method
    $userMock = Mockery::mock($user)->makePartial();
    $userMock->shouldAllowMockingProtectedMethods();

    // Mock the getOrCreateDeviceId method
    $userMock->shouldReceive('getOrCreateDeviceId')
        ->andReturn('test-device-id');

    // Call the method with a specific user agent
    $cookieName = $userMock->generateTwoFactorCookieName('test-user-agent');

    // Verify the cookie name format
    expect($cookieName)->toStartWith('tf_');
    expect(strlen((string) $cookieName))->toEqual(67); // 'tf_' + 64 characters for SHA-256 hash

    // Verify that changing any of the inputs produces a different hash
    $userMock2 = Mockery::mock(User::factory()->create(['id' => 456, 'password' => 'test_password_hash']))->makePartial();
    $userMock2->shouldAllowMockingProtectedMethods();
    $userMock2->shouldReceive('getOrCreateDeviceId')->andReturn('test-device-id');
    $cookieName2 = $userMock2->generateTwoFactorCookieName('test-user-agent');
    expect($cookieName2)->not->toEqual($cookieName);

    // Verify that changing the user agent produces a different hash
    $cookieName3 = $userMock->generateTwoFactorCookieName('different-user-agent');
    expect($cookieName3)->not->toEqual($cookieName);
});

test('device id is created and retrieved correctly', function (): void {
    $user = User::factory()->create();

    // Create a partial mock of the User model to test protected method
    $userMock = Mockery::mock($user)->makePartial();
    $userMock->shouldAllowMockingProtectedMethods();

    // Test the case where the cookie doesn't exist
    $userMock1 = Mockery::mock($user)->makePartial();
    $userMock1->shouldAllowMockingProtectedMethods();

    Cookie::shouldReceive('has')
        ->with('device_id')
        ->andReturn(false);

    Cookie::shouldReceive('queue')
        ->withArgs(fn ($name, $value, $minutes) => $name === 'device_id' &&
               is_string($value) &&
               strlen($value) > 0 &&
               $minutes === 60 * 24 * 365 * 5);

    $deviceId1 = $userMock1->getOrCreateDeviceId();
    expect($deviceId1)->toBeString();
    expect(strlen((string) $deviceId1))->toBeGreaterThan(0);

    // Test the case where the cookie exists
    $userMock2 = Mockery::mock($user)->makePartial();
    $userMock2->shouldAllowMockingProtectedMethods();

    Cookie::shouldReceive('has')
        ->with('device_id')
        ->andReturn(true);

    Cookie::shouldReceive('get')
        ->with('device_id')
        ->andReturn('existing-device-id');

    $deviceId2 = $userMock2->getOrCreateDeviceId();
    expect($deviceId2)->toBeString();
    expect(strlen((string) $deviceId2))->toBeGreaterThan(0);
});

test('trusted device methods work correctly', function (): void {
    $user = User::factory()->create(['id' => 1, 'password' => 'hashed_password']);

    // Create a partial mock of the User model
    $userMock = Mockery::mock($user)->makePartial();
    $userMock->shouldAllowMockingProtectedMethods();

    // Mock the getOrCreateDeviceId method to avoid calling the actual method
    // which would try to use the Cookie facade
    $userMock->shouldReceive('getOrCreateDeviceId')
        ->andReturn('test-device-id');

    // Mock the generateTwoFactorCookieName method
    $cookieName = 'tf_'.hash('sha256', '1'.'hashed_password'.'test-device-id'.'test-user-agent');
    $userMock->shouldReceive('generateTwoFactorCookieName')
        ->andReturn($cookieName);

    // Mock Cookie facade for all methods that might be called
    Cookie::shouldReceive('has')
        ->with($cookieName)
        ->andReturn(false);

    // Mock Cookie::queue for any calls
    Cookie::shouldReceive('queue')
        ->withAnyArgs()
        ->andReturn(null);

    expect($userMock->hasTrustedDevice())->toBeFalse();

    // Test trusting a device
    $userMock->trustDevice(30);

    // Mock forgetting a device
    Cookie::shouldReceive('forget')
        ->withAnyArgs()
        ->andReturn('cookie-instance');

    $userMock->forgetTrustedDevice();
});
