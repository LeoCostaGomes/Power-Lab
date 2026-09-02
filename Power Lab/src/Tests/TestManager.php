<!DOCTYPE html>

<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

<title>Testes</title>

<style>
    body {
        margin: 0;
        padding: 40px;

        font-family: Arial, sans-serif;
        background-color: #f2f2f2;

        display: flex;
        flex-direction: column;
        align-items: center;
        gap: 15px;
    }

    .botao {
        width: 350px;
        padding: 15px;

        text-align: center;
        text-decoration: none;

        color: white;
        background-color: #333;

        border-radius: 8px;

        transition: 0.2s;
    }

    .botao:hover {
        background-color: #555;
        transform: scale(1.03);
    }

    .botao:active {
        transform: scale(0.98);
    }
</style>

</head>

<body>

<a href="../src/Tests/testUser.php" class="botao">
    Teste UserRepository
</a>

<a href="../src/Tests/testBox.php" class="botao">
    Teste BoxRepository
</a>

<a href="../src/Tests/testDifficultyObjectiveEnemyType.php" class="botao">
    Teste DifficultyObjectiveEnemyTypeRepository
</a>

<a href="../src/Tests/testGameMode.php" class="botao">
    Teste GameModeRepository
</a>

<a href="../src/Tests/testGameVersion.php" class="botao">
    Teste GameVersionRepository
</a>

<a href="../src/Tests/testItemCategory.php" class="botao">
    Teste ItemCategoryRepository
</a>

<a href="../src/Tests/testModifier.php" class="botao">
    Teste ModifierRepository
</a>

<a href="../src/Tests/testPaddleSkin.php" class="botao">
    Teste PaddleSkinRepository
</a>

<a href="../src/Tests/testParticle.php" class="botao">
    Teste ParticleRepository
</a>

<a href="../src/Tests/testStage.php" class="botao">
    Teste StageRepository
</a>

<a href="../src/Tests/testUltimate.php" class="botao">
    Teste UltimateRepository
</a>

</body>
</html>
