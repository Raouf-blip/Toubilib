<?php

namespace toubilib\core\application\dto;

use toubilib\core\domain\entities\praticien\Praticien;

class PraticienDTO
{
    public string $nom;
    public string $prenom;
    public string $ville;
    public string $email;
    public string $specialite;

    public function __construct(Praticien $praticien)
    {
        $this->nom = $praticien->nom;
        $this->prenom = $praticien->prenom;
        $this->ville = $praticien->ville;
        $this->email = $praticien->email;
        $this->specialite = $praticien->specialite->libelle;
    }
}