<?php

declare(strict_types=1);

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

/**
 * @property string $id
 * @property string $app_name
 * @property array<array-key, mixed>|null $business_context
 * @property string|null $prompt_template
 * @property array<array-key, mixed>|null $generated_options
 * @property string|null $selected_option_id
 * @property string $status
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property \Illuminate\Support\Carbon $expires_at
 * @property-read \Illuminate\Database\Eloquent\Collection<int, \App\Models\LogoGenerationOption> $options
 * @property-read int|null $options_count
 *
 * @method static \Illuminate\Database\Eloquent\Builder<static>|LogoGenerationSession active()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|LogoGenerationSession expired()
 * @method static \Database\Factories\LogoGenerationSessionFactory factory($count = null, $state = [])
 * @method static \Illuminate\Database\Eloquent\Builder<static>|LogoGenerationSession newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|LogoGenerationSession newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|LogoGenerationSession query()
 * @method static \Illuminate\Database\Eloquent\Builder<static>|LogoGenerationSession whereAppName($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|LogoGenerationSession whereBusinessContext($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|LogoGenerationSession whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|LogoGenerationSession whereExpiresAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|LogoGenerationSession whereGeneratedOptions($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|LogoGenerationSession whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|LogoGenerationSession wherePromptTemplate($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|LogoGenerationSession whereSelectedOptionId($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|LogoGenerationSession whereStatus($value)
 * @method static \Illuminate\Database\Eloquent\Builder<static>|LogoGenerationSession whereUpdatedAt($value)
 *
 * @mixin \Eloquent
 */
class LogoGenerationSession extends Model
{
    use HasFactory, HasUuids;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'app_name',
        'business_context',
        'prompt_template',
        'generated_options',
        'selected_option_id',
        'status',
        'expires_at',
    ];

    /**
     * The attributes that should have default values.
     *
     * @var array<string, mixed>
     */
    protected $attributes = [
        'status' => 'pending',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'business_context' => 'array',
            'generated_options' => 'array',
            'expires_at' => 'datetime',
        ];
    }

    /**
     * Get the logo generation options for this session.
     */
    public function options(): HasMany
    {
        return $this->hasMany(LogoGenerationOption::class, 'session_id');
    }

    /**
     * Check if the session has expired.
     */
    public function isExpired(): bool
    {
        return $this->expires_at->isPast();
    }

    /**
     * Scope a query to only include active (non-expired) sessions.
     *
     * @param  \Illuminate\Database\Eloquent\Builder<LogoGenerationSession>  $query
     * @return \Illuminate\Database\Eloquent\Builder<LogoGenerationSession>
     */
    public function scopeActive($query)
    {
        return $query->where('expires_at', '>', Carbon::now());
    }

    /**
     * Scope a query to only include expired sessions.
     *
     * @param  \Illuminate\Database\Eloquent\Builder<LogoGenerationSession>  $query
     * @return \Illuminate\Database\Eloquent\Builder<LogoGenerationSession>
     */
    public function scopeExpired($query)
    {
        return $query->where('expires_at', '<=', Carbon::now());
    }
}
