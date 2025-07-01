# Vibe Kit by Artisan Build

> **⚠️ WARNING: This starter kit is still in active development and is not ready for production use. Please do not use this in production environments. We will announce when it is ready and has been added to Packagist.**

### An AI-Optimzied TALL Stack Starter Kit

This is the starter kit that we use for all of our TALL stack projects. It is highly opinionated and comes with tooling designed to offload the "boring stuff" about building a Laravel APP to AI agents.

It started life as Laravel's first-party Livewire (class-based) starter kit and has been modified and optimized to meet our needs.

**Important Note -** This starter kit includes components from [Flux Pro](https://fluxui.dev/pricing) and flux-pro is required in the composer.json. We **strongly recommend** that you purhcase a Flux Pro license because the quality and value are unmatched. If not, you can feel free to fork this starter kit and remove the Flux Pro dependencies. We are fully bought into Flux, so we will not be maintaining a version that does not rely on it.

**Important Note -** Some of the bundled LLM instructions assume that you are using [Herd Pro](https://herd.laravel.com/) as your local development environment. If you are not, then you'll need to modify some of the instructions to load things like code coverage in a different way.

## Installation

### Via Laravel Herd

One-click install a new application using this starter kit through [Laravel Herd](https://herd.laravel.com):

<a href="https://herd.laravel.com/new?starter-kit=artisan-build/vibe-kit"><img src="https://img.shields.io/badge/Install%20with%20Herd-fff?logo=laravel&logoColor=f53003" alt="Install with Herd"></a>

### Via the Laravel Installer

Create a new Laravel application using this starter kit through the official [Laravel Installer](https://laravel.com/docs/12.x/installation#installing-php):

```bash
laravel new my-app --using=artisan-build/vibe-kit
```

## What Is Included

### Opinionated Architecture

**Decoupled Authentication and Authorization -** The `User` model is only used to authenticate the user. Each user has one or more `Account` records, and we use the `Account` for authorization.

**FluxUI and Filament 4 Bundled -** We use FluxUI for anything user-facing and Filament for anything internal.

### Opinionated Workflow

**GitHub Actions CI/CD -** We use [Forge](https://forge.laravel.com) for most of our projects and [Vapor](https://vapor.laravel.com) for a handful of things. [Cloud](https://cloud.laravel.com) isn't a great fit for most of what we do at this point. So we have CI/CD GitHub Actions for both Forge and Vapor built into this starter kit.

**Conventient Tooling -** Run `composer ready` at the end of every task to make sure that your code is correctly formatted, PHPStan is passing, Rector has done its work, and all your tests are passing. If `composer ready runs without errors, then your CI pipeline should pass as well.

**Automated Cleanup and Refactoring -** This starter kit comes with Pint configured with the Laravel presets (this will likely evolve to include a few of our own personal preferences) and Rector with a handful of rules. 

**Strict Coding Standards -** This starter kit comes with PHPStan configured to run at level 6. We've found that this is the optimal level for new projects because it gives us really good type safety without getting us bogged down in fighting the tooling. It's also the level at which we've found LLMs can easily resolve their own errors.

**Test-Driven Development -** The LLM Instructions bundled with this starter kit emphasize testing and desccribe a TDD approach. While the agents may not always conform to that approach, we've found that in general, the code that is created by agents using our instructions is well tested.

### AI Agent Instructions

### ./agents/instructions.md

These are the core instructions that are used to guide all LLM workflows. We reference this file inside of AGENTS.md, cursor.md, and any other locations where the LLM agents we use expect to find project-specific instructions.

### .agents/mutation.md

These are instructions to guide the agent through our mutation testing workflow. Mutation testing is a great fit for LLM agents because it's repetitive, time-consuming, and simple. By running this workflow regularly, we build out our test suite to ensure more complete coverage of our application.






### Configurable Layouts

We built on Tony's original design for configurable layouts by adding more layouts and turning it into a configuration value.

## License

The Laravel Blade Starter Kit is open-sourced software licensed under the MIT license.
