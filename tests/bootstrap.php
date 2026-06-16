<?php

declare(strict_types=1);

// PSR-4 autoloader for both App\ and Tests\ namespaces
spl_autoload_register(static function (string $class): void {
    $map = [
        'App\\'   => __DIR__ . '/../app/',
        'Tests\\' => __DIR__ . '/',
    ];
    foreach ($map as $prefix => $dir) {
        if (!str_starts_with($class, $prefix)) {
            continue;
        }
        $rel  = substr($class, strlen($prefix));
        $file = $dir . str_replace('\\', '/', $rel) . '.php';
        if (file_exists($file)) {
            require $file;
        }
        return;
    }
});
