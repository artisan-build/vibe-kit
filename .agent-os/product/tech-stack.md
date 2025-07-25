# Technical Stack

> Last Updated: 2025-01-24
> Version: 1.0.0

## Core Technologies

### Application Framework
- **Framework:** Laravel
- **Version:** 12.0+
- **Language:** PHP 8.2+

### Database
- **Primary:** SQLite

## Frontend Stack

### Frontend Framework
- **Framework:** Livewire
- **Version:** 3.5+

### Admin Panel
- **Framework:** FilamentPHP
- **Version:** 4.0 (Beta)

### CSS Framework
- **Framework:** TailwindCSS
- **Version:** 4.0+
- **PostCSS:** Yes

### UI Components
- **Library:** FluxUI Pro
- **Version:** Latest
- **Installation:** Via composer

## Assets & Media

### Fonts
- **Provider:** Google Fonts
- **Loading Strategy:** Self-hosted for performance

### Icons
- **Library:** Heroicons and Lucide
- **Implementation:** FluxUI Icon Component

## Development Tools

### Build Tools
- **Bundler:** Vite
- **Version:** 5.0+
- **Plugin:** Laravel Vite Plugin

### Code Quality
- **Linting:** Laravel Pint
- **Static Analysis:** PHPStan (Level 6)
- **Refactoring:** Rector
- **Testing:** PestPHP

### Development Server
- **Local Environment:** Laravel Herd Pro (recommended)
- **Concurrent Runners:** Custom composer scripts for dev workflow

## Infrastructure

### Application Hosting
- **Platform:** Laravel Forge (planned)

### Database Hosting
- **Platform:** Integrated with application hosting

### Asset Hosting
- **Platform:** Integrated with application hosting

## Deployment

### CI/CD Pipeline
- **Platform:** GitHub Actions
- **Trigger:** Push to main/staging branches
- **Tests:** Run before deployment

### Environments
- **Production:** main branch
- **Staging:** staging branch
- **Review Apps:** PR-based (optional)

### Code Repository
- **URL:** https://github.com/[organization]/[repository]

## Authentication & Security

### Authentication
- **Package:** Laravel Jetstream
- **Driver:** Fortify
- **Features:** 2FA, Email Verification, Password Reset

### Session Management
- **Driver:** Database
- **Security:** HTTPS-only cookies

## Package Management

### PHP Dependencies
- **Manager:** Composer
- **Key Packages:**
  - filament/filament: ^4.0
  - livewire/livewire: ^3.5
  - livewire/volt: ^1.7
  - livewire/flux-pro: ^1.0
  - laravel/jetstream: ^5.3
  - pestphp/pest: ^3.7

### JavaScript Dependencies
- **Manager:** NPM/Yarn
- **Minimal Dependencies:** Only build tools (Vite, TailwindCSS)

## Development Standards

### Code Style
- **PHP:** PSR-12 via Laravel Pint
- **Enforcement:** Pre-commit hooks and CI checks

### Testing Strategy
- **Framework:** PestPHP
- **Coverage:** Feature and Unit tests
- **TDD:** Encouraged workflow

### Documentation
- **API:** OpenAPI/Swagger (when applicable)
- **Code:** PHPDoc for complex methods
- **AI Instructions:** .agents/ directory structure