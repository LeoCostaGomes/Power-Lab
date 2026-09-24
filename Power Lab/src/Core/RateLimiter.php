<?php

namespace App\Core;

use DateTimeImmutable;
use PDO;

class RateLimiter
{
    private PDO $db;

    public function __construct()
    {
        $this->db = DataBase::getInstance();
    }

    /**
     * Devolve true se a requisição pode passar, false se estourou o limite
     * dentro da janela atual. Reseta sozinho quando a janela expira.
     */
    public function attempt(string $key, int $maxAttempts, int $windowSeconds): bool
    {
        $now = new DateTimeImmutable();

        $stmt = $this->db->prepare('SELECT attempts, window_start FROM tb_rate_limit WHERE rate_key = :key');
        $stmt->execute(['key' => $key]);
        $row = $stmt->fetch();

        if ($row === false) {
            $stmt = $this->db->prepare(
                'INSERT INTO tb_rate_limit (rate_key, attempts, window_start) VALUES (:key, 1, :now)'
            );
            $stmt->execute(['key' => $key, 'now' => $now->format('Y-m-d H:i:s')]);
            return true;
        }

        $windowEnd = (new DateTimeImmutable($row['window_start']))->modify("+{$windowSeconds} seconds");

        if ($now > $windowEnd) {
            // janela expirou -- reseta a contagem em vez de acumular pra sempre
            $stmt = $this->db->prepare(
                'UPDATE tb_rate_limit SET attempts = 1, window_start = :now WHERE rate_key = :key'
            );
            $stmt->execute(['key' => $key, 'now' => $now->format('Y-m-d H:i:s')]);
            return true;
        }

        if ((int) $row['attempts'] >= $maxAttempts) {
            return false;
        }

        $stmt = $this->db->prepare('UPDATE tb_rate_limit SET attempts = attempts + 1 WHERE rate_key = :key');
        $stmt->execute(['key' => $key]);
        return true;
    }
}