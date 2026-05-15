<?php
    require __DIR__ . '/../autoload.php';

    use App\Models\Image;
    use App\Models\BoxType;
    use App\Models\RewardBox;
    use App\Models\RewardBoxWithVariableQuantity;
    use App\Models\BoxItemType;

    // ==========================
    // IMAGENS (Simuladas)
    // ==========================
    $iconComum = new Image("comum.png", "Ícone Comum", "image/png");
    $iconRaro = new Image("raro.png", "Ícone Raro", "image/png");

    // ==========================
    // BOX TYPE 1: "BAÚ DE BRONZE" (A recompensa final)
    // ==========================
    $bronzeBoxType = new BoxType(
        "Baú de Bronze",
        $iconComum,
        [] // Vazio para simplificar o teste de nível 1
    );

    $bronzeRewardItem = new BoxItemType($bronzeBoxType);

    // ==========================
    // BOX TYPE 2: "BAÚ DE PRATA" (Contém o Baú de Bronze)
    // ==========================
    $prataBoxType = new BoxType(
        "Baú de Prata",
        $iconRaro,
        [
            // 100% de chance de vir o baú de bronze
            new RewardBox($bronzeRewardItem, 100)
        ]
    );

    $prataRewardItem = new BoxItemType($prataBoxType);

    // ==========================
    // BOX TYPE 3: "BAÚ DE OURO" (Contém os outros baús)
    // ==========================
    $ouroBoxType = new BoxType(
        "Baú de Ouro",
        new Image("ouro.png", "Ícone Ouro", "image/png"),
        [
            // 80% de chance de vir o baú de Prata (Normal)
            new RewardBox($prataRewardItem, 80),
            
            // 20% de chance de vir entre 2 a 5 baús de Bronze (Variável)
            new RewardBoxWithVariableQuantity(2, 5, $bronzeRewardItem, 20)
        ]
    );

    // ==========================
    // TESTES DE RELAÇÃO
    // ==========================

    echo "===== INFO DA CAIXA PRINCIPAL =====\n";
    echo "Nome: " . $ouroBoxType->getName() . "\n";
    echo "Ícone: " . $ouroBoxType->getBoxIcon()->getName() . "\n";

    echo "\n";

    echo "===== PROBABILIDADES (CÁLCULO) =====\n";
    $chances = $ouroBoxType->getRealChanceOfEachItem();

    foreach ($chances as $chance) {
        echo "Item: " . $chance['itemType']->getRewardText() . " | ";
        echo "Chance: " . $chance['realChance'] . "%\n";
    }

    echo "\n";

    echo "===== RELAÇÕES DE HERANÇA E QUANTIDADE =====\n";
    foreach ($ouroBoxType->getRewardBoxes() as $reward) {
        echo "Recompensa encontrada: " . $reward->getItemType()->getRewardText() . "\n";
        
        if ($reward instanceof RewardBoxWithVariableQuantity) {
            echo "-> Tipo: Quantidade Variável\n";
            echo "-> Range: " . $reward->getMinQuantity() . " até " . $reward->getMaxQuantity() . "\n";
        } else {
            echo "-> Tipo: Recompensa Simples\n";
        }

        // Teste da interface ItemType via BoxReward
        if ($reward->getItemType() instanceof BoxReward) {
            echo "-> Verificação: É um BoxReward válido.\n";
        }
        
        echo "--------------------------\n";
    }
?>