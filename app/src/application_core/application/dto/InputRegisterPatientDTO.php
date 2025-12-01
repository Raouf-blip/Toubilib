<?php
namespace toubilib\core\application\dto;

class InputRegisterPatientDTO
{
    public string $email;
    public string $mdp;
    public string $nom;
    public string $prenom;
    public string $telephone;
    public ?string $dateNaissance;
    public ?string $adresse;
    public ?string $codePostal;
    public ?string $ville;

    public function __construct(
        string $email,
        string $mdp,
        string $nom,
        string $prenom,
        string $telephone,
        ?string $dateNaissance = null,
        ?string $adresse = null,
        ?string $codePostal = null,
        ?string $ville = null
    ) {
        $this->email = $email;
        $this->mdp = $mdp;
        $this->nom = $nom;
        $this->prenom = $prenom;
        $this->telephone = $telephone;
        $this->dateNaissance = $dateNaissance;
        $this->adresse = $adresse;
        $this->codePostal = $codePostal;
        $this->ville = $ville;
    }
}

