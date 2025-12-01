<?php

namespace toubilib\core\domain\entities;

use DateTime;

class Indisponibilite
{
    public function __construct(
        private string $id,
        private string $praticienId,
        private DateTime $dateDebut,
        private DateTime $dateFin,
        private ?string $raison = null,
        private ?DateTime $dateCreation = null
    ) {}

    public function getId(): string
    {
        return $this->id;
    }

    public function getPraticienId(): string
    {
        return $this->praticienId;
    }

    public function getDateDebut(): DateTime
    {
        return $this->dateDebut;
    }

    public function getDateFin(): DateTime
    {
        return $this->dateFin;
    }

    public function getRaison(): ?string
    {
        return $this->raison;
    }

    public function getDateCreation(): ?DateTime
    {
        return $this->dateCreation;
    }

    /**
     * Vérifie si une période chevauche avec cette indisponibilité
     */
    public function chevaucheAvec(DateTime $debut, DateTime $fin): bool
    {
        return $this->dateDebut < $fin && $this->dateFin > $debut;
    }
}

