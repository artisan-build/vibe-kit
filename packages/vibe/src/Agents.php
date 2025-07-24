<?php

declare(strict_types=1);

namespace ArtisanBuild\Vibe;

enum Agents: string
{
    case Claude = 'claude';
    case Cursor = 'cursor';
    case Gemini = 'gemini';
    case Junie = 'junie';

    public function instructionsFile(): string
    {
        return match ($this) {
            self::Claude => base_path('CLAUDE.md'),
            self::Cursor => base_path('.cursor/instructions.md'),
            self::Gemini => base_path('GEMINI.md'),
            self::Junie => base_path('JUNIE.md'),
        };
    }
}
