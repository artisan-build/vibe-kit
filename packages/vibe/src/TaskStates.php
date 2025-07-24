<?php

declare(strict_types=1);

namespace ArtisanBuild\Vibe;

enum TaskStates: string
{
    case InProgress = 'in-progress';
    case Parked = 'parked';
    case Canceled = 'canceled';
    case Completed = 'completed';

    public function ongoingTask(): bool
    {
        return in_array($this, [
            self::Started,
            self::InProgress,
        ]);
    }
}
