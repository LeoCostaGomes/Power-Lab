<<<<<<< Updated upstream
=======
<?php
    require __DIR__ . '/../autoload.php';

    use App\Models\User;
    use App\Models\Email;
    use App\Models\IP;
    use App\Models\Image;
    use App\Models\ItemPoll;
    use App\Models\Poll;

// ==========================
// IMAGE
// ==========================

$image = new Image(
    "espada.png",
    "imagem_teste",
    "image/png"
);

// ==========================
// USER TEMPORÁRIO
// ==========================

$email = new Email("teste@email.com");

$ip = new IP("127.0.0.1");

$tempUser = new User(
    "Carlos",
    "123",
    $email,
    $ip,
    null
);

// ==========================
// ITEM POLL
// ==========================

$itemPoll = new ItemPoll(
    "Espada",
    "Espada lendária",
    $image,
    true,
    $tempUser
);

// ==========================
// USER PRINCIPAL
// ==========================

$user = new User(
    "João",
    "123456",
    $email,
    $ip,
    $itemPoll
);

// ==========================
// POLL
// ==========================

$poll = new Poll(
    new DateTime(),
    new DateTime("+1 day"),
    []
);

$poll->addItemInThisPoll($itemPoll);

// ==========================
// TESTES
// ==========================

echo "===== USER =====\n";

echo "Nome: " . $user->getName() . "\n";

if ($user->comparePassword("123456")) {
    echo "Senha correta\n";
}

echo "\n";

echo "===== IMAGE =====\n";

echo "Nome imagem: " . $image->getName() . "\n";
echo "MimeType: " . $image->getMimeType() . "\n";
echo "Base64: " . $image->getBase64Src() . "\n";

echo "\n";

echo "===== ITEM POLL =====\n";

echo "Nome item: " . $itemPoll->getName() . "\n";
echo "Descrição: " . $itemPoll->getDescription() . "\n";
echo "Criado por: " . $itemPoll->getNameUserGaveIdeia() . "\n";

echo "\n";

echo "===== POLL =====\n";

echo "Quantidade de itens: "
    . count($poll->getItemPoll()) . "\n";

foreach ($poll->getItemPoll() as $item)
{
    echo "Item: " . $item->getName() . "\n";
}
?>
>>>>>>> Stashed changes
