<?php

namespace App\Core;

class Request
{
    private string $method;
    private string $path;
    private array $queryParams;
    private array $body;

    public function __construct()
    {
        $this->method = $_SERVER['REQUEST_METHOD'];

        $uri = parse_url(
            $_SERVER['REQUEST_URI'],
            PHP_URL_PATH
        );

        $uri = urldecode($uri);

        // Remove o caminho até /public
        $publicPosition = strpos($uri, '/public');

        if ($publicPosition !== false) {
            $uri = substr($uri, $publicPosition + strlen('/public'));
        }

        $this->path = '/' . trim($uri, '/');

        $this->queryParams = $_GET;

        $this->body = json_decode(
            file_get_contents('php://input'),
            true
        ) ?? [];
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
}
