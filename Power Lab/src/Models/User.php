<?php
namespace App\Models;

use App\Models\IP;
use App\Models\Email;
use App\Models\ItemPoll;
class User
{
    public function __construct(
        private string $name,
        private string $password,
        private Email $email,
        private IP $ip,
        private ItemPoll | null $pollVotedItem
    ) {
        $this->password = $this->setPassword($password);
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function setName(string $name)
    {
        if ($name === "")
            throw new \InvalidArgumentException("Invalid name.");
        $this->name = $name;
    }

    public function comparePassword(string $passwordToCompare): bool
    {
        return password_verify($passwordToCompare, $this->password);
    }

    public function alterPassword(string $OldPassword, string $NewPassword)
    {
        if (!$this->comparePassword($OldPassword))
            throw new \InvalidArgumentException("old password is incorrect.");
        $this->password = $this->setPassword($NewPassword);
    }

    private function setPassword(string $password): string
    {
        if ($password === "")
            throw new \InvalidArgumentException("Invalid password.");
        return password_hash($password, PASSWORD_DEFAULT);
    }

    public function compareIP(string $IP): bool
    {
        return $this->comparePassword($IP);
    }

    public function compareEmail(Email $email): bool
    {
        return $this->email->compareEmail($email);
    }

    public function userVotedInAnyItemPoll(): bool
    {
        if ($this->pollVotedItem != null) {
            return true;
        }
        return false;
    }

    public function setPollVotedItem(ItemPoll|null $pollVotedItem)
    {
        $this->pollVotedItem = $pollVotedItem;
    }
}
?>