<?php

require_once __DIR__ . '/../../autoloader.php';

use App\Repositories\UserRepository;
use App\DTOs\UserDTO;

$repository = new UserRepository();

$mensagem = '';
$usuarioEncontrado = null;
$usuarioLogado = null;

try {

    // =========================
    // LOGIN
    // =========================

    if ($_SERVER['REQUEST_METHOD'] === 'POST' && $_POST['acao'] === 'login') {

        $emailLogin = $_POST['email'];
        $senhaLogin = $_POST['password'];

        $usuarios = $repository->findAll();

        foreach ($usuarios as $usuario) {

            if (
                $usuario->compareEmail(
                    new \App\Models\Email(email: $emailLogin)
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

        } else {

            $mensagem = 'Email ou senha incorretos.';
        }
    }


    // =========================
    // CRIAR
    // =========================

    if ($_SERVER['REQUEST_METHOD'] === 'POST' && $_POST['acao'] === 'criar') {

        $data = new UserDTO(
            name: $_POST['name'],
            email: $_POST['email'],
            password: $_POST['password'],
            ip: $_POST['ip']
        );

        if ($repository->create($data)) {
            $mensagem = 'Usuário criado com sucesso!';
        } else {
            $mensagem = 'Erro ao criar usuário.';
        }
    }


    // =========================
    // BUSCAR
    // =========================

    if ($_SERVER['REQUEST_METHOD'] === 'POST' && $_POST['acao'] === 'buscar') {

        $id = (int) $_POST['id'];

        $usuarioEncontrado = $repository->findById($id);

        if ($usuarioEncontrado !== null) {
            $mensagem = 'Usuário encontrado!';
        } else {
            $mensagem = 'Usuário não encontrado.';
        }
    }


    // =========================
    // ATUALIZAR
    // =========================

    if ($_SERVER['REQUEST_METHOD'] === 'POST' && $_POST['acao'] === 'atualizar') {

        $id = (int) $_POST['id'];

        $data = new UserDTO(
            name: $_POST['name'],
            email: $_POST['email'],
            password: $_POST['password'],
            ip: $_POST['ip']
        );

        if ($repository->update($id, $data)) {
            $mensagem = 'Usuário atualizado com sucesso!';
        } else {
            $mensagem = 'Erro ao atualizar usuário.';
        }
    }


    // =========================
    // EXCLUIR
    // =========================

    if ($_SERVER['REQUEST_METHOD'] === 'POST' && $_POST['acao'] === 'excluir') {

        $id = (int) $_POST['id'];

        $repository->deleteFromDatabase($id);

        $mensagem = 'Usuário excluído com sucesso!';
    }


} catch (Throwable $e) {

    $mensagem = 'ERRO: ' . $e->getMessage();
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
                value="login"
            >

            <input
                type="email"
                name="email"
                placeholder="Email"
                required
            >

            <input
                type="password"
                name="password"
                placeholder="Senha"
                required
            >

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


    <!-- =========================
         CRIAR
    ========================== -->

    <div class="card">

        <h2>Criar usuário</h2>

        <form method="POST">

            <input
                type="hidden"
                name="acao"
                value="criar"
            >

            <input
                type="text"
                name="name"
                placeholder="Nome"
                required
            >

            <input
                type="email"
                name="email"
                placeholder="Email"
                required
            >

            <input
                type="password"
                name="password"
                placeholder="Senha"
                required
            >

            <input
                type="text"
                name="ip"
                placeholder="IP"
                value="127.0.0.1"
                required
            >

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
                value="buscar"
            >

            <input
                type="number"
                name="id"
                placeholder="ID"
                required
            >

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
                value="atualizar"
            >

            <input
                type="number"
                name="id"
                placeholder="ID"
                required
            >

            <input
                type="text"
                name="name"
                placeholder="Novo nome"
                required
            >

            <input
                type="email"
                name="email"
                placeholder="Novo email"
                required
            >

            <input
                type="password"
                name="password"
                placeholder="Nova senha"
                required
            >

            <input
                type="text"
                name="ip"
                placeholder="Novo IP"
                required
            >

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
                value="excluir"
            >

            <input
                type="number"
                name="id"
                placeholder="ID"
                required
            >

            <button type="submit">
                Excluir usuário
            </button>

        </form>

    </div>

</div>

</body>

</html>