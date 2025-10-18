<?php
namespace toubilib\infra\repositories;

use PDO;
use toubilib\core\application\ports\AuthRepositoryInterface;
use toubilib\core\domain\entities\Auth;

class PDOAuthRepository implements AuthRepositoryInterface
{
    public function findById(string $authId): ?Auth
    {
        $stmt = $this->pdo->prepare("SELECT * FROM patient WHERE id = :id");
        $stmt->execute(['id' => $authId]);
        $row = $stmt->fetch(PDO::FETCH_ASSOC);
        if (!$row) {
            return null;
        }
        return new Auth(
            $row['id'],
            $row['email'],
            $row['password']
        );
    }
}