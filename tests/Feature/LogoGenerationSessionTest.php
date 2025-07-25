<?php

declare(strict_types=1);

use App\Models\LogoGenerationOption;
use App\Models\LogoGenerationSession;
use Carbon\Carbon;

test('can create a logo generation session', function (): void {
    $session = LogoGenerationSession::create([
        'app_name' => 'MyTestApp',
        'business_context' => [
            'industry' => 'fintech',
            'target_audience' => 'small business owners',
            'brand_personality' => ['professional', 'trustworthy'],
        ],
        'prompt_template' => 'Create a modern logo for a fintech app',
        'status' => 'pending',
        'expires_at' => Carbon::now()->addHour(),
    ]);

    expect($session)->toBeInstanceOf(LogoGenerationSession::class)
        ->and($session->app_name)->toBe('MyTestApp')
        ->and($session->business_context['industry'])->toBe('fintech')
        ->and($session->status)->toBe('pending');
});

test('business context is cast to array', function (): void {
    $session = LogoGenerationSession::create([
        'app_name' => 'TestApp',
        'business_context' => [
            'industry' => 'healthcare',
            'values' => ['trust', 'care'],
        ],
        'expires_at' => Carbon::now()->addHour(),
    ]);

    expect($session->business_context)->toBeArray()
        ->and($session->business_context['industry'])->toBe('healthcare')
        ->and($session->business_context['values'])->toBeArray();
});

test('generated options are cast to array', function (): void {
    $session = LogoGenerationSession::create([
        'app_name' => 'TestApp',
        'generated_options' => [
            ['option_id' => 'uuid-1', 'thumbnail_url' => '/path/to/thumb1.png'],
            ['option_id' => 'uuid-2', 'thumbnail_url' => '/path/to/thumb2.png'],
        ],
        'expires_at' => Carbon::now()->addHour(),
    ]);

    expect($session->generated_options)->toBeArray()
        ->and($session->generated_options)->toHaveCount(2)
        ->and($session->generated_options[0]['option_id'])->toBe('uuid-1');
});

test('can have many logo generation options', function (): void {
    $session = LogoGenerationSession::create([
        'app_name' => 'TestApp',
        'expires_at' => Carbon::now()->addHour(),
    ]);

    $option1 = $session->options()->create([
        'prompt' => 'Modern minimalist logo',
        'ai_service' => 'openai',
        'image_url' => 'https://example.com/logo1.png',
    ]);

    $option2 = $session->options()->create([
        'prompt' => 'Professional geometric logo',
        'ai_service' => 'openai',
        'image_url' => 'https://example.com/logo2.png',
    ]);

    expect($session->options)->toHaveCount(2)
        ->and($session->options->first())->toBeInstanceOf(LogoGenerationOption::class)
        ->and($session->options->first()->prompt)->toBe('Modern minimalist logo');
});

test('expires at is required', function (): void {
    LogoGenerationSession::create([
        'app_name' => 'TestApp',
    ]);
})->throws(\Illuminate\Database\QueryException::class);

test('app name is required', function (): void {
    LogoGenerationSession::create([
        'expires_at' => Carbon::now()->addHour(),
    ]);
})->throws(\Illuminate\Database\QueryException::class);

test('status defaults to pending', function (): void {
    $session = LogoGenerationSession::create([
        'app_name' => 'TestApp',
        'expires_at' => Carbon::now()->addHour(),
    ]);

    expect($session->status)->toBe('pending');
});

test('can update session status', function (): void {
    $session = LogoGenerationSession::create([
        'app_name' => 'TestApp',
        'expires_at' => Carbon::now()->addHour(),
    ]);

    $session->update(['status' => 'generating']);
    expect($session->fresh()->status)->toBe('generating');

    $session->update(['status' => 'ready']);
    expect($session->fresh()->status)->toBe('ready');
});

test('can check if session is expired', function (): void {
    $activeSession = LogoGenerationSession::create([
        'app_name' => 'ActiveApp',
        'expires_at' => Carbon::now()->addHour(),
    ]);

    $expiredSession = LogoGenerationSession::create([
        'app_name' => 'ExpiredApp',
        'expires_at' => Carbon::now()->subMinute(),
    ]);

    expect($activeSession->isExpired())->toBeFalse()
        ->and($expiredSession->isExpired())->toBeTrue();
});

test('can scope to active sessions', function (): void {
    // Create active sessions
    LogoGenerationSession::create([
        'app_name' => 'ActiveApp1',
        'expires_at' => Carbon::now()->addHour(),
    ]);

    LogoGenerationSession::create([
        'app_name' => 'ActiveApp2',
        'expires_at' => Carbon::now()->addMinutes(30),
    ]);

    // Create expired session
    LogoGenerationSession::create([
        'app_name' => 'ExpiredApp',
        'expires_at' => Carbon::now()->subMinute(),
    ]);

    $activeSessions = LogoGenerationSession::active()->get();

    expect($activeSessions)->toHaveCount(2)
        ->and($activeSessions->pluck('app_name')->toArray())->not->toContain('ExpiredApp');
});

test('can scope to expired sessions', function (): void {
    // Create active session
    LogoGenerationSession::create([
        'app_name' => 'ActiveApp',
        'expires_at' => Carbon::now()->addHour(),
    ]);

    // Create expired sessions
    LogoGenerationSession::create([
        'app_name' => 'ExpiredApp1',
        'expires_at' => Carbon::now()->subMinute(),
    ]);

    LogoGenerationSession::create([
        'app_name' => 'ExpiredApp2',
        'expires_at' => Carbon::now()->subHour(),
    ]);

    $expiredSessions = LogoGenerationSession::expired()->get();

    expect($expiredSessions)->toHaveCount(2)
        ->and($expiredSessions->pluck('app_name')->toArray())->not->toContain('ActiveApp');
});
