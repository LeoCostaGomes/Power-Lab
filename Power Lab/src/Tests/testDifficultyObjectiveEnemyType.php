<?php

namespace App\Tests;

require_once __DIR__ . '/../../autoloader.php';

use App\Repositories\DifficultyRepository;
use App\Repositories\ObjectiveRepository;
use App\Repositories\EnemyTypeRepository;
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

    /*
     * ==========================================================
     * DIFFICULTY
     * ==========================================================
     */

    echo "\n========================================\n";
    echo "== TESTANDO DIFFICULTY ==\n";
    echo "========================================\n";

    $difficultyRepository = new DifficultyRepository();

    $difficulties = $difficultyRepository->findAll();

    echo "Difficulties: " . count($difficulties) . " carregadas\n";

    if ($difficulties !== null && count($difficulties) > 0) {

        foreach ($difficulties as $difficulty) {

            echo "Difficulty #{$difficulty->getId()}: {$difficulty->getName()}\n";
        }

    } else {
        echo "Nenhuma Difficulty carregada — confira se a tabela possui linhas.\n";
    }


    /*
     * ==========================================================
     * OBJECTIVE
     * ==========================================================
     */

    echo "\n========================================\n";
    echo "== TESTANDO OBJECTIVE ==\n";
    echo "========================================\n";

    $objectiveRepository = new ObjectiveRepository();

    $objectives = $objectiveRepository->findAll();

    echo "Objectives: " . count($objectives) . " carregados\n";

    if ($objectives !== null && count($objectives) > 0) {

        foreach ($objectives as $objective) {

            echo "Objective #{$objective->getId()}: {$objective->getName()}\n";
            echo "  Descrição: {$objective->getDescription()}\n";
        }

    } else {
        echo "Nenhum Objective carregado — confira se a tabela possui linhas.\n";
    }


    /*
     * ==========================================================
     * ENEMY TYPE
     * ==========================================================
     */

    echo "\n========================================\n";
    echo "== TESTANDO ENEMY TYPE ==\n";
    echo "========================================\n";

    $enemyTypeRepository = new EnemyTypeRepository();

    $enemyTypes = $enemyTypeRepository->findAll();

    echo "EnemyTypes: " . count($enemyTypes) . " carregados\n";

    if ($enemyTypes !== null && count($enemyTypes) > 0) {

        foreach ($enemyTypes as $enemyType) {

            echo "EnemyType #{$enemyType->getId()}: {$enemyType->getName()}\n";
        }

    } else {
        echo "Nenhum EnemyType carregado — confira se a tabela possui linhas.\n";
    }


    /*
     * ==========================================================
     * FINAL
     * ==========================================================
     */

    echo "\n========================================\n";
    echo "== TUDO CERTO ==\n";
    echo "========================================\n";

} catch (\Throwable $e) {

    echo "\n========================================\n";
    echo "== FALHOU ==\n";
    echo "========================================\n";

    echo get_class($e) . ": " . $e->getMessage() . "\n";

    echo "\nArquivo: " . $e->getFile() . "\n";
    echo "Linha: " . $e->getLine() . "\n";

    echo "</pre>";
    exit;
}

echo "</pre>";
?>