<?php

declare(strict_types=1);

namespace App\Core;

final class Env
{
    private static bool $loaded = false;

    public static function load(string $path): void
    {
        if (self::$loaded || !file_exists($path)) {
            return;
        }

        foreach (file($path, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES) as $line) {
            $line = trim($line);
            if ($line === '' || str_starts_with($line, '#') || !str_contains($line, '=')) {
                continue;
            }

            [$key, $value] = explode('=', $line, 2);
            $key   = trim($key);
            $value = trim($value, " \t\n\r\0\x0B\"'");

            if ($key === '' || isset($_ENV[$key])) {
                continue;
            }

            putenv("{$key}={$value}");
            $_ENV[$key]    = $value;
            $_SERVER[$key] = $value;
        }

        self::$loaded = true;
    }

    public static function get(string $key, string $default = ''): string
    {
        $val = $_ENV[$key] ?? getenv($key);
        return ($val !== false && $val !== null && $val !== '') ? (string)$val : $default;
    }

    public static function reset(): void
    {
        self::$loaded = false;
    }
}
