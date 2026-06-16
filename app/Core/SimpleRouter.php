<?php

declare(strict_types=1);

namespace App\Core;

final class SimpleRouter
{
    /** @var array<string, array<string, callable>> */
    private array $routes = [
        'GET' => [],
        'POST' => [],
    ];

    public function get(string $path, callable $handler): void
    {
        $this->routes['GET'][$path] = $handler;
    }

    public function post(string $path, callable $handler): void
    {
        $this->routes['POST'][$path] = $handler;
    }

    /**
     * @return mixed
     */
    public function dispatch(string $method, string $uri)
    {
        $path = parse_url($uri, PHP_URL_PATH) ?? '/';

        // معالجة المجلدات الفرعية: حذف مسار المجلد الأساسي من الرابط
        $base = str_replace('\\', '/', dirname($_SERVER['SCRIPT_NAME'] ?? ''));
        if ($base !== '/' && str_starts_with($path, $base)) {
            $path = substr($path, strlen($base));
        }

        // معالجة حالة وجود index.php بشكل صريح في الرابط
        if (str_starts_with($path, '/index.php')) {
            $path = substr($path, 10); // حذف '/index.php'
        }

        $path = $path === '' ? '/' : $path;

        $handler = $this->routes[$method][$path] ?? null;
        if (!$handler) {
            http_response_code(404);
            header('Content-Type: application/json; charset=utf-8');
            return json_encode(['error' => 'Route not found', 'path' => $path], JSON_UNESCAPED_UNICODE);
        }

        return $handler();
    }
}
