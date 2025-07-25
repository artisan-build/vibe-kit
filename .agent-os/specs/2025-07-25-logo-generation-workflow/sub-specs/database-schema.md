# Database Schema

This is the database schema implementation for the spec detailed in @.agent-os/specs/2025-07-25-logo-generation-workflow/spec.md

> Created: 2025-07-25
> Version: 1.0.0

## Schema Changes

### New Tables

#### logo_generation_sessions
Stores temporary session data for logo generation workflow

```sql
CREATE TABLE logo_generation_sessions (
    id CHAR(36) PRIMARY KEY,
    app_name VARCHAR(255) NOT NULL,
    business_context JSON NULL,
    prompt_template TEXT NULL,
    generated_options JSON NULL,
    selected_option_id VARCHAR(255) NULL,
    status ENUM('pending', 'generating', 'ready', 'completed', 'expired') DEFAULT 'pending',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    expires_at TIMESTAMP NOT NULL,
    INDEX idx_status (status),
    INDEX idx_expires_at (expires_at)
);
```

#### logo_generation_options
Stores individual logo options within a session

```sql
CREATE TABLE logo_generation_options (
    id CHAR(36) PRIMARY KEY,
    session_id CHAR(36) NOT NULL,
    prompt TEXT NOT NULL,
    ai_service VARCHAR(50) NOT NULL,
    image_url TEXT NULL,
    image_data LONGBLOB NULL,
    metadata JSON NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (session_id) REFERENCES logo_generation_sessions(id) ON DELETE CASCADE,
    INDEX idx_session_id (session_id)
);
```

## Migration Files

### Create Logo Generation Sessions Table
```php
Schema::create('logo_generation_sessions', function (Blueprint $table) {
    $table->uuid('id')->primary();
    $table->string('app_name');
    $table->json('business_context')->nullable();
    $table->text('prompt_template')->nullable();
    $table->json('generated_options')->nullable();
    $table->string('selected_option_id')->nullable();
    $table->enum('status', ['pending', 'generating', 'ready', 'completed', 'expired'])
          ->default('pending');
    $table->timestamps();
    $table->timestamp('expires_at');
    
    $table->index('status');
    $table->index('expires_at');
});
```

### Create Logo Generation Options Table
```php
Schema::create('logo_generation_options', function (Blueprint $table) {
    $table->uuid('id')->primary();
    $table->foreignUuid('session_id')->constrained('logo_generation_sessions')->cascadeOnDelete();
    $table->text('prompt');
    $table->string('ai_service', 50);
    $table->text('image_url')->nullable();
    $table->longText('image_data')->nullable();
    $table->json('metadata')->nullable();
    $table->timestamp('created_at')->useCurrent();
    
    $table->index('session_id');
});
```

## Data Structure Examples

### logo_generation_sessions.business_context
```json
{
    "industry": "fintech",
    "target_audience": "small business owners",
    "brand_personality": ["professional", "trustworthy", "innovative"],
    "values": ["security", "simplicity", "growth"],
    "color_preferences": ["blue", "green"],
    "style_preferences": "modern minimalist"
}
```

### logo_generation_sessions.generated_options
```json
[
    {
        "option_id": "uuid-1",
        "thumbnail_url": "/storage/logos/temp/session-id/option-1-thumb.png",
        "prompt_variation": "modern minimalist logo for fintech"
    },
    {
        "option_id": "uuid-2",
        "thumbnail_url": "/storage/logos/temp/session-id/option-2-thumb.png",
        "prompt_variation": "professional geometric logo for financial services"
    }
]
```

### logo_generation_options.metadata
```json
{
    "dimensions": {
        "width": 1024,
        "height": 1024
    },
    "format": "png",
    "ai_model": "dall-e-3",
    "generation_time": 5.23,
    "prompt_tokens": 150,
    "style_attributes": ["minimalist", "geometric", "professional"]
}
```

## Rationale

- **UUID primary keys**: Better for distributed systems and prevents ID guessing
- **JSON columns**: Flexible storage for varying business contexts and metadata
- **Separate options table**: Allows for efficient querying and storage of individual logos
- **Status enum**: Clear workflow states for session management
- **Expiration handling**: Automatic cleanup of old sessions
- **Image storage options**: Both URL and blob storage for flexibility
- **Indexing strategy**: Optimized for common queries (status checks, expiration cleanup)