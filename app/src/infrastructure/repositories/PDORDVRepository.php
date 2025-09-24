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

    public function findBusySlots(int $praticienId, DateTime $debut, DateTime $fin): array
    {
        // On compare uniquement la date (ignore l'heure)
        $sql = "SELECT date_heure_debut, date_heure_fin, duree
                FROM rdv
                WHERE praticien_id = :pid
                  AND date(date_heure_debut) BETWEEN :debut AND :fin";

        $stmt = $this->pdo->prepare($sql);
        $stmt->execute([
            'pid' => $praticienId,
            'debut' => $debut->format('Y-m-d'),
            'fin' => $fin->format('Y-m-d'),
        ]);

        return $stmt->fetchAll(\PDO::FETCH_ASSOC);
    }

}
