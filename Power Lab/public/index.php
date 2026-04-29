<?php
    require __DIR__ . '/../autoload.php';

    use App\Models\IP;

    try {
        $meuIp = new IP("192.168.0.1");
        echo "IP válido: " . $meuIp->getIP();
    } catch (\Exception $e) {
        echo "Erro: " . $e->getMessage();
    }
?>
