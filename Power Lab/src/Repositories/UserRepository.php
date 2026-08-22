<?php

namespace App\Repositories;

use App\Core\DataBase;
use App\DTOs\UserDTO;
use App\Models\Email;
use App\Models\IP;
use App\Models\ItemPoll;
use App\Models\User;
use Exception;
use Override;
use PDO;

class UserRepository extends AbstractWritableRepository
{
    private PDO $db;

    public function __construct()
    {
        $this->db = DataBase::getInstance();
        parent::__construct();
    }

    protected function load(): void
    {
        $stmt = $this->db->query('SELECT * FROM tb_user');

        foreach ($stmt->fetchAll() as $row) {
            $this->addUser((int) $row['id_user'], $row['name'], $row['email'], $row['password'], $row['ip']);
        }
    }

    private function addUser(int $id, string $name, string $email, string $password, string $ip)
    {
        $user = $this->instantiateUser($id, $name, $email, $password, $ip);

        $this->items[$user->getId()] = $user;
    }

    private function instantiateUser(int $id, string $name, string $email, string $password, string $ip): User
    {
        $email = new Email(
            email: $email
        );

        $ip = new IP(
            IP: $ip
        );

        return $user = new User(
            id: $id,
            name: $name,
            email: $email,
            password: $password,
            ip: $ip,
            pollVotedItem: null, //Se fizer a enquete tem que mudar essa lógica
        );
    }

    public function findById(int $id): ?User
    {
        return parent::findById($id);
    }

    /**
     * @return User[]
     */
    public function findAll(): array
    {
        return parent::findAll();
    }

    /**
     * @param UserDTO $data
     */
    public function create(object $data): bool
    {
        $stmt = $this->db->prepare(
            'INSERT INTO tb_user (name, email, password, ip)
         VALUES (:name, :email, :password, :ip)'
        );

        $stmt->bindValue(':name', $data->name);
        $stmt->bindValue(':email', $data->email);
        $hashedPassword = password_hash($data->password, PASSWORD_DEFAULT);
        $stmt->bindValue(':password', $hashedPassword);
        $stmt->bindValue(':ip', $data->ip);

        if (!$stmt->execute()) {
            return false;
        }

        $id = (int) $this->db->lastInsertId();

        $this->addUser($id, $data->name, $data->email, $data->password, $data->ip);

        if (!isset($this->items[$id])) {
            return false;
        }

        return true;
    }


    /**
     * @param UserDTO $data
     */
    public function update(int $id, object $data): bool
    {
        $stmt = $this->db->prepare('UPDATE tb_user SET name = :name, email = :email, password = :password, ip = :ip WHERE id_user = :id');
        $stmt->bindValue(':name', $data->name);
        $stmt->bindValue(':email', $data->email);
        $hashedPassword = password_hash($data->password, PASSWORD_DEFAULT);
        $stmt->bindValue(':password', $hashedPassword);
        $stmt->bindValue(':ip', $data->ip);
        $stmt->bindValue(':id', $id, PDO::PARAM_INT);

        if (!$stmt->execute()) {
            return false;
        }

        $updatedUser = $this->instantiateUser(
            $id,
            $data->name,
            $data->email,
            $data->password,
            $data->ip
        );

        $this->items[$id] = $updatedUser;

        return true;
    }

    #[Override]
    public function deleteFromDatabase(int $id): void
    {
        $stmt = $this->db->prepare('DELETE FROM tb_user WHERE id_user = :id');
        $stmt->bindParam(':id', $id, PDO::PARAM_INT);

        if (!$stmt->execute()) {
            throw new Exception("Não foi possível excluir este usuário do banco de dados.");
        }
    }
}
