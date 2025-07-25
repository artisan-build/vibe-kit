<?php

declare(strict_types=1);

use App\Models\LogoGenerationOption;
use App\Models\LogoGenerationSession;
use Carbon\Carbon;

test('can create a logo generation option', function (): void {
    $session = LogoGenerationSession::create([
        'app_name' => 'TestApp',
        'expires_at' => Carbon::now()->addHour(),
    ]);

    $option = LogoGenerationOption::create([
        'session_id' => $session->id,
        'prompt' => 'Create a modern minimalist logo',
        'ai_service' => 'openai',
        'image_url' => 'https://example.com/logo.png',
        'metadata' => [
            'dimensions' => ['width' => 1024, 'height' => 1024],
            'ai_model' => 'dall-e-3',
        ],
    ]);

    expect($option)->toBeInstanceOf(LogoGenerationOption::class)
        ->and($option->prompt)->toBe('Create a modern minimalist logo')
        ->and($option->ai_service)->toBe('openai')
        ->and($option->image_url)->toBe('https://example.com/logo.png');
});

test('metadata is cast to array', function (): void {
    $session = LogoGenerationSession::create([
        'app_name' => 'TestApp',
        'expires_at' => Carbon::now()->addHour(),
    ]);

    $option = LogoGenerationOption::create([
        'session_id' => $session->id,
        'prompt' => 'Test prompt',
        'ai_service' => 'openai',
        'metadata' => [
            'dimensions' => ['width' => 1024, 'height' => 1024],
            'format' => 'png',
            'generation_time' => 5.23,
        ],
    ]);

    expect($option->metadata)->toBeArray()
        ->and($option->metadata['dimensions'])->toBeArray()
        ->and($option->metadata['dimensions']['width'])->toBe(1024)
        ->and($option->metadata['format'])->toBe('png');
});

test('belongs to a logo generation session', function (): void {
    $session = LogoGenerationSession::create([
        'app_name' => 'TestApp',
        'expires_at' => Carbon::now()->addHour(),
    ]);

    $option = LogoGenerationOption::create([
        'session_id' => $session->id,
        'prompt' => 'Test prompt',
        'ai_service' => 'openai',
    ]);

    expect($option->session)->toBeInstanceOf(LogoGenerationSession::class)
        ->and($option->session->app_name)->toBe('TestApp');
});

test('session id is required', function (): void {
    LogoGenerationOption::create([
        'prompt' => 'Test prompt',
        'ai_service' => 'openai',
    ]);
})->throws(\Illuminate\Database\QueryException::class);

test('prompt is required', function (): void {
    $session = LogoGenerationSession::create([
        'app_name' => 'TestApp',
        'expires_at' => Carbon::now()->addHour(),
    ]);

    LogoGenerationOption::create([
        'session_id' => $session->id,
        'ai_service' => 'openai',
    ]);
})->throws(\Illuminate\Database\QueryException::class);

test('ai service is required', function (): void {
    $session = LogoGenerationSession::create([
        'app_name' => 'TestApp',
        'expires_at' => Carbon::now()->addHour(),
    ]);

    LogoGenerationOption::create([
        'session_id' => $session->id,
        'prompt' => 'Test prompt',
    ]);
})->throws(\Illuminate\Database\QueryException::class);

test('can store image data', function (): void {
    $session = LogoGenerationSession::create([
        'app_name' => 'TestApp',
        'expires_at' => Carbon::now()->addHour(),
    ]);

    $imageData = base64_encode('fake image data');

    $option = LogoGenerationOption::create([
        'session_id' => $session->id,
        'prompt' => 'Test prompt',
        'ai_service' => 'openai',
        'image_data' => $imageData,
    ]);

    expect($option->image_data)->toBe($imageData);
});

test('deleting session cascades to options', function (): void {
    $session = LogoGenerationSession::create([
        'app_name' => 'TestApp',
        'expires_at' => Carbon::now()->addHour(),
    ]);

    $option1 = LogoGenerationOption::create([
        'session_id' => $session->id,
        'prompt' => 'Option 1',
        'ai_service' => 'openai',
    ]);

    $option2 = LogoGenerationOption::create([
        'session_id' => $session->id,
        'prompt' => 'Option 2',
        'ai_service' => 'openai',
    ]);

    expect(LogoGenerationOption::count())->toBe(2);

    $session->delete();

    expect(LogoGenerationOption::count())->toBe(0);
});

test('can retrieve all options for a session', function (): void {
    $session1 = LogoGenerationSession::create([
        'app_name' => 'App1',
        'expires_at' => Carbon::now()->addHour(),
    ]);

    $session2 = LogoGenerationSession::create([
        'app_name' => 'App2',
        'expires_at' => Carbon::now()->addHour(),
    ]);

    // Create options for session 1
    LogoGenerationOption::create([
        'session_id' => $session1->id,
        'prompt' => 'Option 1 for App1',
        'ai_service' => 'openai',
    ]);

    LogoGenerationOption::create([
        'session_id' => $session1->id,
        'prompt' => 'Option 2 for App1',
        'ai_service' => 'openai',
    ]);

    // Create option for session 2
    LogoGenerationOption::create([
        'session_id' => $session2->id,
        'prompt' => 'Option for App2',
        'ai_service' => 'openai',
    ]);

    $session1Options = LogoGenerationOption::where('session_id', $session1->id)->get();

    expect($session1Options)->toHaveCount(2)
        ->and($session1Options->pluck('prompt')->toArray())->toContain('Option 1 for App1')
        ->and($session1Options->pluck('prompt')->toArray())->toContain('Option 2 for App1')
        ->and($session1Options->pluck('prompt')->toArray())->not->toContain('Option for App2');
});
