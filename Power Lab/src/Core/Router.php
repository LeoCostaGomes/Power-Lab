<?php

namespace App\Core;

class Router
{
    private array $routes = [];
    private RateLimiter $rateLimiter;

    public function __construct()
    {
        $this->rateLimiter = new RateLimiter();
    }

    public function get(string $path, callable $handler): void
    {
        $this->addRoute('GET', $path, $handler);
    }

    public function post(string $path, callable $handler): void
    {
        $this->addRoute('POST', $path, $handler);
    }

    public function put(string $path, callable $handler): void
    {
        $this->addRoute('PUT', $path, $handler);
    }

    public function delete(string $path, callable $handler): void
    {
        $this->addRoute('DELETE', $path, $handler);
    }

    private function addRoute(string $method, string $path, callable $handler): void
    {
        $pattern = preg_replace('#\{([a-zA-Z_]+)\}#', '(?P<$1>[^/]+)', $path);

        $this->routes[] = [
            'method' => $method,
            'pattern' => '#^' . $pattern . '$#',
            'handler' => $handler,
        ];
    }

    public function dispatch(Request $request): void
    {
        // Limite global: 60 requisições por minuto, por IP, em qualquer rota.
        $rateLimitKey = 'global:' . $request->getClientIp();

        if (!$this->rateLimiter->attempt($rateLimitKey, maxAttempts: 60, windowSeconds: 60)) {
            http_response_code(429);
            header('Content-Type: application/json; charset=utf-8');
            echo json_encode(['error' => 'Muitas requisições. Tenta de novo em instantes.'], JSON_UNESCAPED_UNICODE);
            return;
        }

        foreach ($this->routes as $route) {
            if ($route['method'] !== $request->getMethod()) {
                continue;
            }

            if (preg_match($route['pattern'], $request->getPath(), $matches)) {
                $params = array_filter($matches, fn ($key) => is_string($key), ARRAY_FILTER_USE_KEY);
                call_user_func($route['handler'], $request, $params);
                return;
            }
        }

        http_response_code(404);
        header('Content-Type: application/json; charset=utf-8');
        echo json_encode(['error' => 'Rota não encontrada'], JSON_UNESCAPED_UNICODE);
    }
}