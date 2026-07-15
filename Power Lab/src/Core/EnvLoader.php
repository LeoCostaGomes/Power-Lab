<?php
namespace App\Database;

class EnvLoader
{
    public static function load(string $path): void
    {
        if (!file_exists($path)) {
            return; // Se o arquivo não existir, apenas ignora
        }

        // Lê o arquivo linha por linha
        $lines = file($path, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
        
        foreach ($lines as $line) {
            // Ignora linhas que são comentários
            if (strpos(trim($line), '#') === 0) {
                continue;
            }

            // Divide a linha no primeiro caractere "="
            if (strpos($line, '=') !== false) {
                list($key, $value) = explode('=', $line, 2);
                
                $key = trim($key);
                $value = trim($value);

                // Remove aspas se o valor estiver envolvido por elas
                $value = trim($value, '"\'');

                // Injeta no ambiente global do PHP
                putenv("{$key}={$value}");
                $_ENV[$key] = $value;
            }
        }
    }
}