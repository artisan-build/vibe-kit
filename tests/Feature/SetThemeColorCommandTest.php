<?php

declare(strict_types=1);

use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\File;

beforeEach(function (): void {
    // Backup the original CSS file
    $this->cssPath = resource_path('css/app.css');
    $this->cssBackup = File::get($this->cssPath);

    // Backup the .env file
    $this->envPath = base_path('.env');
    if (File::exists($this->envPath)) {
        $this->envBackup = File::get($this->envPath);
    }
});

afterEach(function (): void {
    // Restore the original CSS file
    File::put($this->cssPath, $this->cssBackup);

    // Restore the .env file
    if (isset($this->envBackup)) {
        File::put($this->envPath, $this->envBackup);
    }
});

test('lists available colors with --list option', function (): void {
    Artisan::call('theme:set-color', ['--list' => true]);

    $output = Artisan::output();

    expect($output)->toContain('Available FluxUI Color Palettes');
    expect($output)->toContain('Accent Colors');
    expect($output)->toContain('Base Colors');
    expect($output)->toContain('blue');
    expect($output)->toContain('zinc');
});

test('sets accent color via command arguments', function (): void {
    Artisan::call('theme:set-color', [
        'accent' => 'purple',
    ]);

    $cssContent = File::get($this->cssPath);
    $envContent = File::get($this->envPath);

    expect($cssContent)->toContain('--color-accent: var(--color-purple-600);');
    expect($envContent)->toContain('THEME_ACCENT_COLOR=purple');
    expect(Artisan::output())->toContain('✓ Accent color updated to: purple');
});

test('sets base color via command arguments', function (): void {
    Artisan::call('theme:set-color', [
        'base' => 'slate',
    ]);

    $cssContent = File::get($this->cssPath);
    $envContent = File::get($this->envPath);

    expect($cssContent)->toContain('--color-slate-50: #f8fafc');
    expect($envContent)->toContain('THEME_BASE_COLOR=slate');
    expect(Artisan::output())->toContain('✓ Base color updated to: slate');
});

test('sets both accent and base colors', function (): void {
    Artisan::call('theme:set-color', [
        'accent' => 'green',
        'base' => 'gray',
    ]);

    $cssContent = File::get($this->cssPath);
    $envContent = File::get($this->envPath);

    expect($cssContent)->toContain('--color-accent: var(--color-green-600);');
    expect($cssContent)->toContain('--color-gray-50: #f9fafb');
    expect($envContent)->toContain('THEME_ACCENT_COLOR=green');
    expect($envContent)->toContain('THEME_BASE_COLOR=gray');
});

test('validates invalid accent color', function (): void {
    $exitCode = Artisan::call('theme:set-color', [
        'accent' => 'invalid-color',
    ]);

    expect($exitCode)->toBe(1);
});

test('validates invalid base color', function (): void {
    $exitCode = Artisan::call('theme:set-color', [
        'base' => 'invalid-base',
    ]);

    expect($exitCode)->toBe(1);
});

test('adds accent color palette to CSS when not present', function (): void {
    Artisan::call('theme:set-color', [
        'accent' => 'rose',
    ]);

    $cssContent = File::get($this->cssPath);

    // Check that the full rose palette was added
    expect($cssContent)->toContain('--color-rose-50: #fff1f2');
    expect($cssContent)->toContain('--color-rose-500: #f43f5e');
    expect($cssContent)->toContain('--color-rose-950: #4c0519');
});

test('updates dark mode accent colors correctly', function (): void {
    Artisan::call('theme:set-color', [
        'accent' => 'indigo',
    ]);

    $cssContent = File::get($this->cssPath);

    // Check that dark mode uses 500 shade
    expect($cssContent)->toMatch('/\.dark\s*\{[^}]*--color-accent:\s*var\(--color-indigo-500\);/s');
});

test('shows success message with build instructions', function (): void {
    Artisan::call('theme:set-color', [
        'accent' => 'blue',
    ]);

    $output = Artisan::output();

    expect($output)->toContain('Theme updated successfully! 🎨');
    expect($output)->toContain('Run `npm run build` to compile the changes.');
});
