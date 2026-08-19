<?php

namespace App\Tests;

require_once __DIR__ . '/../../autoloader.php';

use App\Repositories\GameVersionRepository;
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

    $gameVersionRepository = new GameVersionRepository();

    $gameVersions = $gameVersionRepository->findAll();

    echo "GameVersions: " . count($gameVersions) . " carregados\n";

    echo "\n== Checando GameVersions ==\n";

    if ($gameVersions !== null && count($gameVersions) > 0) {

        foreach ($gameVersions as $gameVersion) {

            echo "GameVersion #{$gameVersion->getId()}: {$gameVersion->getVersionCode()}\n";
            echo "  Changelog: {$gameVersion->getChangelog()}\n";
            echo "\n";
        }

    } else {
        echo "Nenhum GameVersion carregado — confira se a tabela possui linhas.\n";
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