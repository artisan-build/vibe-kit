<?php

declare(strict_types=1);

return [
    'task_dir' => base_path('.tasks'),
    'ai_dir' => is_dir(base_path('ai')) ? base_path('ai') : __DIR__.'/../ai',
];
