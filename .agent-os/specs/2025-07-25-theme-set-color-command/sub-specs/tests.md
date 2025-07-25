# Tests Specification

This is the tests coverage details for the spec detailed in @.agent-os/specs/2025-07-25-theme-set-color-command/spec.md

> Created: 2025-07-25
> Version: 1.0.0

## Test Coverage

### Unit Tests

**ThemeSetColorCommand**
- Test command executes successfully with valid color argument
- Test command prompts for color when no argument provided
- Test command validates color against FluxUI palette
- Test command accepts optional gray scale override
- Test command handles invalid color names gracefully

**CssThemeParser**
- Test parser correctly extracts CSS custom properties from simple CSS
- Test parser handles CSS with comments and complex formatting
- Test parser preserves non-theme CSS properties
- Test parser correctly updates theme color properties
- Test parser maintains original file formatting
- Test parser handles missing properties by adding them
- Test parser correctly handles nested media queries (dark mode)

### Feature Tests

**Theme Color Application**
- Test that running command updates app.css file correctly
- Test that color changes are applied to all required CSS properties
- Test that gray scale override works as expected
- Test that existing customizations in app.css are preserved
- Test that command creates backup before modifying CSS

### Integration Tests

**Interactive Mode**
- Test interactive color selection displays all available colors
- Test user can select color from interactive menu
- Test gray scale selection is offered after color selection
- Test command confirms changes before applying

### Mocking Requirements

- **Filesystem Operations:** Mock file reading/writing for unit tests to avoid actual file manipulation
- **Console Input/Output:** Mock user input for interactive mode testing
- **Command Execution:** Use Laravel's command testing helpers to simulate command runs