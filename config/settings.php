<?php

declare(strict_types=1);

return [
    'two_factor_authentication' => env('TWO_FACTOR_AUTHENTICATION', true), // True if 2FA is enabled.
    'two_factor_trust' => env('TWO_FACTOR_TRUST', 30), // Days to trust a device if 2FA is enabled.
];
