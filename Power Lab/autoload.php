<?php

spl_autoload_register(function ($class) {
    $prefix = 'App\\'; // teu namespace base
    $base_dir = __DIR__ . '/src/'; // onde estão as classes

    // verifica se a classe começa com o prefixo
    $len = strlen($prefix);
    if (strncmp($prefix, $class, $len) !== 0) {
        return;
    }

    // remove o prefixo
    $relative_class = substr($class, $len);

    // transforma namespace em caminho de arquivo
    $file = $base_dir . str_replace('\\', '/', $relative_class) . '.php';

    // se o arquivo existir, carrega
    if (file_exists($file)) {
        require $file;
    }
});