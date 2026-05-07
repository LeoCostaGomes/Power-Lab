<?php
namespace App\Models;

use App\Models\IP;
use App\Models\Email;
class User
{
    public function __construct(
        private string $name,
        private string $password,
        private Email $email,
        private IP $ip,
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
        $passwordToCompare = password_hash($passwordToCompare, PASSWORD_DEFAULT);
        return password_verify($passwordToCompare, $this->password);
    }

    public function alterPassword(string $OldPassword, string $NewPassword)
    {
        if (!$this->comparePassword($OldPassword))
            throw new \InvalidArgumentException("old password is incorrect.");
        $this->password = $this->setPassword($NewPassword);
    }

    private function setPassword(string $password) : string
    {
        if ($password === "")
            throw new \InvalidArgumentException("Invalid password.");
        return password_hash($password, PASSWORD_DEFAULT);
    }

    
}
?>
