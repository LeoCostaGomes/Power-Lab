<?php

// 1. REGISTRA O AUTOLOAD (Apenas mapeia as classes, sem ler o .env repetidamente)
spl_autoload_register(function ($class) {
    $prefix = 'App\\'; // Seu namespace base
    $base_dir = __DIR__ . '/src/'; // Onde estão as classes

    // Verifica se a classe começa com o prefixo
    $len = strlen($prefix);
    if (strncmp($prefix, $class, $len) !== 0) {
        return;
    }

    // Remove o prefixo
    $relative_class = substr($class, $len);

    // Transforma namespace em caminho de arquivo (ex: App\Models\User -> src/Models/User.php)
    $file = $base_dir . str_replace('\\', '/', $relative_class) . '.php';

    // Se o arquivo existir, carrega
    if (file_exists($file)) {
        require $file;
    }
});

// 2. CARREGA O .ENV (Uma única vez, agora que o autoload já sabe onde achar o EnvLoader)
\App\Database\EnvLoader::load(__DIR__ . '/../.env');