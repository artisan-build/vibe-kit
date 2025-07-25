# Technical Specification

This is the technical specification for the spec detailed in @.agent-os/specs/2025-07-25-theme-set-color-command/spec.md

> Created: 2025-07-25
> Version: 1.0.0

## Technical Requirements

- Parse CSS files to extract custom property definitions (CSS variables)
- Modify CSS custom property values while preserving file structure, comments, and formatting
- Support FluxUI's theme color system as documented at https://fluxui.dev/themes
- Handle both root-level CSS variables and those within media queries (e.g., dark mode)
- Provide interactive command-line interface using Laravel's built-in console features
- Validate color choices against FluxUI's available color palette
- Support optional gray scale customization beyond the default 'zinc' recommendation

## Approach Options

**Option A:** Simple string replacement
- Pros: Quick to implement, minimal dependencies
- Cons: Fragile with customizations, may break formatting, difficult to maintain

**Option B:** Regular expression based parsing (Selected)
- Pros: More robust than string replacement, can preserve formatting, no external dependencies
- Cons: Complex regex patterns needed, still somewhat brittle

**Option C:** Full CSS AST parser
- Pros: Most robust solution, handles all edge cases
- Cons: Requires external dependency, overkill for our needs

**Rationale:** Option B provides the right balance of robustness and simplicity. We can use regex to identify CSS custom properties and their values, build an in-memory representation, modify as needed, and reconstruct the CSS while preserving structure.

## Implementation Details

### CSS Parser Strategy

1. Read the entire app.css file content
2. Use regex to extract all CSS custom property definitions with their values
3. Build an associative array mapping property names to values
4. Preserve the original file structure by tracking property positions
5. Update only the theme-related properties based on FluxUI documentation
6. Reconstruct the CSS by replacing old values with new ones at their original positions

### FluxUI Color Mapping

The command will map FluxUI color names to their CSS custom property sets:
- Primary color properties (--primary-50 through --primary-950)
- Gray scale properties (--gray-50 through --gray-950)
- Any additional theme-specific properties defined by FluxUI

### Command Structure

```php
class ThemeSetColorCommand extends Command
{
    protected $signature = 'theme:set-color {color?} {--gray=}';
    protected $description = 'Set the application theme color using FluxUI color palette';
}
```

### File Location

The command will modify: `resources/css/app.css`

## External Dependencies

No new external dependencies are required. The implementation will use:
- Laravel's built-in Console Command features
- PHP's native file handling functions
- PHP's built-in regex capabilities
- Laravel's filesystem helpers