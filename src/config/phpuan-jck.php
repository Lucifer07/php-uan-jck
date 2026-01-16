<?php

return [
    'enabled' => env('PHPUAN_JCK_ENABLED', true),
    'debug' => env('PHPUAN_JCK_DEBUG', false),
    'slow_threshold_ms' => env('PHPUAN_JCK_SLOW_THRESHOLD_MS', 100),
    'memory_threshold_bytes' => env('PHPUAN_JCK_MEMORY_THRESHOLD_BYTES', 1048576),
    'ignore_namespaces' => [
        'Illuminate\\',
        'Composer\\',
        'Symfony\\',
        'Carbon\\',
        'Monolog\\',
    ],
    'ignore_paths' => [
        '/vendor/',
        '/storage/framework/',
    ],
    'cache' => [
        'enabled' => env('PHPUAN_JCK_CACHE_ENABLED', true),
        'ttl' => env('PHPUAN_JCK_CACHE_TTL', 3600),
    ],
    'cleanup' => [
        'enabled' => env('PHPUAN_JCK_CLEANUP_ENABLED', true),
        'retention_hours' => env('PHPUAN_JCK_RETENTION_HOURS', 24),
        'max_trace_size_mb' => env('PHPUAN_JCK_MAX_TRACE_SIZE_MB', 500),
    ],
    'telemetry_dir' => env('PHPUAN_JCK_TELEMETRY_DIR', 'telemetry'),
];
