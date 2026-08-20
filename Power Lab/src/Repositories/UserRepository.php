<?php

namespace App\Repositories;

use App\Core\DataBase;
use App\DTOs\UserDTO;
use App\Models\Email;
use App\Models\IP;
use App\Models\ItemPoll;
use App\Models\User;
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
            $this->addUser($row);
        }
    }

    private function addUser($row)
    {
        $email = new Email(
            email: $row['email']
        );

        $ip = new IP(
            IP: $row['ip']
        );

        $user = new User(
            id: (int) $row['id_user'],
            name: $row['name'],
            email: $email,
            password: $row['password'],
            ip: $ip,
            pollVotedItem: null, //Se fizer a enquete tem que mudar essa lógica
        );

        $this->items[$user->getId()] = $user;
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
        $stmt->bindValue(':password', $data->password);
        $stmt->bindValue(':ip', $data->ip);

        if (!$stmt->execute()) {
            return false;
        }

        $id = (int) $this->db->lastInsertId();

        $user = $this->findById($id);

        if ($user === null) {
            return false;
        }

        $this->items[$user->getId()] = $user;

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
        $stmt->bindValue(':password', $data->password);
        $stmt->bindValue(':ip', $data->ip);
        $stmt->bindValue(':id', $id, PDO::PARAM_INT);

        if (!$stmt->execute())
        {
            return false;
        }
    }
}

?>