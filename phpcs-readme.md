# PHP_CodeSniffer with Slevomat Coding Standard - Code Quality Advisor

This project uses PHP_CodeSniffer (PHPCS) with the Slevomat Coding Standard as a **code quality advisor** to surface refactoring opportunities and improvements. It's intentionally separate from the `composer ready` workflow and complements (rather than duplicates) what Pint and PHPStan already handle.

## Philosophy

This setup is designed to **suggest improvements**, not enforce basic standards:
- **Pint** enforces coding style (spacing, formatting, structure)
- **PHPStan** enforces type safety and static analysis
- **PHPCS** suggests refactoring opportunities and quality improvements

## What This Setup Provides

### 1. Code Complexity Detection
- **Cognitive Complexity (threshold: 15)** - Identifies overly complex methods that could be simplified

### 2. Type Coverage Analysis
- Missing parameter type hints
- Missing return type hints
- Missing property type hints (except in Models and Commands where parent classes don't type them)

### 3. Dead Code Detection
- Unused variables
- Unused imports
- Useless variable assignments

### 4. Code Smell Detection
- Useless if conditions
- Useless ternary operators
- Opportunities for early exit (reduce nesting)
- Places where null coalesce (`??`) could be used

### 5. Modern PHP Patterns
- Constructor property promotion opportunities

### 6. Exception Handling
- Dead catch blocks (catches but doesn't use the exception)

## Running PHPCS

### Check Code Quality (Run Anytime)
```bash
composer sniff
```
This is your **code review assistant** - run it when you want suggestions for improving code quality.

### Auto-Fix What's Fixable
```bash
composer sniff-fix
```
Automatically fixes violations that have a clear solution:
- Adds missing type hints
- Removes unused imports
- Applies constructor property promotion

**Note:** Not all suggestions are auto-fixable. Complex refactorings (like simplifying complex methods) require manual work.

## Integration Philosophy

PHPCS is **not** part of `composer ready` by design:
- Use `composer ready` for gating commits (must pass: Rector, Pint, PHPStan, Tests)
- Use `composer sniff` as an advisor for continuous improvement

Think of it like this:
- `composer ready` = "Can I commit this?"
- `composer sniff` = "Could this be better?"

## What Gets Scanned

PHPCS analyzes these directories:
- `app/` - Application code
- `config/` - Configuration files
- `routes/` - Route definitions

### Excluded Files
- Test files
- Database files (migrations, seeders, factories)
- Blade templates
- Generated files
- Vendor directories

## Understanding Violations

### Auto-Fixable (marked with `[x]`)
Run `composer sniff-fix` to apply these automatically:
- Missing type hints
- Unused imports
- Constructor property promotion

### Manual Refactoring Needed
These require thought and code changes:

**High Complexity:**
```php
// PHPCS warns: Cognitive complexity 20 exceeds 15
public function complexMethod()
{
    // Many nested ifs, loops, conditions
}

// Refactor by extracting methods:
public function complexMethod()
{
    $this->validateInput();
    $this->processData();
    $this->sendResponse();
}
```

**Early Exit Opportunities:**
```php
// Current
public function handle()
{
    if ($condition) {
        // lots of code
    }
}

// Better - early exit reduces nesting
public function handle()
{
    if (! $condition) {
        return;
    }

    // lots of code at normal indentation
}
```

**Null Coalesce:**
```php
// Current
$value = isset($array['key']) ? $array['key'] : 'default';

// Better
$value = $array['key'] ?? 'default';
```

## Performance

- First run: ~100-120ms
- Cached runs: ~40-60ms
- Memory: ~10MB
- Uses 8 cores for parallel processing

## Why Not Enforce Everything?

### Pint Already Handles:
- Spacing and indentation
- Brace placement
- Line length
- Array formatting
- Import statement formatting
- Declare statement placement

### PHPStan Already Handles:
- Type safety
- Dead code detection (partially)
- Invalid type usage

### PHPCS Adds:
- **Complexity metrics** (cognitive complexity)
- **Refactoring suggestions** (early exit, null coalesce)
- **Type hint coverage** (complementing PHPStan)
- **Code smell detection** (useless conditions, variables)

## Configuration

The ruleset in `phpcs.xml` focuses on:
- Complexity analysis
- Type coverage
- Dead code
- Code smells
- Modern PHP patterns

**What's NOT included:**
- PSR-12 formatting rules (Pint handles this)
- Spacing/indentation rules (Pint handles this)
- Strict coding style enforcement (Pint handles this)

## Customizing Thresholds

### Adjust Complexity Threshold
Edit `phpcs.xml`:
```xml
<rule ref="SlevomatCodingStandard.Complexity.Cognitive">
    <properties>
        <property name="maxComplexity" value="20"/>
    </properties>
</rule>
```

### Disable Specific Rules
```xml
<rule ref="SlevomatCodingStandard.ControlStructures.EarlyExit">
    <severity>0</severity>
</rule>
```

### Suppress in Code
```php
/**
 * @phpcsSuppress SlevomatCodingStandard.Complexity.Cognitive
 */
public function intentionallyComplexMethod()
{
    // Complex logic that can't be simplified
}
```

## Workflow Recommendations

### During Development
```bash
# Write code
# Fix obvious issues
composer lint  # Format with Pint
composer stan  # Check types with PHPStan
composer sniff # Get improvement suggestions (optional)
composer test  # Run tests
```

### Before Committing
```bash
composer ready  # Must pass: Rector, Pint, PHPStan, Tests
```

### Periodic Code Review
```bash
composer sniff  # Review refactoring opportunities
composer sniff-fix  # Apply auto-fixes
# Manually refactor complex methods
```

## CI/CD Integration

PHPCS is optional in CI since it's advisory:

### Option 1: Skip in CI
Don't run `composer sniff` in CI - it's a local development tool.

### Option 2: Non-Blocking Report
```yaml
- name: Code Quality Report
  run: composer sniff
  continue-on-error: true
```

### Option 3: Metrics Collection
```yaml
- name: Collect Code Quality Metrics
  run: vendor/bin/phpcs --report=json > phpcs-report.json
  continue-on-error: true
```

## Common Suggestions

### "Property does not have native type hint"
```php
// Before
protected $name;

// After - adds safety and documentation
protected string $name;
```

### "Use early exit to reduce code nesting"
```php
// Before - nested
public function handle()
{
    if ($valid) {
        // many lines
        if ($approved) {
            // many more lines
        }
    }
}

// After - early exits
public function handle()
{
    if (! $valid) {
        return;
    }

    // many lines

    if (! $approved) {
        return;
    }

    // many more lines - now at base indentation
}
```

### "Cognitive Complexity X exceeds 15"
Break into smaller, focused methods:
```php
// Before - one complex method
public function processOrder($order)
{
    // 50 lines of validation
    // 30 lines of processing
    // 20 lines of notifications
}

// After - extracted responsibilities
public function processOrder($order)
{
    $this->validateOrder($order);
    $this->processPayment($order);
    $this->updateInventory($order);
    $this->sendNotifications($order);
}
```

## Benefits

### 1. Continuous Improvement
Surfaces opportunities to improve code without blocking development.

### 2. Learning Tool
Helps developers understand better patterns through suggestions.

### 3. Refactoring Guide
Provides concrete suggestions for improving existing code.

### 4. No Duplication
Doesn't enforce what Pint already handles, reducing tool overlap.

## Resources

- [Slevomat Coding Standard - All Sniffs](https://github.com/slevomat/coding-standard#sniffs-included-in-this-standard)
- [Cognitive Complexity Explained](https://www.sonarsource.com/docs/CognitiveComplexity.pdf)
- [PHP_CodeSniffer Documentation](https://github.com/squizlabs/PHP_CodeSniffer/wiki)

## For Agent OS Installer

### Package
```bash
composer require slevomat/coding-standard --dev
```

### Configuration
- **`phpcs.xml`** - Focused on quality, not formatting
- **`.gitignore`** - Add `.phpcs.cache`

### Scripts
```json
{
  "sniff": ["vendor/bin/phpcs"],
  "sniff-fix": ["vendor/bin/phpcbf"]
}
```

### Key Differences from Standard Setup
1. **Not in `composer ready`** - It's advisory, not blocking
2. **No PSR-12 base** - Pint handles formatting
3. **Only quality rules** - Complexity, dead code, type hints
4. **Laravel-optimized** - Allows mixed types, array hints where needed
