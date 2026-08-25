<?php

namespace App\Repositories;

use App\Core\DataBase;
use App\DTOs\UserDTO;
use App\Models\Email;
use App\Models\IP;
use App\Models\User;
use Exception;
use InvalidArgumentException;
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
        $newEmail = new Email(
            email: $email
        );

        $newIp = new IP(
            $ip
        );

        return $user = new User(
            id: $id,
            name: $name,
            email: $newEmail,
            password: $password,
            ip: $newIp,
            pollVotedItem: null, //Se fizer a enquete tem que mudar essa lógica
        );
    }

    public function findById(int $id): ?User
    {
        return parent::findById($id);
    }

    public function validateEmailAndIP(String $email, String $ip)
    {
        if ($this->emailExists($email)) throw new InvalidArgumentException("Este email já foi usado!");

        //Adicionar verificação se IP ja existe no banco caso seja necessário

        $this->validateEmail($email);

        $this->validateIP($ip);
    }

    public function emailExists(String $email): bool
    {
        foreach ($this->items as $user) {
            if ($user->compareEmail($email)) return true;
        }
        return false;
    }

    public function IPExists(String $ip): bool
    {
        foreach ($this->items as $user) {
            if ($user->compareIP($ip)) return true;
        }
        return false;
    }

    public function validateEmail(String $email)
    {
        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
                throw new InvalidArgumentException(
                    "Invalid email address: '{$email}'."
                );
            }
    }

    public function validateIP(String $ip)
    {
        if (!filter_var($ip, FILTER_VALIDATE_IP)) {
                throw new InvalidArgumentException("invalide address IP: '$ip'.");
            }
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
        $this->validateEmailAndIP($data->email, $data->ip);

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

        $this->addUser($id, $data->name, $data->email, $hashedPassword, $data->ip);

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
        $this->validateEmailAndIP($data->email, $data->ip);

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
            $hashedPassword,
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
