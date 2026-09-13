<?php
namespace App\Controllers;

use App\Core\JsonResponse;
use App\Core\Request;
use App\Repositories\UserRepository;
use App\DTOs\UserDTO;
use App\DTOs\UserUpdateDTO;
use App\Models\User;
use InvalidArgumentException;
use App\Core\TokenService;

class UserController
{
    public function __construct(private UserRepository $userRepository) {}

    public function getAll(Request $request, array $params): void
    {
        $users = $this->userRepository->findAll();
        JsonResponse::send(array_values(array_map([$this, 'formatUser'], $users)));
    }

    public function getById(Request $request, array $params): void
    {
        $id = (int) $params['id'];

        if (!$this->authenticateAs($request, $id)) {
            JsonResponse::send(['error' => 'Não autorizado'], 401);
            return;
        }

        $user = $this->userRepository->findById($id);

        if ($user === null) {
            JsonResponse::send(['error' => 'Usuário não encontrado'], 404);
            return;
        }

        JsonResponse::send($this->formatUser($user));
    }

    public function create(Request $request, array $params): void
    {
        $body = $request->getBody();

        if (empty($body['name']) || empty($body['email']) || empty($body['password'])) {
            JsonResponse::send(['error' => 'name, email e password são obrigatórios'], 400);
            return;
        }

        try {
            $dto = new UserDTO(
                name: $body['name'],
                email: $body['email'],
                password: $body['password'],
                ip: $request->getClientIp(),
            );

            if (!$this->userRepository->create($dto)) {
                JsonResponse::send(['error' => 'Não foi possível criar o usuário'], 500);
                return;
            }
        } catch (InvalidArgumentException $e) {
            JsonResponse::send(['error' => $e->getMessage()], 409);
            return;
        }

        $created = $this->findByEmail($body['email']);
        JsonResponse::send($created !== null ? $this->formatUser($created) : ['message' => 'Usuário criado'], 201);
    }

    public function update(Request $request, array $params): void
    {
        $id = (int) $params['id'];

        if (!$this->authenticateAs($request, $id)) {
            JsonResponse::send(['error' => 'Não autorizado'], 401);
            return;
        }

        $body = $request->getBody();

        if ($this->userRepository->findById($id) === null) {
            JsonResponse::send(['error' => 'Usuário não encontrado'], 404);
            return;
        }
 
        // Atualização parcial: qualquer campo ausente no corpo vira null no DTO,
        // e o Repository sabe ignorar campos null em vez de sobrescrever com vazio.
        try {
            $dto = new UserUpdateDTO(
                name: $body['name'] ?? null,
                email: $body['email'] ?? null,
                password: $body['password'] ?? null,
                ip: $body['ip'] ?? null,
            );
 
            if (!$this->userRepository->update($id, $dto)) {
                JsonResponse::send(['error' => 'Não foi possível atualizar o usuário'], 500);
                return;
            }
        } catch (InvalidArgumentException $e) {
            JsonResponse::send(['error' => $e->getMessage()], 409);
            return;
        }
 
        JsonResponse::send($this->formatUser($this->userRepository->findById($id)));
    }

    public function delete(Request $request, array $params): void
    {
        $id = (int) $params['id'];

        if (!$this->authenticateAs($request, $id)) {
            JsonResponse::send(['error' => 'Não autorizado'], 401);
            return;
        }

        if (!$this->userRepository->delete($id)) {
            JsonResponse::send(['error' => 'Usuário não encontrado'], 404);
            return;
        }

        JsonResponse::send(['message' => 'Usuário excluído com sucesso']);
    }

    public function login(Request $request, array $params): void
    {
        $body = $request->getBody();

        if (empty($body['email']) || empty($body['password'])) {
            JsonResponse::send(['error' => 'email e password são obrigatórios'], 400);
            return;
        }

        $user = $this->findByEmail($body['email']);

        if ($user === null || !$user->comparePassword($body['password'])) {
            JsonResponse::send(['error' => 'Email ou senha incorretos'], 401);
            return;
        }

        JsonResponse::send([
            'token' => TokenService::generate($user->getId()),
            'user' => $this->formatUser($user),
        ]);
    }

    /**
     * Confere se o token do cabeçalho Authorization pertence exatamente
     * ao usuário de $expectedUserId -- não só "algum token válido".
     */
    private function authenticateAs(Request $request, int $expectedUserId): bool
    {
        $token = $request->getBearerToken();

        if ($token === null) {
            return false;
        }

        $authenticatedUserId = TokenService::validate($token);

        return $authenticatedUserId !== null && $authenticatedUserId === $expectedUserId;
    }

    /**
     * Não existe um findByEmail() no Repository -- varre findAll() comparando.
     */
    private function findByEmail(string $email): ?User
    {
        foreach ($this->userRepository->findAll() as $user) {
            if ($user->compareEmail($email)) {
                return $user;
            }
        }

        return null;
    }

    private function formatUser(User $user): array
    {
        return [
            'id' => $user->getId(),
            'name' => $user->getName(),
            'email' => $user->getEmail(),
            // IP e senha propositalmente fora daqui -- não deveriam sair numa resposta de API.
        ];
    }
}