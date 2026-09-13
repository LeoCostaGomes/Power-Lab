<?php

namespace App\Core;

class Request
{
    private string $method;
    private string $path;
    private array $queryParams;
    private array $body;
    private string $clientIp;

    public function __construct()
    {
        $this->method = $_SERVER['REQUEST_METHOD'];

        $scriptDir = str_replace('\\', '/', dirname($_SERVER['SCRIPT_NAME']));
        $uri = urldecode(parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH));

        if ($scriptDir !== '/' && str_starts_with($uri, $scriptDir)) {
            $uri = substr($uri, strlen($scriptDir));
        }

        $this->path = '/' . trim($uri, '/');
        $this->queryParams = $_GET;
        $this->body = json_decode(file_get_contents('php://input'), true) ?? [];
        $this->clientIp = $_SERVER['REMOTE_ADDR'] ?? '0.0.0.0';
    }

    public function getMethod(): string
    {
        return $this->method;
    }

    public function getPath(): string
    {
        return $this->path;
    }

    public function getQueryParams(): array
    {
        return $this->queryParams;
    }

    public function getBody(): array
    {
        return $this->body;
    }

    public function getClientIp(): string
    {
        return $this->clientIp;
    }

    /**
     * Lê o header "Authorization: Bearer <token>" e devolve só o token,
     * ou null se não vier nenhum.
     */
    public function getBearerToken(): ?string
    {
        $header = $_SERVER['HTTP_AUTHORIZATION'] ?? null;

        // Em algumas configurações de Apache, o header Authorization não chega
        // em $_SERVER e só aparece via apache_request_headers().
        if ($header === null && function_exists('apache_request_headers')) {
            $headers = apache_request_headers();
            $header = $headers['Authorization'] ?? $headers['authorization'] ?? null;
        }

        if ($header === null || !str_starts_with($header, 'Bearer ')) {
            return null;
        }

        return substr($header, 7);
    }
}