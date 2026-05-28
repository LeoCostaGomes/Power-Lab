<?php
    require __DIR__ . '/../autoload.php';

    // Mocks rápidos para o funcionamento correto das interfaces se necessário
    if (!interface_exists('App\Interfaces\ItemType')) {
        header("Content-Type: text/plain; charset=utf-8");
        die("Erro: A interface 'App\Interfaces\ItemType' não foi encontrada. Certifique-se de que o autoload está configurado ou crie a interface correspondente.");
    }

    use App\Models\Image;
    use App\Models\Territory;
    use App\Models\Skin;
    use App\Models\Particle;
    use App\Models\Paddle;
    use App\Models\Ultimate;

    // Novas classes de tipo de item
    use App\Models\PongCoinsItemType;
    use App\Models\ParticleItemType;
    use App\Models\PaddleItemType;
    use App\Models\UltimateItemType;
    use App\Models\SkinItemType;

    // =========================================================================
    // 1. INSTÂNCIAS DE SUPORTE (Dependências básicas de Território e Imagem)
    // =========================================================================
    $territorioEsmeralda = new Territory("Território de Esmeralda");
    $territorioVulcanico = new Territory("Território Vulcânico");

    $redPng = base64_decode("iVBORw0KGgoAAAANSUhEUgAAAAEAAAABCAYAAAAfFcSJAAAADUlEQVR42mP8z8BQDwAEhQGAhKmMIQAAAABJRU5ErkJggg==");
    $bluePng = base64_decode("iVBORw0KGgoAAAANSUhEUgAAAAEAAAABCAYAAAAfFcSJAAAADUlEQVR42mNkYPj/HwADEwDN4M9ZOAAAAABJRU5ErkJggg==");
    $gifAnimadoReal = base64_decode("R0lGODlhEAAQAPQAAP///wAAAPj4+Pq6uu7u7s7OzszMzMvLy8bGxsLCwsHBwb6+vrq6ujozMzczMzExMTAvLy8uLi4tLgAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAACH/C05FVFNDQVBFMi4wAwEAAAAh/hpDcmVhdGVkIHdpdGggVGhlIEdJTVAsIEdJRlMAMgAh+QQFMgAAACwAAAAAEAAQAAAFNyAgjmSJZmgalqZqui7AnvAsg7NID7e75xcMDmco9BCOgIDwAwySgIDgAwwSgIDgAwzSgIDgAwS6I0EAIfkEBTIAAAAsAAAAABAADwAABTYgII5kiWZoGpaXariuwC7Cs8wOhLsnGPwOCw6HwBACCBKBgOADDJKAgOADDNKAgOADBJIDQRAAIfkEBTIAAAAsAAAAABAADwAABTYgII5kiWZoGpaXariuwC7Cs8wOhLsnGPwOCw6HwBACCBKBgOADDJKAgOADDNKAgOADBJIDQRAAIfkEBTIAAAAsAAAAABAADwAABTYgII5kiWZoGpaXariuwC7Cs8wOhLsnGPwOCw6HwBACCBKBgOADDJKAgOADDNKAgOADBJIDQRAAIfkEBTIAAAAsAAAAABAADwAABTYgII5kiWZoGpaXariuwC7Cs8wOhLsnGPwOCw6HwBACCBKBgOADDJKAgOADDNKAgOADBJIDQRAAOf");

    $spriteIcone = new Image($redPng, "image/png", "Ícone Ultimate Vermelho");
    $spriteParticula = new Image($bluePng, "image/png", "Sprite da Partícula Azul");
    $gifParticula = new Image($gifAnimadoReal, "image/gif", "GIF da Partícula Animado");

    // =========================================================================
    // 2. CRIAÇÃO DOS MODELOS BASE
    // =========================================================================
    $skinBasica = new Skin("Skin Cyberpunk 2077");
    $particulaFogo = new Particle("Efeito de Chamas", $spriteParticula, $gifParticula);
    
    $descricoesPaddle = ["Estágio Inicial: Flutuação.", "Estágio Intermediário: Velocidade.", "Estágio Final: Propulsão."];
    $paddleLendaria = new Paddle("Paddle Suprema", $descricoesPaddle, $territorioEsmeralda);
    
    $ultimateDestruicao = new Ultimate("Supernova Blaster", "Dispara um feixe de energia.", $spriteIcone, $territorioVulcanico);

    // =========================================================================
    // 3. CRIAÇÃO DOS REWARD ITEM TYPES (As novas classes a serem testadas)
    // =========================================================================
    $itemPongCoins = new PongCoinsItemType();
    $itemParticle  = new ParticleItemType($particulaFogo);
    $itemPaddle    = new PaddleItemType($paddleLendaria);
    $itemUltimate  = new UltimateItemType($ultimateDestruicao);
    $itemSkin      = new SkinItemType($skinBasica);

    // Agrupando em uma lista para facilitar a exibição em loop no HTML
    $listaDeRecompensas = [
        "Pong Coins" => $itemPongCoins,
        "Partícula"  => $itemParticle,
        "Raquete"    => $itemPaddle,
        "Ultimate"   => $itemUltimate,
        "Skin"       => $itemSkin
    ];
?>

<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <title>Teste das Classes de Recompensa (ItemType)</title>
    <style>
        body { font-family: sans-serif; background: #202124; color: #e8eaed; padding: 20px; }
        .container { max-width: 900px; margin: 0 auto; }
        .section { background: #2d2f31; border-radius: 8px; padding: 15px; margin-bottom: 20px; box-shadow: 0 4px 6px rgba(0,0,0,0.1); }
        h2 { color: #8ab4f8; border-bottom: 1px solid #3c4043; padding-bottom: 5px; margin-top: 0; }
        
        /* Grid para alinhar os ItemTypes lado a lado */
        .rewards-grid { display: grid; grid-template-columns: repeat(auto-fill, minmax(250px, 1fr)); gap: 20px; margin-top: 15px; }
        .reward-card { background: #3c4043; border: 1px solid #5f6368; border-radius: 6px; padding: 15px; text-align: center; display: flex; flex-direction: column; align-items: center; justify-content: space-between; }
        .reward-card h3 { margin: 0 0 10px 0; font-size: 14px; color: #f28b82; text-transform: uppercase; letter-spacing: 0.5px; }
        .reward-card p { margin: 10px 0 0 0; font-weight: bold; font-size: 16px; color: #fff; }
        
        .img-container img { 
            width: 64px; 
            height: 64px; 
            border: 1px solid #5f6368; 
            display: block; 
            margin: 0 auto; 
            background: #1a1a1a;
            image-rendering: pixelated;
            image-rendering: crisp-edges;
        }
    </style>
</head>
<body>

    <div class="container">
        <h1>Painel de Teste: Tipos de Itens de Recompensa</h1>
        <p style="color: #bdc1c6;">Este teste valida o comportamento das classes que implementam a interface <code>ItemType</code>.</p>

        <div class="section">
            <h2>===== IMPLEMENTAÇÕES DE ITEMTYPE =====</h2>
            
            <div class="rewards-grid">
                <?php foreach ($listaDeRecompensas as $categoria => $item): ?>
                    <div class="reward-card">
                        <h3><?= $categoria ?></h3>
                        
                        <div class="img-container">
                            <img src="<?= $item->getRewardSprite()->getBase64Src() ?>" alt="Sprite da Recompensa">
                        </div>

                        <p><?= $item->getRewardText() ?></p>
                    </div>
                <?php endforeach; ?>
            </div>
        </div>

        <div class="section">
            <h2>===== VERIFICAÇÃO DE INTEGRIDADE DOS MODELOS =====</h2>
            <p><strong>Nome do Modelo Skin:</strong> <?= $skinBasica->getName() ?></p>
            <p><strong>Nome da Paddle Base:</strong> <?= $paddleLendaria->getName() ?> (<?= $paddleLendaria->getNameTerritory() ?>)</p>
            <p><strong>Nome da Ultimate Base:</strong> <?= $ultimateDestruicao->getName() ?> (<?= $ultimateDestruicao->getNameTerritory() ?>)</p>
        </div>
    </div>

</body>
</html>