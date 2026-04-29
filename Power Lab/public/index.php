<?php
    spl_autoload_register(function ($nomeDaClasse) {
    // Procura o arquivo com o nome da classe na mesma pasta
    $caminho = __DIR__ . '/' . $nomeDaClasse . '.php';
    
    if (file_exists($caminho)) {
        require_once $caminho;
    }
});


try {
    $meuIp = new IP("192.168.0.1");
    echo "IP válido: " . $meuIp->getIP();
} catch (\Exception $e) {
    echo "Erro: " . $e->getMessage();
}
?>
