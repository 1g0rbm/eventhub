<?php

declare(strict_types=1);

$files = array_merge(
    glob(__DIR__ . '/common/*.php') ?: [],
    glob(__DIR__ . '/' . (getenv('APP_ENV') ?: 'prod') . '/*.php') ?: []
);

$configs = array_map(
    static fn(string $file) => require_once $file,
    $files
);

return array_merge_recursive(...$configs);
