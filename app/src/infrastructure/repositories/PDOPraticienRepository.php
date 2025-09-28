<?php

namespace toubilib\infra\repositories;

use toubilib\core\domain\entities\praticien\Praticien;
use toubilib\core\domain\entities\praticien\Specialite;
use toubilib\core\application\ports\PraticienRepositoryInterface;

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

    public function findById(string $id): ?Praticien
        {
            $sql = "SELECT p.id, p.nom, p.prenom, p.ville, p.email, s.id as specialite_id, s.libelle as specialite_libelle
                    FROM praticien p
                    JOIN specialite s ON p.specialite_id = s.id
                    WHERE p.id = :id";
            $stmt = $this->pdo->prepare($sql);
            $stmt->execute(['id' => $id]);
            $row = $stmt->fetch(\PDO::FETCH_ASSOC);

            if ($row === false) {
                return null;
            }
            
            $specialite = new Specialite($row['specialite_id'], $row['specialite_libelle']);
            $praticien = new Praticien(
                $row['id'],
                $row['nom'],
                $row['prenom'],
                $row['ville'],
                $row['email'],
                $specialite
            );
            return $praticien;
        }

    public function getMotifsVisite(string $praticienId): array
    {
        $sql = "SELECT mv.libelle
                FROM motif_visite mv
                JOIN praticien2motif pm ON mv.id = pm.motif_id
                WHERE pm.praticien_id = :pid";
        
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute(['pid' => $praticienId]);
        $rows = $stmt->fetchAll(\PDO::FETCH_ASSOC);

        $motifs = [];
        foreach ($rows as $row) {
            $motifs[] = $row['libelle'];
        }

        return $motifs;
    }
}