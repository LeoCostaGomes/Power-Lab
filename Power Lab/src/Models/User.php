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
        }

        public function getName(): string
        {
            return $this->name;
        }

        public function setName(string $name)
        {
            if ($name === "")  return;
            $this->name = $name;
        }

        public function comparePassword(string $passwordToCompare): bool
        {
            return password_verify($passwordToCompare, $this->password);
        }
    }
?>