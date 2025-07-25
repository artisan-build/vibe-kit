# CLAUDE.md

> Laravel Starter Kit AI Instructions
> Last Updated: 2025-01-24

## Agent OS Documentation

### Product Context
- **Mission & Vision:** @.agent-os/product/mission.md
- **Technical Architecture:** @.agent-os/product/tech-stack.md
- **Development Roadmap:** @.agent-os/product/roadmap.md
- **Decision History:** @.agent-os/product/decisions.md

### Development Standards
- **Code Style:** @~/.agent-os/standards/code-style.md
- **Best Practices:** @~/.agent-os/standards/best-practices.md

### Project Management
- **Active Specs:** @.agent-os/specs/
- **Spec Planning:** Use `@~/.agent-os/instructions/create-spec.md`
- **Tasks Execution:** Use `@~/.agent-os/instructions/execute-tasks.md`

## Workflow Instructions

When asked to work on this codebase:

1. **First**, check @.agent-os/product/roadmap.md for current priorities
2. **Then**, follow the appropriate instruction file:
   - For new features: @~/.agent-os/instructions/create-spec.md
   - For tasks execution: @~/.agent-os/instructions/execute-tasks.md
3. **Always**, adhere to the standards in the files listed above

## Important Notes

- Product-specific files in `.agent-os/product/` override any global standards
- User's specific instructions override (or amend) instructions found in `.agent-os/specs/...`
- Always adhere to established patterns, code style, and best practices documented above

## Project-Specific Context

This is an opinionated Laravel 12+ starter kit designed for AI-first development. Key aspects:

- **Purpose:** Rapid application development with AI assistance
- **Stack:** TALL (TailwindCSS, Alpine.js, Laravel, Livewire) + FilamentPHP + Flux Pro
- **Quality:** Strict standards with PHPStan, Pint, Rector, and PestPHP
- **Workflow:** Agent OS methodology for spec-driven development

When working on this starter kit:
- Maintain the AI-first philosophy in all decisions
- Ensure all features work well with AI code generation
- Keep the opinionated nature - make decisive choices
- Document patterns for AI comprehension