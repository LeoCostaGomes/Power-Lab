<?php
namespace App\Database;

use PDO;
use PDOException;

class DataBase
{
    private static ?PDO $instance = null;

    public static function getInstance(): PDO
    {
        if (self::$instance === null) {
            try {
                // Puxa os dados direto do $_ENV preenchido pelo EnvLoader
                $host    = $_ENV['DB_HOST'] ?? 'localhost';
                $dbname  = $_ENV['DB_NAME'] ?? '';
                $user    = $_ENV['DB_USER'] ?? 'root';
                $pass    = $_ENV['DB_PASS'] ?? '';
                $charset = $_ENV['DB_CHARSET'] ?? 'utf8mb4';

                $dsn = "mysql:host={$host};dbname={$dbname};charset={$charset}";

                self::$instance = new PDO($dsn, $user, $pass, [
                    PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
                    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC
                ]);
                
            } catch (PDOException $e) {
                // Em produção, evite dar die() com a mensagem crua para não vazar caminhos do servidor
                die("Erro de Conexão com o Banco de Dados.");
            }
        }
        return self::$instance;
    }
}