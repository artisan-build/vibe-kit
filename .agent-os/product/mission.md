# Product Mission

> Last Updated: 2025-10-14
> Version: 1.0.0

## Pitch

Vibe Kit is an AI-optimized Laravel starter kit that helps Laravel developers build production-grade SaaS applications faster by providing a robust foundation with code quality tooling, flexible architecture patterns, and seamless Agent OS integration for spec-driven LLM workflows.

## Users

### Primary Customers

- **Laravel Developers Building SaaS Products**: Developers who want to skip the repetitive setup work and jump straight into building unique features while maintaining high code quality standards.
- **Development Teams Using AI-Assisted Workflows**: Teams adopting spec-driven development with LLMs who need a starter kit optimized for that workflow.

### User Personas

**Solo SaaS Developer** (25-45 years old)
- **Role:** Full-Stack Laravel Developer / Indie Hacker
- **Context:** Building B2B or B2C SaaS products solo or with a small team, often using AI tools to accelerate development
- **Pain Points:**
  - Spending weeks setting up authentication, teams, billing, and code quality tooling for every new project
  - Struggling to keep AI-generated code consistent with best practices
  - Needing flexibility in domain language (e.g., "Family" vs "Team" vs "Organization")
- **Goals:**
  - Launch products faster without sacrificing code quality
  - Use AI effectively while maintaining production-grade standards
  - Build on proven patterns rather than reinventing the wheel

**Agency Development Team** (2-10 developers)
- **Role:** Laravel Development Agency
- **Context:** Building custom SaaS applications for clients with varying domain requirements
- **Pain Points:**
  - Each project requires customizing team/organization structures to match client domain language
  - Maintaining consistent code quality across multiple developers and AI-assisted workflows
  - Onboarding new developers to team standards and tooling
- **Goals:**
  - Standardize their starter template across projects
  - Deliver higher quality code faster using AI assistance
  - Reduce time spent on project scaffolding and setup

## The Problem

### Repetitive Project Scaffolding Wastes Developer Time

Every new Laravel SaaS project requires setting up the same foundational features: authentication, user settings, code quality tooling, and organizational structures. This can take 1-2 weeks before actual product development begins, and each implementation varies slightly, making it harder to maintain patterns across projects.

**Our Solution:** Vibe Kit provides a production-ready foundation with authentication, 2FA, settings management, robust code quality tooling (Pint, PHPStan, Rector, Pest), and an installer for flexible organization structures.

### Existing Starter Kits Force Domain Language

Most starter kits hard-code domain language like "Team" throughout the application, forcing developers to either use that terminology or spend time refactoring it to match their product's domain (e.g., "Family", "Organization", "Workspace").

**Our Solution:** Vibe Kit includes the Turbulence package, allowing developers to define their own domain language during setup, ensuring the organizational structure matches their product from day one.

### AI-Generated Code Often Lacks Consistency and Quality

While LLMs can generate code quickly, maintaining consistent code quality, testing standards, and architectural patterns across an AI-assisted project is challenging without proper guardrails and tooling.

**Our Solution:** Vibe Kit is optimized for Agent OS workflows with comprehensive LLM instructions (via Laravel Boost guidelines), automated code quality checks (`composer ready`), and strict standards that help AI agents produce production-grade code.

## Differentiators

### AI-First Architecture

Unlike traditional starter kits built for manual development, Vibe Kit is specifically optimized for spec-driven LLM workflows. It includes comprehensive Laravel Boost guidelines, Agent OS integration, and tooling configured at levels where LLMs can successfully resolve their own errors (PHPStan level 6).

### Flexible Domain Language via Turbulence

While Laravel Jetstream hard-codes "Team" throughout, Vibe Kit's Turbulence package allows developers to define organizational structures using their product's actual domain language during installation, eliminating the need for post-scaffold refactoring.

### Production-Grade Code Quality Tooling

Vibe Kit doesn't just include linters and static analysis—it provides a complete quality pipeline (`composer ready`) that runs Rector, Pint, PHPStan, and Pest in one command, ensuring both human and AI-generated code meets production standards before deployment.

## Key Features

### Core Features

- **Complete Authentication System:** Login, registration, password reset, email verification, and two-factor authentication using Laravel Fortify
- **User Settings Management:** Profile management, password changes, 2FA configuration, and appearance/theme customization
- **FluxUI Pro Integration:** Premium UI components for user-facing interfaces with professional design out of the box
- **Filament 4 Admin Panel:** Pre-configured admin panel for internal tooling and management interfaces

### Developer Experience Features

- **Comprehensive Code Quality Suite:** Integrated Pint (Laravel preset), PHPStan (level 6), Rector, and Pest with `composer ready` command
- **Agent OS Optimized:** Pre-configured with Laravel Boost guidelines and Agent OS installer for spec-driven LLM workflows
- **Flexible Development Workflow:** `composer dev` runs server, queue, logs, and Vite in parallel with one command
- **Robust Testing Setup:** Pest configured with browser testing, type coverage, and parallel test execution support

### Extensibility Features

- **Turbulence Package Integration:** Flexible organization/team structure with customizable domain language (installed via setup wizard)
- **Configurable Layout System:** Multiple authentication and application layouts ready to use or customize
- **Modular Package Architecture:** Optional billing and marketing packages installable via setup script without bloating core
