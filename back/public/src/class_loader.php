<?php
declare(strict_types=1);

spl_autoload_register(function (string $class): void {
    if (mb_strpos($class, '..')) return;

    $file = str_replace('\\', '/', $class) . '.php';

    if (file_exists(__DIR__ . '/' . $file)) {
        if (!include __DIR__ . '/' . $file) {
            throw new Exception('Class Not Found: ' . $class);
        }
    }
});
