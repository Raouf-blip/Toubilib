<?php
namespace toubilib\core\application\dto;

class InputIndisponibiliteDTO
{
    public string $praticienId;
    public string $dateDebut;
    public string $dateFin;
    public ?string $raison;

    public function __construct(
        string $praticienId,
        string $dateDebut,
        string $dateFin,
        ?string $raison = null
    ) {
        $this->praticienId = $praticienId;
        $this->dateDebut = $dateDebut;
        $this->dateFin = $dateFin;
        $this->raison = $raison;
    }
}

