<?php

declare(strict_types=1);

namespace Database\Factories;

use App\Models\LogoGenerationSession;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\LogoGenerationSession>
 */
class LogoGenerationSessionFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var class-string<\App\Models\LogoGenerationSession>
     */
    protected $model = LogoGenerationSession::class;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'app_name' => fake()->company(),
            'business_context' => [
                'industry' => fake()->randomElement(['fintech', 'healthcare', 'e-commerce', 'education', 'productivity']),
                'target_audience' => fake()->randomElement(['small businesses', 'enterprises', 'consumers', 'developers']),
                'brand_personality' => fake()->randomElements(['modern', 'professional', 'playful', 'trustworthy', 'innovative'], 2),
                'values' => fake()->randomElements(['security', 'simplicity', 'growth', 'reliability', 'innovation'], 2),
            ],
            'prompt_template' => fake()->sentence(10),
            'generated_options' => null,
            'selected_option_id' => null,
            'status' => 'pending',
            'expires_at' => Carbon::now()->addHour(),
        ];
    }

    /**
     * Indicate that the session is generating.
     */
    public function generating(): static
    {
        return $this->state(fn (array $attributes) => [
            'status' => 'generating',
        ]);
    }

    /**
     * Indicate that the session is ready.
     */
    public function ready(): static
    {
        return $this->state(fn (array $attributes) => [
            'status' => 'ready',
            'generated_options' => [
                ['option_id' => fake()->uuid(), 'thumbnail_url' => fake()->imageUrl()],
                ['option_id' => fake()->uuid(), 'thumbnail_url' => fake()->imageUrl()],
                ['option_id' => fake()->uuid(), 'thumbnail_url' => fake()->imageUrl()],
                ['option_id' => fake()->uuid(), 'thumbnail_url' => fake()->imageUrl()],
                ['option_id' => fake()->uuid(), 'thumbnail_url' => fake()->imageUrl()],
                ['option_id' => fake()->uuid(), 'thumbnail_url' => fake()->imageUrl()],
            ],
        ]);
    }

    /**
     * Indicate that the session is completed.
     */
    public function completed(): static
    {
        return $this->state(fn (array $attributes) => [
            'status' => 'completed',
            'selected_option_id' => fake()->uuid(),
        ]);
    }

    /**
     * Indicate that the session is expired.
     */
    public function expired(): static
    {
        return $this->state(fn (array $attributes) => [
            'status' => 'expired',
            'expires_at' => Carbon::now()->subMinute(),
        ]);
    }
}