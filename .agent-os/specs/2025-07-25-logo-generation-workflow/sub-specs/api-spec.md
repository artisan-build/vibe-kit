# API Specification

This is the API specification for the spec detailed in @.agent-os/specs/2025-07-25-logo-generation-workflow/spec.md

> Created: 2025-07-25
> Version: 1.0.0

## Endpoints

### GET /logo-preview/{session_id}

**Purpose:** Display the logo preview interface for a generation session
**Parameters:** 
- `session_id` (UUID, required) - The logo generation session identifier
**Response:** HTML page with Livewire component
**Errors:** 
- 404 - Session not found or expired
- 403 - Session not ready (still generating)

### POST /api/logo-generation/{session_id}/select

**Purpose:** Select a logo option from the generation session
**Parameters:**
- `session_id` (UUID, required) - The session identifier
- `option_id` (UUID, required) - The selected logo option ID
**Request Body:**
```json
{
    "option_id": "uuid-string",
    "generate_dark_variant": true
}
```
**Response:**
```json
{
    "success": true,
    "message": "Logo selected and processing started",
    "redirect_url": "/logo-preview/{session_id}/processing"
}
```
**Errors:**
- 404 - Session or option not found
- 422 - Invalid option ID
- 409 - Logo already selected for this session

### POST /api/logo-generation/{session_id}/regenerate

**Purpose:** Generate additional logo options for an existing session
**Parameters:**
- `session_id` (UUID, required) - The session identifier
**Request Body:**
```json
{
    "style_adjustments": "more colorful",
    "exclude_options": ["uuid-1", "uuid-2"]
}
```
**Response:**
```json
{
    "success": true,
    "message": "Generating 6 new logo options",
    "new_options_count": 6
}
```
**Errors:**
- 404 - Session not found
- 429 - Rate limit exceeded
- 409 - Generation already in progress

### GET /api/logo-generation/{session_id}/status

**Purpose:** Check the status of a logo generation session
**Parameters:**
- `session_id` (UUID, required) - The session identifier
**Response:**
```json
{
    "session_id": "uuid-string",
    "status": "ready",
    "options_count": 6,
    "selected_option_id": null,
    "expires_at": "2025-07-25T15:30:00Z",
    "preview_url": "/logo-preview/{session_id}"
}
```
**Errors:**
- 404 - Session not found

## Livewire Components

### LogoPreviewGrid Component

**Purpose:** Display grid of generated logo options
**Properties:**
- `session` - The logo generation session model
- `options` - Collection of logo options
- `selectedOption` - Currently selected option ID
**Actions:**
- `selectOption($optionId)` - Mark an option as selected
- `confirmSelection()` - Process the selected logo
- `generateMore()` - Request additional options

### LogoContextPreview Component

**Purpose:** Show logo in various real-world contexts
**Properties:**
- `logoUrl` - URL of the logo to preview
- `contexts` - Array of preview contexts
- `darkMode` - Toggle for dark mode preview
**Actions:**
- `toggleDarkMode()` - Switch between light/dark previews

## Internal Service APIs

### LogoGeneratorService

```php
interface LogoGeneratorServiceInterface
{
    public function generateFromBusinessPlan(
        string $appName,
        array $businessContext,
        int $count = 6
    ): LogoGenerationSession;
    
    public function generateOptions(
        LogoGenerationSession $session,
        array $adjustments = []
    ): Collection;
    
    public function processSelectedLogo(
        LogoGenerationOption $option,
        array $formats = ['svg', 'png', 'ico']
    ): array;
}
```

### BusinessPlanParserService

```php
interface BusinessPlanParserInterface
{
    public function parse(string $content): array;
    
    public function extractBusinessContext(string $filePath): array;
    
    public function generatePromptContext(array $businessData): array;
}
```

### AI Service Interface

```php
interface AILogoServiceInterface
{
    public function generateLogo(string $prompt, array $options = []): AIGenerationResult;
    
    public function isAvailable(): bool;
    
    public function getRateLimitStatus(): array;
}
```

## WebSocket Events (Future Enhancement)

### logo.generation.started
Fired when logo generation begins
```json
{
    "session_id": "uuid-string",
    "total_options": 6
}
```

### logo.generation.progress
Fired as each logo is generated
```json
{
    "session_id": "uuid-string",
    "completed": 3,
    "total": 6,
    "current_option": {
        "id": "uuid-string",
        "thumbnail_url": "/path/to/thumb.png"
    }
}
```

### logo.generation.completed
Fired when all logos are generated
```json
{
    "session_id": "uuid-string",
    "options_count": 6,
    "preview_url": "/logo-preview/{session_id}"
}
```