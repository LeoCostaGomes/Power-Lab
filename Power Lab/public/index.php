<?php
    require __DIR__ . '/../autoload.php';

    use App\Models\GameVersion;
    use App\Models\GameMode;

    // =========================================================================
    // 1. INSTÂNCIAS DE TESTE: VERSÕES DO JOGO (GameVersion)
    // =========================================================================
    $versaoEstavel = new GameVersion(
        "v1.2.0", 
        "Adicionado suporte a novas skins, correção de bugs na colisão da raquete e otimização de performance no renderizador de partículas."
    );

    $versaoBeta = new GameVersion(
        "v1.3.0-beta", 
        "Fase de testes para o novo sistema de Ultimates globais e balanceamento do Território Vulcânico."
    );

    $listaVersoes = [
        "Produção / Estável" => $versaoEstavel,
        "Ambiente Beta"      => $versaoBeta
    ];

    // =========================================================================
    // 2. INSTÂNCIAS DE TESTE: MODOS DE JOGO (GameMode)
    // =========================================================================
    $modoClassico = new GameMode(
        "Clássico 1v1", 
        "A experiência tradicional do Pong. Rebata a bola, use seus reflexos e vença ao marcar 11 pontos primeiro."
    );

    $modoCaos = new GameMode(
        "Caos Total", 
        "Modo com multiplicadores de velocidade, ativação aleatória de Ultimates e modificações de gravidade na bola em tempo real."
    );

    $modoTerritorios = new GameMode(
        "Disputa de Território", 
        "Cada rebatida perfeita domina uma parte da arena. O jogador com maior área controlada ao fim do tempo vence."
    );

    $listaModos = [
        "Casual"       => $modoClassico,
        "Competitivo"  => $modoTerritorios,
        "Especial"     => $modoCaos
    ];
?>

<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <title>Teste das Classes GameVersion e GameMode</title>
    <style>
        body { font-family: sans-serif; background: #202124; color: #e8eaed; padding: 20px; }
        .container { max-width: 950px; margin: 0 auto; }
        .section { background: #2d2f31; border-radius: 8px; padding: 15px; margin-bottom: 20px; box-shadow: 0 4px 6px rgba(0,0,0,0.1); }
        h2 { color: #8ab4f8; border-bottom: 1px solid #3c4043; padding-bottom: 5px; margin-top: 0; }
        
        /* Grid para o alinhamento dos cards */
        .meta-grid { display: grid; grid-template-columns: repeat(auto-fill, minmax(280px, 1fr)); gap: 20px; margin-top: 15px; }
        .meta-card { background: #3c4043; border: 1px solid #5f6368; border-radius: 6px; padding: 15px; display: flex; flex-direction: column; justify-content: space-between; }
        
        .meta-card h3 { margin: 0 0 10px 0; font-size: 13px; color: #f28b82; text-transform: uppercase; letter-spacing: 0.5px; }
        .meta-badge { background: #1a1a1a; color: #8ab4f8; font-family: monospace; font-size: 16px; padding: 8px; text-align: center; border-radius: 4px; border: 1px solid #5f6368; margin-bottom: 10px; font-weight: bold; }
        .meta-title { margin: 0 0 10px 0; font-size: 18px; color: #fff; font-weight: bold; text-align: center; }
        .meta-desc { font-size: 13px; color: #bdc1c6; line-height: 1.4; }
        
        /* Cores exclusivas para diferenciar os blocos */
        .mode-card-title { color: #81c995 !important; }
    </style>
</head>
<body>

    <div class="container">
        <h1>Painel de Teste: Metadados do Sistema do Jogo</h1>
        <p style="color: #bdc1c6;">Este teste valida o comportamento das classes de infraestrutura <code>GameVersion</code> e <code>GameMode</code>.</p>

        <div class="section">
            <h2>===== VERSÕES DO SISTEMA (GAMEVERSION) =====</h2>
            <div class="meta-grid">
                <?php foreach ($listaVersoes as $ambiente => $versao): ?>
                    <div class="meta-card">
                        <div>
                            <h3><?= $ambiente ?></h3>
                            <div class="meta-badge"><?= $versao->getVersionCode() ?></div>
                        </div>
                        <div>
                            <p style="margin: 5px 0; font-size: 11px; color: #8ab4f8; font-weight: bold;">CHANGELOG:</p>
                            <div class="meta-desc"><?= $versao->getChangelog() ?></div>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
        </div>

        <div class="section">
            <h2>===== MODOS DE JOGO DISPONÍVEIS (GAMEMODE) =====</h2>
            <div class="meta-grid">
                <?php foreach ($listaModos as $categoria => $modo): ?>
                    <div class="meta-card">
                        <div>
                            <h3>Fila: <?= $categoria ?></h3>
                            <div class="meta-title mode-card-title"><?= $modo->getName() ?></div>
                        </div>
                        <div>
                            <p style="margin: 5px 0; font-size: 11px; color: #81c995; font-weight: bold;">REGRAS / DESCRIÇÃO:</p>
                            <div class="meta-desc"><?= $modo->getDescription() ?></div>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
        </div>

        <div class="section">
            <h2>===== VERIFICAÇÃO DE INTEGRIDADE DOS MODELOS =====</h2>
            <p><strong>Método de Leitura GameVersion:</strong> Código de versão obtido via <code>getVersionCode()</code> com sucesso.</p>
            <p><strong>Método de Leitura GameMode:</strong> Nome do modo obtido via <code>getName()</code> com sucesso.</p>
        </div>
    </div>

</body>
</html>