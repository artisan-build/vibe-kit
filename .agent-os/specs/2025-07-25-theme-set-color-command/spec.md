# Spec Requirements Document

> Spec: Theme Set Color Command
> Created: 2025-07-25
> Status: Planning

## Overview

Implement an Artisan command that allows developers to easily set and customize FluxUI theme colors in their application. This feature will enable rapid theme customization without manual CSS editing, making the starter kit more flexible for different branding requirements.

## User Stories

### Developer Theme Customization

As a developer using the Vibe starter kit, I want to quickly change my application's theme colors using a simple command, so that I can match my client's brand colors without manually editing CSS files.

The developer runs `php artisan theme:set-color blue` to change the primary theme color to blue. The command updates the app.css file with the appropriate FluxUI color values, and optionally allows customizing the gray scale. The changes are immediately visible after rebuilding assets with `npm run build`.

### Interactive Color Selection

As a developer, I want to be prompted for color selection when I don't specify a color, so that I can see all available options and make an informed choice.

When running `php artisan theme:set-color` without arguments, the command presents an interactive menu of available FluxUI colors. After selecting a color, the developer can optionally choose a gray scale variant to replace the default 'zinc' recommendation.

## Spec Scope

1. **Artisan Command Creation** - Create a new `theme:set-color` command that accepts an optional color parameter
2. **CSS Parser Implementation** - Build a robust CSS parser that can read, modify, and write back CSS custom properties while preserving file structure
3. **FluxUI Theme Integration** - Integrate with FluxUI's theme system, applying the correct color values based on their documentation
4. **Interactive Color Selection** - Provide an interactive prompt when no color is specified, showing all available FluxUI colors
5. **Gray Scale Customization** - Allow optional override of the recommended 'zinc' gray scale with other variants

## Out of Scope

- Real-time preview of theme changes in the browser
- GUI-based theme editor
- Custom color creation beyond FluxUI's predefined palette
- Automatic asset rebuilding after color changes
- Theme persistence across deployments

## Expected Deliverable

1. Developers can run `php artisan theme:set-color {color}` to set a FluxUI theme color, with changes reflected in app.css
2. Running the command without a color argument presents an interactive selection menu
3. The CSS parser correctly handles existing customizations in app.css without breaking them

## Spec Documentation

- Tasks: @.agent-os/specs/2025-07-25-theme-set-color-command/tasks.md
- Technical Specification: @.agent-os/specs/2025-07-25-theme-set-color-command/sub-specs/technical-spec.md
- Tests Specification: @.agent-os/specs/2025-07-25-theme-set-color-command/sub-specs/tests.md