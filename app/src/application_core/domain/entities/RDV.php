<?php

namespace toubilib\core\domain\entities;
use DateTime;
use Exception;

class RDV
{
    public function __construct(
        private string $id,
        private string $praticienId,
        private string $patientId,
        private ?string $patientEmail,
        private \DateTime $dateHeureDebut,
        private ?\DateTime $dateHeureFin,
        private int $status,
        private int $duree,
        private ?\DateTime $dateCreation,
        private ?string $motifVisite
    ) {}

    public function getId(): string { return $this->id; }
    public function getPraticienId(): string { return $this->praticienId; }
    public function getPatientId(): string { return $this->patientId; }
    public function getPatientEmail(): ?string { return $this->patientEmail; }
    public function getDateHeureDebut(): \DateTime { return $this->dateHeureDebut; }
    public function getDateHeureFin(): ?\DateTime { return $this->dateHeureFin; }
    public function getStatus(): int { return $this->status; }
    public function getDuree(): int { return $this->duree; }
    public function getDateCreation(): ?\DateTime { return $this->dateCreation; }
    public function getMotifVisite(): ?string { return $this->motifVisite; }

    public function annuler(): void
    {
        $now = new DateTime();

        if ($this->status === 1) {
            throw new Exception("Rendez-vous déjà annulé");
        }

        if ($this->dateHeureDebut < $now) {
            throw new Exception("Impossible d'annuler un rendez-vous passé");
        }

        $this->status = 1;
    }
}
