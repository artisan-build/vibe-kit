<?php

declare(strict_types=1);

return [
    /*
    |--------------------------------------------------------------------------
    | Theme Configuration
    |--------------------------------------------------------------------------
    |
    | This file is for storing the configuration for the application's theme.
    | You can specify which layout to use as the default theme.
    |
    */

    'app_layout' => env('APP_LAYOUT', 'app.sidebar'),
    'guest_layout' => env('GUEST_LAYOUT', 'guest.simple'),
    'auth_layout' => env('AUTH_LAYOUT', 'auth.split'),
    'universal_layout' => env('UNIVERSAL_LAYOUT', 'app.sidebar'),

    /*
    |--------------------------------------------------------------------------
    | Color Palette Configuration
    |--------------------------------------------------------------------------
    |
    | Configure the color palette for your application. These settings control
    | the FluxUI theme colors used throughout the application.
    |
    */

    'colors' => [
        'accent' => env('THEME_ACCENT_COLOR', 'blue'),
        'base' => env('THEME_BASE_COLOR', 'zinc'),
    ],
];
