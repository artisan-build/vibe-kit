# Technical Specification

This is the technical specification for the spec detailed in @.agent-os/specs/2025-07-25-logo-generation-workflow/spec.md

> Created: 2025-07-25
> Version: 1.0.0

## Technical Requirements

### Core Architecture
- **Artisan Command**: Main entry point for logo generation workflow
- **AI Service Integration**: Abstracted service layer for multiple AI providers
- **Session Management**: Temporary storage of generation sessions and results
- **Preview Interface**: Livewire components for real-time logo preview
- **Asset Pipeline**: Image processing and format conversion system

### Command Structure
- Command class: `GenerateLogoCommand`
- Signature: `logo:generate {--name=} {--style=} {--colors=}`
- Automatically reads from `.agent-os/product/mission.md`
- Derives color scheme from `config/theme.php` and `resources/css/app.css`
- Interactive prompts for missing options
- Progress indicators during generation
- Automatic browser launch for preview

### AI Integration Requirements
- Primary framework: Prism PHP for multi-provider support
- Supported providers: OpenAI (DALL-E 3), Anthropic, Ollama
- Prompt template system for consistent results
- Built-in rate limiting and retry logic via Prism
- Error handling for API failures
- Provider switching without code changes

### Preview Interface Requirements
- Session-based access with expiration
- Grid layout with 6-8 logo options
- Real-world context previews (multiple sizes)
- Dark mode variant toggles
- Selection state management
- "Generate more" functionality

### Asset Processing Requirements
- SVG conversion using Imagick or API service
- Automatic favicon generation
- PNG exports in standard sizes (16, 32, 64, 128, 256, 512px)
- Apple touch icon generation (180x180)
- Compression and optimization
- Organized file structure in resources/images/logo/

### Agent OS Integration
- **Mission File Parsing**: Read `.agent-os/product/mission.md` for business context
- **Extract from Mission**:
  - Product pitch and positioning
  - Target users and personas
  - Key differentiators
  - Problem/solution pairs for brand values
- **Theme Integration**: 
  - Read accent color from `config/theme.php` environment variables
  - Parse CSS variables from `resources/css/app.css`
  - Use theme colors to guide logo color palette
- **Fallback**: Use command options if Agent OS files not found

## Approach Options

**Option A: Queue-Based Processing**
- Pros: Better for high-volume, non-blocking, scalable
- Cons: More complex, requires queue infrastructure

**Option B: Synchronous Processing (Selected)**
- Pros: Simpler implementation, immediate feedback, easier debugging
- Cons: Blocking operation, potential timeouts
- Rationale: For initial implementation, synchronous processing provides better developer experience and simpler architecture

## External Dependencies

- **echolabsdev/prism** - Multi-provider AI integration framework
- **Justification:** Supports multiple AI providers with unified API, built-in retries, and provider switching
- **intervention/image** - Image manipulation and format conversion
- **Justification:** Mature Laravel image processing library with SVG support
- **livewire/livewire** - Already installed, for preview interface
- **Justification:** Provides reactive UI without additional JavaScript framework

## Configuration Structure

```php
// config/logo-generator.php
return [
    'ai_provider' => env('LOGO_AI_PROVIDER', 'openai'),
    'providers' => [
        'openai' => [
            'api_key' => env('OPENAI_API_KEY'),
            'model' => 'dall-e-3',
            'size' => '1024x1024',
            'quality' => 'standard',
        ],
        'anthropic' => [
            'api_key' => env('ANTHROPIC_API_KEY'),
            'model' => 'claude-3-opus-20240229',
        ],
        'ollama' => [
            'url' => env('OLLAMA_URL', 'http://localhost:11434'),
            'model' => 'llava',
        ],
    ],
    'generation_count' => 6,
    'session_lifetime' => 3600, // 1 hour
    'output_formats' => ['svg', 'png', 'ico'],
    'png_sizes' => [16, 32, 64, 128, 256, 512],
    'mission_file_path' => '.agent-os/product/mission.md',
    'fallback_business_paths' => [
        'business-plan.md',
        'docs/business-plan.md',
    ],
];
```

## Performance Criteria

- Logo generation should complete within 30 seconds
- Preview interface should load within 2 seconds
- Asset processing should complete within 10 seconds
- Support concurrent generation sessions
- Implement caching for repeated prompts

## Security Considerations

- Sanitize all user inputs before AI API calls
- Validate generated images for appropriate content
- Secure session IDs to prevent unauthorized access
- Rate limit generation requests per user
- Store API keys securely in environment variables