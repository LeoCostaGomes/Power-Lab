<?php

namespace App\Tests;

require_once __DIR__ . '/../../autoloader.php';

use App\Repositories\GameModeRepository;
use App\Core\DataBase;
use PDO;

header('Content-Type: text/html; charset=utf-8');

echo "<pre>";

$pdo = DataBase::getInstance();

echo "Conexão aberta com sucesso.\n";
echo "Servidor: " . $pdo->getAttribute(PDO::ATTR_SERVER_VERSION) . "\n";

$stmt = $pdo->query('SELECT DATABASE() AS db_atual');
$row = $stmt->fetch();

echo "Banco conectado: {$row['db_atual']}\n";

try {
    echo "== Carregando repository ==\n";

    $gameModeRepository = new GameModeRepository();

    $gameModes = $gameModeRepository->findAll();

    echo "GameModes: " . count($gameModes) . " carregados\n";

    echo "\n== Checando GameModes ==\n";

    if ($gameModes !== null && count($gameModes) > 0) {

        foreach ($gameModes as $gameMode) {

            echo "GameMode #{$gameMode->getId()}: {$gameMode->getName()}\n";
            echo "  Descrição: {$gameMode->getDescription()}\n";
            echo "\n";
        }

    } else {
        echo "Nenhum GameMode carregado — confira se a tabela possui linhas.\n";
    }

    echo "\n== Tudo certo ==\n";

} catch (\Throwable $e) {

    echo "\n== FALHOU ==\n";
    echo get_class($e) . ": " . $e->getMessage() . "\n";

    echo "</pre>";
    exit;
}

echo "</pre>";
?>