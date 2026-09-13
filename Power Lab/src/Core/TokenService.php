<?php

namespace App\Core;

use RuntimeException;

class TokenService
{
    private static function getSecret(): string
    {
        $secret = $_ENV['APP_SECRET'] ?? null;

        if ($secret === null || $secret === '') {
            throw new RuntimeException('APP_SECRET não está definido no .env');
        }

        return $secret;
    }

    public static function generate(int $userId, int $ttlSeconds = 3600): string
    {
        $payload = json_encode(['userId' => $userId, 'exp' => time() + $ttlSeconds]);
        $payloadEncoded = base64_encode($payload);
        $signature = hash_hmac('sha256', $payloadEncoded, self::getSecret());

        return "{$payloadEncoded}.{$signature}";
    }

    /**
     * Devolve o userId se o token for válido e não expirado, ou null caso contrário.
     */
    public static function validate(string $token): ?int
    {
        $parts = explode('.', $token);

        if (count($parts) !== 2) {
            return null;
        }

        [$payloadEncoded, $signature] = $parts;

        $expectedSignature = hash_hmac('sha256', $payloadEncoded, self::getSecret());

        // hash_equals evita timing attack -- nunca compara assinatura com === direto.
        if (!hash_equals($expectedSignature, $signature)) {
            return null;
        }

        $payload = json_decode(base64_decode($payloadEncoded), true);

        if (!is_array($payload) || !isset($payload['userId'], $payload['exp'])) {
            return null;
        }

        if ($payload['exp'] < time()) {
            return null; // token expirado
        }

        return (int) $payload['userId'];
    }
}