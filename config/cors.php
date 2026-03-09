<?php

return [
    // CORS dihandle oleh CorsMiddleware custom, bukan package ini
    // File ini tetap ada agar tidak error saat config:cache
    'paths' => ['api/*'],
    'allowed_methods' => ['*'],
    'allowed_origins' => [
        env('FRONTEND_URL', 'http://localhost:5173'),
        'http://localhost:5173',
        'http://localhost:3000',
    ],
    'allowed_origins_patterns' => [],
    'allowed_headers' => ['*'],
    'exposed_headers' => [],
    'max_age' => 86400,

    // ✅ FALSE — pakai Bearer token, bukan cookie/session
    'supports_credentials' => false,
];
