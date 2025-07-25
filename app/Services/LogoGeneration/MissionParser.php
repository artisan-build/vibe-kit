<?php

declare(strict_types=1);

namespace App\Services\LogoGeneration;

use App\Contracts\LogoGeneration\MissionParserInterface;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Str;

class MissionParser implements MissionParserInterface
{
    /**
     * Extract business context from mission file.
     */
    public function extractBusinessContext(): array
    {
        $missionContent = $this->readMissionFile();

        if (empty($missionContent)) {
            return [
                'industry' => null,
                'target_audience' => [],
                'brand_personality' => [],
                'values' => [],
            ];
        }

        return [
            'industry' => $this->extractIndustry($missionContent),
            'target_audience' => $this->extractTargetAudience($missionContent),
            'brand_personality' => $this->extractBrandPersonality($missionContent),
            'values' => $this->extractValues($missionContent),
        ];
    }

    /**
     * Extract theme colors from configuration and CSS.
     */
    public function extractThemeColors(): array
    {
        $colors = [
            'accent' => Config::get('theme.colors.accent', 'blue'),
            'base' => Config::get('theme.colors.base', 'zinc'),
        ];

        // Try to extract specific shade from CSS
        $cssPath = resource_path('css/app.css');
        if (File::exists($cssPath)) {
            $cssContent = File::get($cssPath);
            if (preg_match('/--color-accent:\s*var\(--color-('.$colors['accent'].'-\d+)\)/', $cssContent, $matches)) {
                $colors['accent_shade'] = $matches[1];
            }
        }

        return $colors;
    }

    /**
     * Extract app name from mission file.
     */
    public function extractAppName(): ?string
    {
        $missionContent = $this->readMissionFile();

        if (empty($missionContent)) {
            return null;
        }

        // Look for app name in the pitch section
        if (preg_match('/## Pitch\s*\n\s*\n\s*([A-Z][\w\s]+?)\s+is/', $missionContent, $matches)) {
            return trim($matches[1]);
        }

        return null;
    }

    /**
     * Generate prompt context from business data and colors.
     */
    public function generatePromptContext(array $businessData, array $colors): array
    {
        $context = [
            'style_keywords' => [],
            'color_palette' => [],
            'industry_context' => [],
            'audience_context' => [],
        ];

        // Add brand personality as style keywords
        if (! empty($businessData['brand_personality'])) {
            $context['style_keywords'] = $businessData['brand_personality'];
        }

        // Add colors to palette
        $context['color_palette'][] = $colors['accent'];
        if ($colors['accent'] !== $colors['base']) {
            $context['color_palette'][] = $colors['base'];
        }

        // Add industry context
        if (! empty($businessData['industry'])) {
            $context['industry_context'][] = $businessData['industry'];
        }

        // Add audience context
        if (! empty($businessData['target_audience'])) {
            $context['audience_context'] = $businessData['target_audience'];
        }

        return $context;
    }

    /**
     * Generate a complete logo prompt for AI generation.
     */
    public function generateLogoPrompt(array $businessContext, array $themeColors): string
    {
        $appName = $businessContext['app_name'] ?? 'the application';
        $industry = $businessContext['industry'] ?? 'technology';
        $personality = ! empty($businessContext['brand_personality'])
            ? implode(', ', $businessContext['brand_personality'])
            : 'modern, professional';
        $mainColor = $themeColors['accent'] ?? 'blue';

        $prompt = "Create a minimalist square logo for {$appName}, ";
        $prompt .= "a {$industry} application. ";
        $prompt .= "The logo should be {$personality}. ";
        $prompt .= "Use {$mainColor} as the primary color. ";
        $prompt .= 'The design should work well at small sizes and be suitable for app icons. ';
        $prompt .= 'Keep it simple, memorable, and avoid text unless absolutely essential.';

        return $prompt;
    }

    /**
     * Read the mission file content.
     */
    private function readMissionFile(): string
    {
        $primaryPath = base_path('.agent-os/product/mission.md');

        if (File::exists($primaryPath)) {
            return File::get($primaryPath);
        }

        // Check fallback paths
        $fallbackPaths = [
            'business-plan.md',
            'docs/business-plan.md',
        ];

        foreach ($fallbackPaths as $path) {
            $fullPath = base_path($path);
            if (File::exists($fullPath)) {
                return File::get($fullPath);
            }
        }

        return '';
    }

    /**
     * Extract industry from mission content.
     */
    private function extractIndustry(string $content): ?string
    {
        // Look for industry mentions in pitch
        if (preg_match('/is\s+(?:a|an)\s+([\w\s]+?)\s+(?:that|designed|for)/i', $content, $matches)) {
            return strtolower(trim($matches[1]));
        }

        // Look for e-commerce mentions
        if (Str::contains(strtolower($content), ['e-commerce', 'ecommerce', 'online store'])) {
            return 'e-commerce';
        }

        return null;
    }

    /**
     * Extract target audience from mission content.
     *
     * @return array<int, string>
     */
    private function extractTargetAudience(string $content): array
    {
        $audience = [];

        // Extract from Primary Customers section
        if (preg_match('/### Primary Customers\s*\n(.+?)(?=\n###|\n##|$)/s', $content, $matches)) {
            $customersSection = $matches[1];
            if (preg_match_all('/\*\*([^*]+)\*\*/', $customersSection, $customerMatches)) {
                foreach ($customerMatches[1] as $customer) {
                    $audience[] = strtolower(trim($customer));
                }
            }
        }

        // Extract from pitch if not found
        if (empty($audience) && preg_match('/helps?\s+([\w\s,]+?)\s+(?:to|by|manage|track)/i', $content, $matches)) {
            $audience[] = strtolower(trim($matches[1]));
        }

        return array_unique($audience);
    }

    /**
     * Extract brand personality from mission content.
     *
     * @return array<int, string>
     */
    private function extractBrandPersonality(string $content): array
    {
        $personality = [];
        $keywords = [];

        // Extract from user personas goals
        if (preg_match_all('/\*\*Goals:\*\*\s*([^\n]+)/i', $content, $matches)) {
            foreach ($matches[1] as $goals) {
                $goalWords = array_map('trim', explode(',', strtolower($goals)));
                foreach ($goalWords as $goal) {
                    // Extract adjectives from goals
                    if (Str::contains($goal, ['rapid', 'fast', 'quick'])) {
                        $keywords[] = 'fast';
                    }
                    if (Str::contains($goal, ['clean', 'modern', 'beautiful'])) {
                        $keywords[] = 'modern';
                    }
                    if (Str::contains($goal, ['flexible', 'customiz'])) {
                        $keywords[] = 'flexible';
                    }
                    if (Str::contains($goal, ['secure', 'safe'])) {
                        $keywords[] = 'secure';
                    }
                    if (Str::contains($goal, ['creative', 'design'])) {
                        $keywords[] = 'creative';
                    }
                    if (Str::contains($goal, ['streamline', 'improve'])) {
                        $keywords[] = 'efficient';
                    }
                }
            }
        }

        // Extract from differentiators
        if (preg_match('/## Differentiators(.+?)(?=\n##|$)/s', $content, $matches)) {
            $diffSection = strtolower($matches[1]);
            if (Str::contains($diffSection, ['ai', 'smart', 'intelligent', 'ai-powered'])) {
                $keywords[] = 'smart';
            }
            if (Str::contains($diffSection, ['trust', 'reliable'])) {
                $keywords[] = 'trustworthy';
            }
            if (Str::contains($diffSection, ['innovat'])) {
                $keywords[] = 'innovative';
            }
            if (Str::contains($diffSection, ['profession'])) {
                $keywords[] = 'professional';
            }
        }

        // Extract from key features
        if (preg_match('/## Key Features(.+?)(?=\n##|$)/s', $content, $matches)) {
            $featuresSection = strtolower($matches[1]);
            if (Str::contains($featuresSection, ['smart', 'ai', 'intelligent'])) {
                $keywords[] = 'smart';
            }
        }

        // Extract from solution descriptions
        if (preg_match_all('/\*\*Our Solution:\*\*\s*([^\n]+)/i', $content, $matches)) {
            foreach ($matches[1] as $solution) {
                $solutionLower = strtolower($solution);
                if (Str::contains($solutionLower, ['efficient', 'streamline', 'centralized', 'smart'])) {
                    $keywords[] = 'efficient';
                }
            }
        }

        return array_values(array_unique($keywords));
    }

    /**
     * Extract values from mission content.
     *
     * @return array<int, string>
     */
    private function extractValues(string $content): array
    {
        $values = [];

        // Extract from problem/solution pairs
        if (preg_match_all('/###\s*([^\n]+)\s*\n\s*\n([^#]+?)\*\*Our Solution:\*\*/s', $content, $matches)) {
            foreach ($matches[1] as $index => $problem) {
                $problemLower = strtolower($problem);
                $solutionLower = strtolower($matches[2][$index]);

                // Extract values based on problems being solved
                if (Str::contains($problemLower, ['security', 'vulnerab'])) {
                    $values[] = 'security';
                }
                if (Str::contains($problemLower, ['performance', 'slow', 'speed'])) {
                    $values[] = 'performance';
                }
                if (Str::contains($problemLower, ['chaos', 'disorganiz', 'scatter', 'management'])) {
                    $values[] = 'organization';
                }
                if (Str::contains($problemLower, ['productiv', 'task'])) {
                    $values[] = 'productivity';
                }
                if (Str::contains($problemLower, ['trust', 'reliab'])) {
                    $values[] = 'trust';
                }
                if (Str::contains($problemLower, ['collaborat', 'team'])) {
                    $values[] = 'teamwork';
                }

                // Also check solution content for trust/reliability
                if (Str::contains($solutionLower, ['trust', 'reliab', 'secure'])) {
                    $values[] = 'trust';
                }
                if (Str::contains($solutionLower, ['productiv'])) {
                    $values[] = 'productivity';
                }
            }
        }

        // Also check the entire content for certain keywords
        $contentLower = strtolower($content);
        if (Str::contains($contentLower, ['productivity', 'productive'])) {
            $values[] = 'productivity';
        }

        return array_values(array_unique($values));
    }
}
