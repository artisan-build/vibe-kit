<?php

declare(strict_types=1);

use App\Services\LogoGeneration\MissionParser;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\File;

beforeEach(function (): void {
    $this->parser = new MissionParser;
});

test('can parse mission file and extract business context', function (): void {
    $missionContent = <<<'MARKDOWN'
# Product Mission

> Last Updated: 2025-01-24
> Version: 1.0.0

## Pitch

TaskMaster is a productivity software designed for small business owners that helps them manage tasks efficiently by providing intuitive task management and collaboration features.

## Users

### Primary Customers

- **Small Business Owners**: Entrepreneurs running businesses with 1-50 employees
- **Freelancers**: Independent professionals managing multiple projects

### User Personas

**Busy Entrepreneur** (30-45 years old)
- **Role:** Small Business Owner
- **Context:** Managing multiple projects and team members
- **Pain Points:** Task overload, poor organization, missed deadlines
- **Goals:** Streamline workflows, improve team productivity

## The Problem

### Task Management Chaos

Small businesses struggle with scattered tasks across emails, sticky notes, and various apps. This fragmentation leads to missed deadlines and decreased productivity.

**Our Solution:** Centralized task management with smart prioritization.

## Differentiators

### AI-Powered Prioritization

Unlike traditional task managers, we use AI to automatically prioritize tasks based on deadlines, dependencies, and business impact.

## Key Features

### Core Features

- **Smart Task Lists:** AI-organized task prioritization
- **Team Collaboration:** Real-time updates and assignments
- **Progress Tracking:** Visual dashboards and reports
MARKDOWN;

    File::shouldReceive('exists')
        ->with(base_path('.agent-os/product/mission.md'))
        ->andReturn(true);

    File::shouldReceive('get')
        ->with(base_path('.agent-os/product/mission.md'))
        ->andReturn($missionContent);

    $context = $this->parser->extractBusinessContext();

    expect($context)->toBeArray()
        ->and($context['industry'])->toBe('productivity software')
        ->and($context['target_audience'])->toContain('small business owners')
        ->and($context['brand_personality'])->toContain('efficient')
        // 'smart' is extracted from features section which contains "Smart Task Lists"
        ->and($context['values'])->toContain('productivity')
        ->and($context['values'])->toContain('organization');
});

test('can extract theme colors from configuration', function (): void {
    Config::set('theme.colors.accent', 'blue');
    Config::set('theme.colors.base', 'zinc');

    // Mock CSS file reading
    File::shouldReceive('exists')
        ->with(resource_path('css/app.css'))
        ->andReturn(true);

    File::shouldReceive('get')
        ->with(resource_path('css/app.css'))
        ->andReturn('--color-accent: var(--color-blue-600);');

    $colors = $this->parser->extractThemeColors();

    expect($colors)->toBeArray()
        ->and($colors['accent'])->toBe('blue')
        ->and($colors['base'])->toBe('zinc')
        ->and($colors['accent_shade'])->toBe('blue-600');
});

test('handles missing mission file gracefully', function (): void {
    File::shouldReceive('exists')
        ->with(base_path('.agent-os/product/mission.md'))
        ->andReturn(false);

    // Mock fallback paths
    File::shouldReceive('exists')
        ->with(base_path('business-plan.md'))
        ->andReturn(false);

    File::shouldReceive('exists')
        ->with(base_path('docs/business-plan.md'))
        ->andReturn(false);

    $context = $this->parser->extractBusinessContext();

    expect($context)->toBeArray()
        ->and($context)->toHaveKey('industry')
        ->and($context['industry'])->toBeNull();
});

test('can generate prompt context from business data', function (): void {
    $businessData = [
        'industry' => 'fintech',
        'target_audience' => ['small businesses', 'entrepreneurs'],
        'brand_personality' => ['trustworthy', 'innovative', 'professional'],
        'values' => ['security', 'simplicity'],
    ];

    $colors = [
        'accent' => 'blue',
        'base' => 'gray',
    ];

    $promptContext = $this->parser->generatePromptContext($businessData, $colors);

    expect($promptContext)->toBeArray()
        ->and($promptContext['style_keywords'])->toContain('trustworthy')
        ->and($promptContext['style_keywords'])->toContain('professional')
        ->and($promptContext['color_palette'])->toContain('blue')
        ->and($promptContext['industry_context'])->toContain('fintech')
        ->and($promptContext['audience_context'])->toContain('small businesses');
});

test('extracts app name from mission pitch', function (): void {
    $missionContent = <<<'MARKDOWN'
# Product Mission

## Pitch

FinanceFlow is a personal finance management tool that helps individuals track expenses and budget effectively.
MARKDOWN;

    File::shouldReceive('exists')
        ->with(base_path('.agent-os/product/mission.md'))
        ->andReturn(true);

    File::shouldReceive('get')
        ->with(base_path('.agent-os/product/mission.md'))
        ->andReturn($missionContent);

    $appName = $this->parser->extractAppName();

    expect($appName)->toBe('FinanceFlow');
});

test('parses user personas to extract brand personality', function (): void {
    $missionContent = <<<'MARKDOWN'
# Product Mission

## Users

### User Personas

**Tech-Savvy Professional** (25-35 years old)
- **Role:** Software Developer
- **Context:** Building modern applications
- **Pain Points:** Complex setup, slow development
- **Goals:** Rapid prototyping, clean code

**Creative Designer** (30-40 years old)
- **Role:** UI/UX Designer
- **Context:** Creating beautiful interfaces
- **Pain Points:** Limited customization options
- **Goals:** Flexible design system
MARKDOWN;

    File::shouldReceive('exists')
        ->with(base_path('.agent-os/product/mission.md'))
        ->andReturn(true);

    File::shouldReceive('get')
        ->with(base_path('.agent-os/product/mission.md'))
        ->andReturn($missionContent);

    $context = $this->parser->extractBusinessContext();

    expect($context['brand_personality'])->toContain('modern')
        ->and($context['brand_personality'])->toContain('creative')
        ->and($context['brand_personality'])->toContain('flexible');
});

test('extracts values from problem and solution sections', function (): void {
    $missionContent = <<<'MARKDOWN'
# Product Mission

## The Problem

### Security Vulnerabilities

Applications often have security flaws that expose user data. This creates trust issues and compliance problems.

**Our Solution:** Bank-level security with automated vulnerability scanning.

### Performance Issues

Slow applications frustrate users and hurt business growth.

**Our Solution:** Lightning-fast performance with intelligent caching.
MARKDOWN;

    File::shouldReceive('exists')
        ->with(base_path('.agent-os/product/mission.md'))
        ->andReturn(true);

    File::shouldReceive('get')
        ->with(base_path('.agent-os/product/mission.md'))
        ->andReturn($missionContent);

    $context = $this->parser->extractBusinessContext();

    expect($context['values'])->toContain('security')
        ->and($context['values'])->toContain('performance')
        ->and($context['values'])->toContain('trust');
});

test('handles multiple fallback paths for business plan', function (): void {
    File::shouldReceive('exists')
        ->with(base_path('.agent-os/product/mission.md'))
        ->andReturn(false);

    File::shouldReceive('exists')
        ->with(base_path('business-plan.md'))
        ->andReturn(true);

    File::shouldReceive('get')
        ->with(base_path('business-plan.md'))
        ->andReturn('# Business Plan\n\nOur product is an e-commerce platform...');

    $context = $this->parser->extractBusinessContext();

    expect($context['industry'])->toContain('e-commerce');
});

test('generates complete prompt template for logo generation', function (): void {
    $businessContext = [
        'app_name' => 'TaskFlow',
        'industry' => 'productivity software',
        'target_audience' => ['remote teams', 'project managers'],
        'brand_personality' => ['modern', 'efficient', 'collaborative'],
        'values' => ['productivity', 'teamwork'],
    ];

    $themeColors = [
        'accent' => 'indigo',
        'base' => 'slate',
    ];

    $prompt = $this->parser->generateLogoPrompt($businessContext, $themeColors);

    expect($prompt)->toBeString()
        ->and($prompt)->toContain('TaskFlow')
        ->and($prompt)->toContain('productivity software')
        ->and($prompt)->toContain('modern')
        ->and($prompt)->toContain('indigo')
        ->and($prompt)->toContain('minimalist square logo');
});
