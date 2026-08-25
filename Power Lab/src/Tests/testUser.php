<?php

require_once __DIR__ . '/../../autoloader.php';

use App\Repositories\UserRepository;
use App\DTOs\UserDTO;

$mensagem = '';
$tipoMensagem = 'sucesso';

$usuarioEncontrado = null;
$usuarioLogado = null;


/**
 * Obtém o IP público do usuário.
 */
function getPublicIP(): string
{
    $ip = file_get_contents('https://api.ipify.org');

    if ($ip === false || !filter_var($ip, FILTER_VALIDATE_IP)) {
        throw new Exception('Não foi possível obter o IP público.');
    }

    return $ip;
}


try {

    $repository = new UserRepository();


    // =========================
    // LOGIN
    // =========================

    if ($_SERVER['REQUEST_METHOD'] === 'POST' && $_POST['acao'] === 'login') {

        try {

            $emailLogin = $_POST['email'];
            $senhaLogin = $_POST['password'];

            $usuarios = $repository->findAll();

            foreach ($usuarios as $usuario) {

                if (
                    $usuario->compareEmail(
                        $emailLogin
                    )
                    &&
                    $usuario->comparePassword($senhaLogin)
                ) {
                    $usuarioLogado = $usuario;
                    break;
                }
            }

            if ($usuarioLogado !== null) {

                $mensagem = 'Login realizado com sucesso!';
                $tipoMensagem = 'sucesso';
            } else {

                $mensagem = 'Email ou senha incorretos.';
                $tipoMensagem = 'erro';
            }
        } catch (Throwable $e) {

            $mensagem = 'Não foi possível realizar o login: ' . $e->getMessage();
            $tipoMensagem = 'erro';
        }
    }


    // =========================
    // CRIAR
    // =========================

    if ($_SERVER['REQUEST_METHOD'] === 'POST' && $_POST['acao'] === 'criar') {

        try {

            $ip = getPublicIP();

            $data = new UserDTO(
                name: $_POST['name'],
                email: $_POST['email'],
                password: $_POST['password'],
                ip: $ip
            );

            if ($repository->create($data)) {
                $mensagem = 'Usuário criado com sucesso!';
                $tipoMensagem = 'sucesso';
            } else {
                $mensagem = 'Erro ao criar usuário.';
                $tipoMensagem = 'erro';
            }
        } catch (Exception $e) {

            $mensagem = $e->getMessage();
            $tipoMensagem = 'erro';
        }
    }


    // =========================
    // BUSCAR
    // =========================

    if ($_SERVER['REQUEST_METHOD'] === 'POST' && $_POST['acao'] === 'buscar') {

        try {

            $id = (int) $_POST['id'];

            $usuarioEncontrado = $repository->findById($id);

            if ($usuarioEncontrado !== null) {

                $mensagem = 'Usuário encontrado!';
                $tipoMensagem = 'sucesso';
            } else {

                $mensagem = 'Usuário não encontrado.';
                $tipoMensagem = 'erro';
            }
        } catch (Throwable $e) {

            $mensagem = 'Erro ao buscar usuário: ' . $e->getMessage();
            $tipoMensagem = 'erro';
        }
    }


    // =========================
    // ATUALIZAR
    // =========================

    if ($_SERVER['REQUEST_METHOD'] === 'POST' && $_POST['acao'] === 'atualizar') {

        try {

            $id = (int) $_POST['id'];

            $ip = getPublicIP();

            $data = new UserDTO(
                name: $_POST['name'],
                email: $_POST['email'],
                password: $_POST['password'],
                ip: $ip
            );

            if ($repository->update($id, $data)) {

                $mensagem = 'Usuário atualizado com sucesso!';
                $tipoMensagem = 'sucesso';
            } else {

                $mensagem = 'Erro ao atualizar usuário.';
                $tipoMensagem = 'erro';
            }
        } catch (Throwable $e) {

            $erro = strtolower($e->getMessage());

            if (
                str_contains($erro, 'email') ||
                str_contains($erro, 'e-mail')
            ) {

                $mensagem = 'Este email já está cadastrado.';
            } elseif (str_contains($erro, 'ip')) {

                $mensagem = 'Este IP já está cadastrado.';
            } else {

                $mensagem = 'Erro ao atualizar usuário: ' . $e->getMessage();
            }

            $tipoMensagem = 'erro';
        }
    }


    // =========================
    // EXCLUIR
    // =========================

    if ($_SERVER['REQUEST_METHOD'] === 'POST' && $_POST['acao'] === 'excluir') {

        try {

            $id = (int) $_POST['id'];

            $repository->deleteFromDatabase($id);

            $mensagem = 'Usuário excluído com sucesso!';
            $tipoMensagem = 'sucesso';
        } catch (Throwable $e) {

            $mensagem = 'Erro ao excluir usuário: ' . $e->getMessage();
            $tipoMensagem = 'erro';
        }
    }
} catch (Throwable $e) {

    $mensagem = 'Não foi possível carregar o UserRepository: ' . $e->getMessage();
    $tipoMensagem = 'erro';
}

?>

<!DOCTYPE html>

<html lang="pt-BR">

<head>

    <meta charset="UTF-8">

    <title>Teste UserRepository</title>

    <style>
        body {
            font-family: Arial, sans-serif;
            background: #eeeeee;
            margin: 0;
            padding: 30px;
        }

        .container {
            width: 600px;
            max-width: 100%;
            margin: auto;
        }

        .card {
            background: white;
            padding: 20px;
            margin-bottom: 20px;
            border-radius: 10px;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
        }

        h1 {
            text-align: center;
        }

        h2 {
            margin-top: 0;
        }

        input {
            width: 100%;
            box-sizing: border-box;
            padding: 10px;
            margin-bottom: 10px;
        }

        button {
            padding: 10px 15px;
            cursor: pointer;
        }

        .mensagem {
            background: #dff0d8;
            border: 1px solid #a3d69c;
            padding: 15px;
            margin-bottom: 20px;
            border-radius: 5px;
        }

        .resultado {
            margin-top: 15px;
            background: #f5f5f5;
            padding: 15px;
            border-radius: 5px;
        }

        .login-sucesso {
            background: #d1ecf1;
            border: 1px solid #86cfda;
            padding: 15px;
            margin-top: 15px;
            border-radius: 5px;
        }

        .mensagem {
            padding: 15px;
            margin-bottom: 20px;
            border-radius: 5px;
        }

        .mensagem.sucesso {
            background: #dff0d8;
            border: 1px solid #a3d69c;
            color: #285b2a;
        }

        .mensagem.erro {
            background: #f8d7da;
            border: 1px solid #dc3545;
            color: #842029;
        }
    </style>

</head>

<body>

    <div class="container">

        <h1>Teste do UserRepository</h1>

        <?php if ($mensagem !== ''): ?>

            <div class="mensagem">
                <?= htmlspecialchars($mensagem) ?>
            </div>

        <?php endif; ?>


        <!-- =========================
         LOGIN
    ========================== -->

        <div class="card">

            <h2>Login</h2>

            <form method="POST">

                <input
                    type="hidden"
                    name="acao"
                    value="login">

                <input
                    type="email"
                    name="email"
                    placeholder="Email"
                    required>

                <input
                    type="password"
                    name="password"
                    placeholder="Senha"
                    required>

                <button type="submit">
                    Entrar
                </button>

            </form>


            <?php if ($usuarioLogado !== null): ?>

                <div class="login-sucesso">

                    <strong>Usuário autenticado!</strong>

                    <br><br>

                    ID:
                    <?= htmlspecialchars((string) $usuarioLogado->getId()) ?>

                    <br>

                    Nome:
                    <?= htmlspecialchars($usuarioLogado->getName()) ?>

                </div>

            <?php endif; ?>

        </div>


        <div class="card">

            <h2>Criar usuário</h2>

            <form method="POST">

                <input
                    type="hidden"
                    name="acao"
                    value="criar">

                <input
                    type="text"
                    name="name"
                    placeholder="Nome"
                    required>

                <input
                    type="email"
                    name="email"
                    placeholder="Email"
                    required>

                <input
                    type="password"
                    name="password"
                    placeholder="Senha"
                    required>

                <button type="submit">
                    Criar usuário
                </button>

            </form>

        </div>


        <!-- =========================
         BUSCAR
    ========================== -->

        <div class="card">

            <h2>Buscar usuário por ID</h2>

            <form method="POST">

                <input
                    type="hidden"
                    name="acao"
                    value="buscar">

                <input
                    type="number"
                    name="id"
                    placeholder="ID"
                    required>

                <button type="submit">
                    Buscar usuário
                </button>

            </form>


            <?php if ($usuarioEncontrado !== null): ?>

                <div class="resultado">

                    <strong>ID:</strong>
                    <?= htmlspecialchars((string) $usuarioEncontrado->getId()) ?>

                    <br><br>

                    <strong>Nome:</strong>
                    <?= htmlspecialchars($usuarioEncontrado->getName()) ?>

                    <br><br>

                    <strong>Usuário votou:</strong>
                    <?= $usuarioEncontrado->userVotedInAnyItemPoll() ? 'SIM' : 'NÃO' ?>

                </div>

            <?php endif; ?>

        </div>


        <!-- =========================
         ATUALIZAR
    ========================== -->

        <div class="card">

            <h2>Atualizar usuário</h2>

            <form method="POST">

                <input
                    type="hidden"
                    name="acao"
                    value="atualizar">

                <input
                    type="number"
                    name="id"
                    placeholder="ID"
                    required>

                <input
                    type="text"
                    name="name"
                    placeholder="Novo nome"
                    required>

                <input
                    type="email"
                    name="email"
                    placeholder="Novo email"
                    required>

                <input
                    type="password"
                    name="password"
                    placeholder="Nova senha"
                    required>

                <button type="submit">
                    Atualizar usuário
                </button>

            </form>

        </div>


        <!-- =========================
         EXCLUIR
    ========================== -->

        <div class="card">

            <h2>Excluir usuário</h2>

            <form method="POST">

                <input
                    type="hidden"
                    name="acao"
                    value="excluir">

                <input
                    type="number"
                    name="id"
                    placeholder="ID"
                    required>

                <button type="submit">
                    Excluir usuário
                </button>

            </form>

        </div>

    </div>

    <?php if ($mensagem !== ''): ?>

        <div class="mensagem <?= $tipoMensagem ?>">

            <?= htmlspecialchars($mensagem) ?>

        </div>

    <?php endif; ?>

</body>

</html>