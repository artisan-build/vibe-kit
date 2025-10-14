# Technical Stack

> Last Updated: 2025-10-14
> Version: 1.0.0

## Application Framework

- **Framework:** Laravel
- **Version:** 12.0+
- **Language:** PHP 8.2+
- **Architecture:** Modern Laravel 12 streamlined structure (no app/Http/Middleware/, uses bootstrap/app.php)

## Database

- **Primary Database:** SQLite (development)
- **ORM:** Eloquent
- **Migration Strategy:** Laravel migrations with version prefixes
- **Schema Design:** Separation of authentication (users table) from authorization (planned via Turbulence)

## Frontend Framework

- **Framework:** Livewire (Class-Based)
- **Version:** 3.x
- **Note:** Livewire Volt is listed in dependencies but intentionally not used; will be removed in future cleanup
- **Approach:** Full-page Livewire components for interactive pages

## Admin Panel

- **Framework:** Filament
- **Version:** 4.x (even while in beta)
- **Purpose:** Internal admin interfaces and management panels

## CSS Framework

- **Framework:** Tailwind CSS
- **Version:** 4.0+
- **Build Tool:** Vite
- **Configuration:** PostCSS with Tailwind

## UI Component Library

- **Library:** Flux UI Pro
- **Version:** 2.x
- **Installation Method:** Composer (flux-pro repository)
- **License Required:** Yes (commercial license from fluxui.dev)
- **Usage:** All user-facing UI components

## Authentication & Security

- **Authentication Package:** Laravel Fortify
- **Version:** 1.27+
- **Features Implemented:**
  - Login / Registration
  - Password Reset
  - Email Verification
  - Two-Factor Authentication (via pragmarx/google2fa packages)
- **Session Management:** Standard Laravel sessions

## Assets & Icons

- **Icon Libraries:** Heroicons (via Flux UI), Lucide (custom icons)
- **Custom Icons:** Stored in resources/views/flux/icon/
- **Loading Strategy:** Blade components

## Code Quality & Testing

### Linting & Formatting
- **PHP Formatter:** Laravel Pint 1.18+
- **Preset:** Laravel (with Spatie stubs)
- **Auto-fixer:** Duster 3.2+

### Static Analysis
- **Tool:** PHPStan 2.1+ via Larastan 3.4+
- **Level:** 6 (optimized for LLM error resolution)
- **IDE Helper:** barryvdh/laravel-ide-helper 3.5+

### Refactoring
- **Tool:** Rector 2.0+
- **Laravel Rules:** driftingly/rector-laravel 2.0+

### Testing
- **Framework:** Pest 4.0+
- **Plugins:**
  - pest-plugin-laravel 4.0+
  - pest-plugin-browser 4.0+ (browser testing)
  - pest-plugin-type-coverage 4.0+
- **Assertion Style:** Pest (not PHPUnit)
- **Parallel Testing:** Supported

### Quality Pipeline
- **Command:** `composer ready`
- **Steps:**
  1. Clear config cache
  2. Generate IDE helper models
  3. Run Rector automated refactoring
  4. Run Duster/Pint code formatting
  5. Run PHPStan static analysis
  6. Run Pest test suite
- **Purpose:** One command to ensure production-ready code

## Development Tools

### Local Development
- **Recommended Environment:** Laravel Herd Pro
- **Alternative:** Laravel Sail 1.41+
- **Debug Tool:** Laravel Debugbar 3.15+ (dev only)
- **Log Viewer:** Laravel Pail 1.2.2+

### Package Development
- **Turbulence:** dev-main (organization/team structure)
- **Agent OS Installer:** dev-main (scaffolds Agent OS integration)

### Asset Building
- **Build Tool:** Vite
- **Package Manager:** npm
- **Dev Command:** `composer dev` (runs server, queue, logs, and Vite in parallel)

## Infrastructure & Deployment

### Application Hosting
- **Primary:** Laravel Forge
- **Alternative:** Laravel Vapor (for select projects)
- **Note:** Laravel Cloud not currently used

### CI/CD Pipeline
- **Platform:** GitHub Actions
- **Workflows:** Configured for both Forge and Vapor deployments
- **Triggers:** Push to main/staging branches
- **Quality Gates:** Must pass `composer ready` checks

### Asset Hosting
- **Strategy:** Compiled assets served with application
- **CDN:** Not configured by default

## Security & Monitoring

- **Security Auditing:** roave/security-advisories (dev-latest)
- **Dependency Management:** Composer with prefer-stable
- **Session Security:** Configured via config/session.php
- **CORS:** Not configured by default

## AI Development Integration

### Laravel Boost
- **Package:** laravel/boost 1.1+
- **Purpose:** MCP server with Laravel-specific tools
- **Features:**
  - Artisan command listing
  - Tinker execution
  - Browser log reading
  - Documentation search
- **Documentation:** Comprehensive Laravel Boost guidelines in CLAUDE.md

### Agent OS
- **Installer:** artisan-build/agent-os-installer
- **Standards Location:** ~/.agent-os/
- **Instructions Location:** ~/.agent-os/instructions/
- **Product Documentation:** .agent-os/product/ (this directory)
- **Spec Management:** .agent-os/specs/

## Code Repository

- **Platform:** GitHub
- **Repository:** artisan-build/vibe-kit
- **License:** MIT
- **Visibility:** Public (starter kit)

## Package Management

- **PHP Packages:** Composer
- **JavaScript Packages:** npm
- **Private Repositories:**
  - Flux Pro: composer.fluxui.dev
  - Turbulence: GitHub (artisan-build)
  - Agent OS Installer: GitHub (artisan-build)
