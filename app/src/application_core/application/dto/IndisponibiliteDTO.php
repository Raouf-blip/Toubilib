<?php
namespace toubilib\core\application\dto;

use toubilib\core\domain\entities\Indisponibilite;

class IndisponibiliteDTO
{
    public string $id;
    public string $praticienId;
    public string $dateDebut;
    public string $dateFin;
    public ?string $raison;
    public ?string $dateCreation;

    public function __construct(Indisponibilite $indisponibilite)
    {
        $this->id = $indisponibilite->getId();
        $this->praticienId = $indisponibilite->getPraticienId();
        $this->dateDebut = $indisponibilite->getDateDebut()->format('Y-m-d H:i:s');
        $this->dateFin = $indisponibilite->getDateFin()->format('Y-m-d H:i:s');
        $this->raison = $indisponibilite->getRaison();
        $this->dateCreation = $indisponibilite->getDateCreation()?->format('Y-m-d H:i:s');
    }
}

