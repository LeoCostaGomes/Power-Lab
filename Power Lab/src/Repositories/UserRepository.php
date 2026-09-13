<?php

namespace App\Repositories;

use App\Core\DataBase;
use App\DTOs\UserDTO;
use App\DTOs\UserUpdateDTO;
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

    private function addUser(int $id, string $name, string $email, string $password, string $ip): void
    {
        $user = $this->instantiateUser($id, $name, $email, $password, $ip);

        $this->items[$user->getId()] = $user;
    }

    private function instantiateUser(int $id, string $name, string $email, string $password, string $ip): User
    {
        $emailObj = new Email(email: $email);
        $ipObj = new IP($ip);

        return new User(
            id: $id,
            name: $name,
            email: $emailObj,
            password: $password,
            ip: $ipObj,
            pollVotedItem: null, //Se fizer a enquete tem que mudar essa lógica
        );
    }

    /**
     * Recarrega um usuário direto do banco e atualiza o cache em memória.
     * Usado depois de qualquer INSERT/UPDATE, pra nunca precisar "adivinhar"
     * o valor de um campo que não foi alterado (a senha, por exemplo).
     */
    private function refreshUserFromDatabase(int $id): void
    {
        $stmt = $this->db->prepare('SELECT * FROM tb_user WHERE id_user = :id');
        $stmt->bindValue(':id', $id, PDO::PARAM_INT);
        $stmt->execute();
        $row = $stmt->fetch();

        if ($row !== false) {
            $this->addUser((int) $row['id_user'], $row['name'], $row['email'], $row['password'], $row['ip']);
        }
    }

    public function findById(int $id): ?User
    {
        return parent::findById($id);
    }

    public function emailExists(string $email): bool
    {
        foreach ($this->items as $user) {
            if ($user->compareEmail($email)) {
                return true;
            }
        }
        return false;
    }

    public function IPExists(string $ip): bool
    {
        foreach ($this->items as $user) {
            if ($user->compareIP($ip)) return true;
        }
        return false;
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
    public function create(mixed $data): bool
    {
        if ($this->emailExists($data->email)) {
            throw new InvalidArgumentException("Este email já foi usado!");
        }

        $stmt = $this->db->prepare(
            'INSERT INTO tb_user (name, email, password, ip)
             VALUES (:name, :email, :password, :ip)'
        );

        $stmt->bindValue(':name', $data->name);
        $stmt->bindValue(':email', $data->email);
        $stmt->bindValue(':password', password_hash($data->password, PASSWORD_DEFAULT));
        $stmt->bindValue(':ip', $data->ip);

        if (!$stmt->execute()) {
            return false;
        }

        $this->refreshUserFromDatabase((int) $this->db->lastInsertId());

        return true;
    }

    /**
     * Atualização PARCIAL: só os campos não-nulos do UserUpdateDTO entram no UPDATE.
     * Campos omitidos permanecem exatamente como estavam.
     *
     * @param UserUpdateDTO $data
     */
    public function update(int $id, mixed $data): bool
    {
        if (!isset($this->items[$id])) {
            return false;
        }

        if ($data->email !== null && $this->emailExists($data->email)) {
            throw new InvalidArgumentException("Este email já foi usado!");
        }

        $sets = [];
        $bindings = [];

        if ($data->name !== null) {
            $sets[] = 'name = :name';
            $bindings[':name'] = $data->name;
        }
        if ($data->email !== null) {
            $sets[] = 'email = :email';
            $bindings[':email'] = $data->email;
        }
        if ($data->password !== null) {
            $sets[] = 'password = :password';
            $bindings[':password'] = password_hash($data->password, PASSWORD_DEFAULT);
        }
        if ($data->ip !== null) {
            $sets[] = 'ip = :ip';
            $bindings[':ip'] = $data->ip;
        }

        if (empty($sets)) {
            return true; // nada pra atualizar -- não é erro, só não faz nada
        }

        $sql = 'UPDATE tb_user SET ' . implode(', ', $sets) . ' WHERE id_user = :id';
        $stmt = $this->db->prepare($sql);

        foreach ($bindings as $param => $value) {
            $stmt->bindValue($param, $value);
        }
        $stmt->bindValue(':id', $id, PDO::PARAM_INT);

        if (!$stmt->execute()) {
            return false;
        }

        $this->refreshUserFromDatabase($id);

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