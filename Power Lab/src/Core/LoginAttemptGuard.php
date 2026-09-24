<?php

namespace App\Core;

use DateTimeImmutable;
use PDO;

class LoginAttemptGuard
{
    private PDO $db;

    public function __construct()
    {
        $this->db = DataBase::getInstance();
    }

    public function isBlocked(string $identifier): bool
    {
        $stmt = $this->db->prepare(
            'SELECT blocked_until FROM tb_rate_limit WHERE rate_key = :key'
        );

        $stmt->execute([
            'key' => $this->keyFor($identifier)
        ]);

        $row = $stmt->fetch();

        if ($row === false || $row['blocked_until'] === null) {
            return false;
        }

        return new DateTimeImmutable($row['blocked_until']) > new DateTimeImmutable();
    }

    /**
     * Chama toda vez que uma tentativa de login FALHAR (senha errada) pra esse
     * identificador (email). Ao chegar em $maxAttempts, bloqueia por $lockoutSeconds.
     */
    public function registerFailure(
        string $identifier,
        int $maxAttempts = 10,
        int $lockoutSeconds = 900
    ): void {
        $key = $this->keyFor($identifier);
        $now = new DateTimeImmutable();

        $stmt = $this->db->prepare(
            'SELECT attempts FROM tb_rate_limit WHERE rate_key = :key'
        );

        $stmt->execute([
            'key' => $key
        ]);

        $row = $stmt->fetch();

        $attempts = $row === false
            ? 1
            : ((int) $row['attempts']) + 1;

        $blockedUntil = $attempts >= $maxAttempts
            ? $now->modify("+{$lockoutSeconds} seconds")->format('Y-m-d H:i:s')
            : null;

        if ($row === false) {
            $stmt = $this->db->prepare(
                'INSERT INTO tb_rate_limit
                (rate_key, attempts, window_start, blocked_until)
                VALUES (:key, :attempts, :now, :blocked)'
            );

            $stmt->execute([
                'key' => $key,
                'attempts' => $attempts,
                'now' => $now->format('Y-m-d H:i:s'),
                'blocked' => $blockedUntil,
            ]);
        } else {
            $stmt = $this->db->prepare(
                'UPDATE tb_rate_limit
                SET attempts = :attempts, blocked_until = :blocked
                WHERE rate_key = :key'
            );

            $stmt->execute([
                'key' => $key,
                'attempts' => $attempts,
                'blocked' => $blockedUntil,
            ]);
        }
    }

    /**
     * Chama num login BEM-SUCEDIDO, pra zerar o contador -- só tentativa errada conta.
     */
    public function clearAttempts(string $identifier): void
    {
        $stmt = $this->db->prepare(
            'DELETE FROM tb_rate_limit WHERE rate_key = :key'
        );

        $stmt->execute([
            'key' => $this->keyFor($identifier)
        ]);
    }

    private function keyFor(string $identifier): string
    {
        return "login:{$identifier}";
    }
}