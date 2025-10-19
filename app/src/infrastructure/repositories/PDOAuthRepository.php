<?php

namespace toubilib\infra\repositories;

use PDO;
use toubilib\core\application\ports\AuthRepositoryInterface;
use toubilib\core\domain\entities\Auth;

class PDOAuthRepository implements AuthRepositoryInterface
{
    private PDO $pdo;

    public function __construct(PDO $pdo)
    {
        $this->pdo = $pdo;
    }

    public function findByEmail(string $authEmail): ?Auth
    {
        $stmt = $this->pdo->prepare("SELECT * FROM users WHERE email = :email");
        $stmt->execute(['email' => $authEmail]);
        $row = $stmt->fetch(PDO::FETCH_ASSOC);
        if (!$row) {
            return null;
        }
        return new Auth(
            $row['id'],
            $row['email'],
            $row['password'],
            $row['role']
        );
    }
}
