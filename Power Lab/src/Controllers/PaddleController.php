<?php
namespace App\Controllers;

use App\Core\JsonResponse;
use App\Core\Request;
use App\Repositories\PaddleRepository;

class PaddleController
{
    public function __construct(private PaddleRepository $paddleRepository) {}

    public function index(Request $request, array $params): void
{
    $paddles = $this->paddleRepository->findAll();
    JsonResponse::send(array_map(fn ($p) => ['id' => $p->getId(), 'name' => $p->getName()], $paddles));
}

    public function show(Request $request, array $params): void
    {
        header('Content-Type: application/json');
        $paddle = $this->paddleRepository->findById((int) $params['id']);

        if ($paddle === null) {
            http_response_code(404);
            JsonResponse::send(['error' => 'Paddle não encontrado']);
            return;
        }

        JsonResponse::send(['id' => $paddle->getId(), 'name' => $paddle->getName()]);
    }
}
?>