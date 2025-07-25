<?php

declare(strict_types=1);

namespace App\Contracts\LogoGeneration;

interface MissionParserInterface
{
    /**
     * Extract business context from mission file.
     *
     * @return array{
     *     industry: string|null,
     *     target_audience: array<int, string>,
     *     brand_personality: array<int, string>,
     *     values: array<int, string>,
     * }
     */
    public function extractBusinessContext(): array;

    /**
     * Extract theme colors from configuration and CSS.
     *
     * @return array{
     *     accent: string,
     *     base: string,
     *     accent_shade?: string,
     * }
     */
    public function extractThemeColors(): array;

    /**
     * Extract app name from mission file.
     */
    public function extractAppName(): ?string;

    /**
     * Generate prompt context from business data and colors.
     *
     * @param array{
     *     industry?: string|null,
     *     target_audience?: array<int, string>,
     *     brand_personality?: array<int, string>,
     *     values?: array<int, string>,
     * } $businessData
     * @param array{
     *     accent: string,
     *     base: string,
     * } $colors
     * @return array{
     *     style_keywords: array<int, string>,
     *     color_palette: array<int, string>,
     *     industry_context: array<int, string>,
     *     audience_context: array<int, string>,
     * }
     */
    public function generatePromptContext(array $businessData, array $colors): array;

    /**
     * Generate a complete logo prompt for AI generation.
     *
     * @param array{
     *     app_name?: string|null,
     *     industry?: string|null,
     *     target_audience?: array<int, string>,
     *     brand_personality?: array<int, string>,
     *     values?: array<int, string>,
     * } $businessContext
     * @param array{
     *     accent: string,
     *     base: string,
     * } $themeColors
     */
    public function generateLogoPrompt(array $businessContext, array $themeColors): string;
}
