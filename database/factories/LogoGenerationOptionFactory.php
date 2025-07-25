<?php

declare(strict_types=1);

namespace Database\Factories;

use App\Models\LogoGenerationOption;
use App\Models\LogoGenerationSession;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\LogoGenerationOption>
 */
class LogoGenerationOptionFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var class-string<\App\Models\LogoGenerationOption>
     */
    protected $model = LogoGenerationOption::class;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'session_id' => LogoGenerationSession::factory(),
            'prompt' => fake()->sentence(15),
            'ai_service' => fake()->randomElement(['openai', 'stable-diffusion', 'midjourney']),
            'image_url' => fake()->imageUrl(1024, 1024, 'logo'),
            'image_data' => null,
            'metadata' => [
                'dimensions' => [
                    'width' => 1024,
                    'height' => 1024,
                ],
                'format' => 'png',
                'ai_model' => fake()->randomElement(['dall-e-3', 'stable-diffusion-xl', 'midjourney-v6']),
                'generation_time' => fake()->randomFloat(2, 2, 10),
                'prompt_tokens' => fake()->numberBetween(50, 200),
                'style_attributes' => fake()->randomElements(['minimalist', 'geometric', 'professional', 'modern', 'abstract'], 3),
            ],
        ];
    }

    /**
     * Indicate that the option has image data stored.
     */
    public function withImageData(): static
    {
        return $this->state(fn (array $attributes) => [
            'image_data' => base64_encode(fake()->text(1000)),
            'image_url' => null,
        ]);
    }

    /**
     * Indicate that the option uses OpenAI.
     */
    public function openai(): static
    {
        return $this->state(fn (array $attributes) => [
            'ai_service' => 'openai',
            'metadata' => array_merge($attributes['metadata'] ?? [], [
                'ai_model' => 'dall-e-3',
            ]),
        ]);
    }
}
