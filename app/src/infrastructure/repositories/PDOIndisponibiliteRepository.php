<?php
namespace toubilib\infra\repositories;

use toubilib\core\application\ports\IndisponibiliteRepositoryInterface;
use toubilib\core\domain\entities\Indisponibilite;
use DateTime;

class PDOIndisponibiliteRepository implements IndisponibiliteRepositoryInterface
{
    private \PDO $pdo;

    public function __construct(\PDO $pdo)
    {
        $this->pdo = $pdo;
    }

    public function save(Indisponibilite $indisponibilite): void
    {
        $stmt = $this->pdo->prepare(
            "INSERT INTO indisponibilite (id, praticien_id, date_debut, date_fin, raison, date_creation) 
             VALUES (:id, :praticien_id, :date_debut, :date_fin, :raison, :date_creation)"
        );
        $stmt->execute([
            'id' => $indisponibilite->getId(),
            'praticien_id' => $indisponibilite->getPraticienId(),
            'date_debut' => $indisponibilite->getDateDebut()->format('Y-m-d H:i:s'),
            'date_fin' => $indisponibilite->getDateFin()->format('Y-m-d H:i:s'),
            'raison' => $indisponibilite->getRaison(),
            'date_creation' => $indisponibilite->getDateCreation()?->format('Y-m-d H:i:s')
        ]);
    }

    public function findByPraticienId(string $praticienId): array
    {
        $stmt = $this->pdo->prepare(
            "SELECT * FROM indisponibilite 
             WHERE praticien_id = :praticien_id 
             ORDER BY date_debut DESC"
        );
        $stmt->execute(['praticien_id' => $praticienId]);
        $rows = $stmt->fetchAll(\PDO::FETCH_ASSOC);

        $indisponibilites = [];
        foreach ($rows as $row) {
            $indisponibilites[] = new Indisponibilite(
                $row['id'],
                $row['praticien_id'],
                new DateTime($row['date_debut']),
                new DateTime($row['date_fin']),
                $row['raison'] ?? null,
                isset($row['date_creation']) ? new DateTime($row['date_creation']) : null
            );
        }
        return $indisponibilites;
    }

    public function findByPraticienIdAndDateRange(string $praticienId, DateTime $debut, DateTime $fin): array
    {
        $stmt = $this->pdo->prepare(
            "SELECT * FROM indisponibilite 
             WHERE praticien_id = :praticien_id 
               AND date_debut < :fin 
               AND date_fin > :debut
             ORDER BY date_debut ASC"
        );
        $stmt->execute([
            'praticien_id' => $praticienId,
            'debut' => $debut->format('Y-m-d H:i:s'),
            'fin' => $fin->format('Y-m-d H:i:s')
        ]);
        $rows = $stmt->fetchAll(\PDO::FETCH_ASSOC);

        $indisponibilites = [];
        foreach ($rows as $row) {
            $indisponibilites[] = new Indisponibilite(
                $row['id'],
                $row['praticien_id'],
                new DateTime($row['date_debut']),
                new DateTime($row['date_fin']),
                $row['raison'] ?? null,
                isset($row['date_creation']) ? new DateTime($row['date_creation']) : null
            );
        }
        return $indisponibilites;
    }

    public function delete(string $indisponibiliteId): void
    {
        $stmt = $this->pdo->prepare("DELETE FROM indisponibilite WHERE id = :id");
        $stmt->execute(['id' => $indisponibiliteId]);
    }

    public function findById(string $id): ?Indisponibilite
    {
        $stmt = $this->pdo->prepare("SELECT * FROM indisponibilite WHERE id = :id");
        $stmt->execute(['id' => $id]);
        $row = $stmt->fetch(\PDO::FETCH_ASSOC);

        if (!$row) {
            return null;
        }

        return new Indisponibilite(
            $row['id'],
            $row['praticien_id'],
            new DateTime($row['date_debut']),
            new DateTime($row['date_fin']),
            $row['raison'] ?? null,
            isset($row['date_creation']) ? new DateTime($row['date_creation']) : null
        );
    }
}

