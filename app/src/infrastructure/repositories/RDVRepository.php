<?php

namespace toubilib\core\infrastructure\repositories;

use toubilib\core\domain\repositories\RDVRepositoryInterface;
use toubilib\core\domain\entities\RDV;
use PDO;

class RDVRepository implements RDVRepositoryInterface
{
    public function __construct(private PDO $pdo) {}

    public function findByPraticienAndPeriode(string $praticienId, \DateTime $dateDebut, \DateTime $dateFin): array
    {
        $stmt = $this->pdo->prepare("
            SELECT * FROM rdv
            WHERE praticien_id = :praticienId
              AND date_heure_debut >= :dateDebut
              AND date_heure_debut <= :dateFin
            ORDER BY date_heure_debut ASC
        ");

        $stmt->execute([
            'praticienId' => $praticienId,
            'dateDebut' => $dateDebut->format('Y-m-d H:i:s'),
            'dateFin' => $dateFin->format('Y-m-d H:i:s')
        ]);

        $rows = $stmt->fetchAll(PDO::FETCH_ASSOC);

        $rdvs = [];
        foreach ($rows as $row) {
            $rdvs[] = new RDV(
                $row['id'],
                $row['praticien_id'],
                $row['patient_id'],
                $row['patient_email'],
                new \DateTime($row['date_heure_debut']),
                isset($row['date_heure_fin']) ? new \DateTime($row['date_heure_fin']) : null,
                (int)$row['status'],
                (int)$row['duree'],
                isset($row['date_creation']) ? new \DateTime($row['date_creation']) : null,
                $row['motif_visite'] ?? null
            );
        }

        return $rdvs;
    }
}