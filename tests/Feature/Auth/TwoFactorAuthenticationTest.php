<?php

declare(strict_types=1);

use App\Livewire\Auth\Login;
use App\Livewire\Auth\TwoFactorChallenge;
use App\Models\User;
use Livewire\Livewire;

test('users with two factor authentication are redirected to the two factor challenge', function (): void {
    // Create a user with 2FA enabled
    $user = User::factory()->create();
    $user->generateTwoFactorSecret();
    $user->generateRecoveryCodes();
    $user->confirmTwoFactor();
    $user->save();

    // Attempt to login
    $response = Livewire::test(Login::class)
        ->set('email', $user->email)
        ->set('password', 'password')
        ->call('login');

    // Should redirect to the 2FA challenge
    $response->assertRedirect(route('two-factor.challenge', absolute: false));

    // User should be authenticated but not fully
    expect(auth()->check())->toBeTrue();

    // Verify the 2FA challenge page can be rendered
    $this->get('/two-factor-challenge')->assertOk();
});

test('users can complete the two factor challenge with a valid code', function (): void {
    // Create a user with 2FA enabled
    $user = User::factory()->create();
    $user->generateTwoFactorSecret();
    $user->generateRecoveryCodes();
    $user->confirmTwoFactor();
    $user->save();

    // Login to get to the 2FA challenge
    Livewire::test(Login::class)
        ->set('email', $user->email)
        ->set('password', 'password')
        ->call('login');

    // Mock the verification of the 2FA code
    $user = User::find($user->id); // Get a fresh instance

    // Create a partial mock of the User model
    $userMock = Mockery::mock($user)->makePartial();
    $userMock->shouldAllowMockingProtectedMethods();

    // Set up expectations for the verifyTwoFactorCode method
    $userMock->shouldReceive('verifyTwoFactorCode')
        ->with('123456')
        ->andReturn(true);

    // Set the authenticated user to our mocked instance
    $this->actingAs($userMock);

    // Complete the 2FA challenge
    $response = Livewire::test(TwoFactorChallenge::class)
        ->set('code', '123456')
        ->call('verifyCode');

    $response->assertHasNoErrors();
    $response->assertRedirect(route('dashboard', absolute: false));
});

test('users can complete the two factor challenge with a valid recovery code', function (): void {
    // Create a user with 2FA enabled
    $user = User::factory()->create();
    $user->generateTwoFactorSecret();
    $user->generateRecoveryCodes();
    $user->confirmTwoFactor();
    $user->save();

    // Login to get to the 2FA challenge
    Livewire::test(Login::class)
        ->set('email', $user->email)
        ->set('password', 'password')
        ->call('login');

    // Mock the verification of the recovery code
    $user = User::find($user->id); // Get a fresh instance

    // Create a partial mock of the User model
    $userMock = Mockery::mock($user)->makePartial();
    $userMock->shouldAllowMockingProtectedMethods();

    // Set up expectations for the verifyRecoveryCode method
    $userMock->shouldReceive('verifyRecoveryCode')
        ->with('recovery-code')
        ->andReturn(true);

    // Set the authenticated user to our mocked instance
    $this->actingAs($userMock);

    // Complete the 2FA challenge with a recovery code
    $response = Livewire::test(TwoFactorChallenge::class)
        ->set('showingRecoveryCodeForm', true)
        ->set('recovery_code', 'recovery-code')
        ->call('verifyCode');

    $response->assertHasNoErrors();
    $response->assertRedirect(route('dashboard', absolute: false));
});

test('users cannot complete the two factor challenge with an invalid code', function (): void {
    // Create a user with 2FA enabled
    $user = User::factory()->create();
    $user->generateTwoFactorSecret();
    $user->generateRecoveryCodes();
    $user->confirmTwoFactor();
    $user->save();

    // Login to get to the 2FA challenge
    Livewire::test(Login::class)
        ->set('email', $user->email)
        ->set('password', 'password')
        ->call('login');

    // Mock the verification of the 2FA code
    $user = User::find($user->id); // Get a fresh instance

    // Create a partial mock of the User model
    $userMock = Mockery::mock($user)->makePartial();
    $userMock->shouldAllowMockingProtectedMethods();

    // Set up expectations for the verifyTwoFactorCode method
    $userMock->shouldReceive('verifyTwoFactorCode')
        ->with('invalid-code')
        ->andReturn(false);

    // Set the authenticated user to our mocked instance
    $this->actingAs($userMock);

    // Attempt to complete the 2FA challenge with an invalid code
    $response = Livewire::test(TwoFactorChallenge::class)
        ->set('code', 'invalid-code')
        ->call('verifyCode');

    $response->assertHasErrors(['code']);
});

test('users cannot complete the two factor challenge with an invalid recovery code', function (): void {
    // Create a user with 2FA enabled
    $user = User::factory()->create();
    $user->generateTwoFactorSecret();
    $user->generateRecoveryCodes();
    $user->confirmTwoFactor();
    $user->save();

    // Login to get to the 2FA challenge
    Livewire::test(Login::class)
        ->set('email', $user->email)
        ->set('password', 'password')
        ->call('login');

    // Mock the verification of the recovery code
    $user = User::find($user->id); // Get a fresh instance

    // Create a partial mock of the User model
    $userMock = Mockery::mock($user)->makePartial();
    $userMock->shouldAllowMockingProtectedMethods();

    // Set up expectations for the verifyRecoveryCode method
    $userMock->shouldReceive('verifyRecoveryCode')
        ->with('invalid-recovery-code')
        ->andReturn(false);

    // Set the authenticated user to our mocked instance
    $this->actingAs($userMock);

    // Attempt to complete the 2FA challenge with an invalid recovery code
    $response = Livewire::test(TwoFactorChallenge::class)
        ->set('showingRecoveryCodeForm', true)
        ->set('recovery_code', 'invalid-recovery-code')
        ->call('verifyCode');

    $response->assertHasErrors(['recovery_code']);
});

test('users can toggle between code and recovery code forms', function (): void {
    // Create a user with 2FA enabled
    $user = User::factory()->create();
    $user->generateTwoFactorSecret();
    $user->generateRecoveryCodes();
    $user->confirmTwoFactor();
    $user->save();

    $this->actingAs($user);

    // Toggle to recovery code form
    $livewire = Livewire::test(TwoFactorChallenge::class);
    expect($livewire->get('showingRecoveryCodeForm'))->toBeFalse();

    $livewire->call('toggleRecoveryCodeForm');
    expect($livewire->get('showingRecoveryCodeForm'))->toBeTrue();

    // Toggle back to regular code form
    $livewire->call('toggleRecoveryCodeForm');
    expect($livewire->get('showingRecoveryCodeForm'))->toBeFalse();
});

test('users can remember the device when completing the two factor challenge', function (): void {
    // Create a user with 2FA enabled
    $user = User::factory()->create();
    $user->generateTwoFactorSecret();
    $user->generateRecoveryCodes();
    $user->confirmTwoFactor();
    $user->save();

    // Login to get to the 2FA challenge
    Livewire::test(Login::class)
        ->set('email', $user->email)
        ->set('password', 'password')
        ->call('login');

    // Get a fresh instance and mock the methods
    $user = User::find($user->id);

    // Create a partial mock of the User model
    $userMock = Mockery::mock($user)->makePartial();
    $userMock->shouldAllowMockingProtectedMethods();

    // Set up expectations for the verifyTwoFactorCode method
    $userMock->shouldReceive('verifyTwoFactorCode')
        ->with('123456')
        ->andReturn(true);

    // Set up expectations for the trustDevice method
    $trustDeviceCalled = false;
    $userMock->shouldReceive('trustDevice')
        ->withAnyArgs()
        ->andReturnUsing(function () use (&$trustDeviceCalled): void {
            $trustDeviceCalled = true;
        });

    // Set the authenticated user to our mocked instance
    $this->actingAs($userMock);

    // Complete the 2FA challenge with remember device option
    Livewire::test(TwoFactorChallenge::class)
        ->set('code', '123456')
        ->set('rememberDevice', true)
        ->call('verifyCode');

    expect($trustDeviceCalled)->toBeTrue();
});
