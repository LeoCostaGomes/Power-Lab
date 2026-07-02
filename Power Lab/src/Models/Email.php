<?php
    namespace App\Models;
    class Email
    {
        public function __construct(private int $id, private string $email)
        {
            if (!filter_var($this->email, FILTER_VALIDATE_EMAIL)) {
                throw new \InvalidArgumentException("invalide address IP: ' $this->email '.");
            }
        }

        public function getId(): int
        {
            return $this->id;
        }

        public function getEmail() : string
        {
            return $this->email;
        }

        public function compareEmail(Email $emailToCompare) : bool
        {
            return $this->getEmail() === $emailToCompare->getEmail();
        }
    }
?>