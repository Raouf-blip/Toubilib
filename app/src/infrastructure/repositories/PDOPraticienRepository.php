<?php

namespace toubilib\infra\repositories;

use toubilib\core\domain\entities\praticien\Praticien;
use toubilib\core\domain\entities\praticien\Specialite;
use toubilib\core\application\PraticienRepositoryInterface;

class PDOPraticienRepository implements PraticienRepositoryInterface
{
    private \PDO $pdo;

    public function __construct(\PDO $pdo)
    {
        $this->pdo = $pdo;
    }

    public function findAll(): array
    {
        $sql = "SELECT p.id, p.nom, p.prenom, p.ville, p.email, s.id as specialite_id, s.libelle as specialite_libelle 
                FROM praticien p
                JOIN specialite s ON p.specialite_id = s.id";
        
        $stmt = $this->pdo->query($sql);
        $praticiens = [];
        while ($row = $stmt->fetch(\PDO::FETCH_ASSOC)) {
            $specialite = new Specialite($row['specialite_id'], $row['specialite_libelle']);
            $praticiens[] = new Praticien(
                $row['id'],
                $row['nom'],
                $row['prenom'],
                $row['ville'],
                $row['email'],
                $specialite
            );
        }
        return $praticiens;
    }
}