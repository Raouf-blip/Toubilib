<?php
namespace toubilib\infra\repositories;

use toubilib\core\application\ports\RDVRepositoryInterface;
use DateTime;

class PDORDVRepository implements RDVRepositoryInterface
{
    private \PDO $pdo;

    public function __construct(\PDO $pdo)
    {
        $this->pdo = $pdo;
    }

    public function findBusySlots(string $praticienId, DateTime $debut, DateTime $fin): array
    {
        $sql = "SELECT id, praticien_id, patient_id, patient_email, date_heure_debut, 
                    date_heure_fin, status, duree, date_creation, motif_visite
                FROM rdv
                WHERE praticien_id = :pid
                AND DATE(date_heure_debut) BETWEEN :debut AND :fin";

        $stmt = $this->pdo->prepare($sql);
        $stmt->execute([
            'pid'   => $praticienId,
            'debut' => $debut->format('Y-m-d'),
            'fin'   => $fin->format('Y-m-d'),
        ]);

        $rows = $stmt->fetchAll(\PDO::FETCH_ASSOC);

        $rdvs = [];
        foreach ($rows as $row) {
            $rdvs[] = new \toubilib\core\domain\entities\RDV(
                $row['id'],
                $row['praticien_id'],
                $row['patient_id'],
                $row['patient_email'] ?? null,
                new DateTime($row['date_heure_debut']),
                isset($row['date_heure_fin']) ? new DateTime($row['date_heure_fin']) : null,
                (int)$row['status'],
                (int)$row['duree'],
                isset($row['date_creation']) ? new DateTime($row['date_creation']) : null,
                $row['motif_visite'] ?? null
            );
        }

        return $rdvs;
    }
}
