<?php

declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * @property string $id
 * @property string $session_id
 * @property string $prompt
 * @property string $ai_service
 * @property string|null $image_url
 * @property string|null $image_data
 * @property array<array-key, mixed>|null $metadata
 * @property string $created_at
 * @property-read \App\Models\LogoGenerationSession $session
 *
 * @method static \Database\Factories\LogoGenerationOptionFactory factory($count = null, $state = [])
 * @method static \Illuminate\Database\Eloquent\Builder<static>|LogoGenerationOption newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|LogoGenerationOption newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|LogoGenerationOption query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|LogoGenerationOption whereAiService($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|LogoGenerationOption whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|LogoGenerationOption whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|LogoGenerationOption whereImageData($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|LogoGenerationOption whereImageUrl($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|LogoGenerationOption whereMetadata($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|LogoGenerationOption wherePrompt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|LogoGenerationOption whereSessionId($value)
 *
 * @mixin \Eloquent
 */
class LogoGenerationOption extends Model
{
    use HasFactory, HasUuids;

    /**
     * Indicates if the model should be timestamped.
     *
     * @var bool
     */
    public $timestamps = false;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'session_id',
        'prompt',
        'ai_service',
        'image_url',
        'image_data',
        'metadata',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'metadata' => 'array',
        ];
    }

    /**
     * Get the session that owns this option.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo<LogoGenerationSession, LogoGenerationOption>
     */
    public function session(): BelongsTo
    {
        return $this->belongsTo(LogoGenerationSession::class, 'session_id');
    }
}
