<?php

namespace toubilib\core\domain\entities\praticien;

class Praticien
{
    public string $id;
    public string $nom;
    public string $prenom;
    public string $ville;
    public string $email;
    public Specialite $specialite;
    public string $telephone;
    public ?string $structureNom;
    public ?string $adresse;
    public ?string $codePostal;
    public ?string $structureVille;

    public function __construct(
        string $id, 
        string $nom, 
        string $prenom, 
        string $ville, 
        string $email, 
        Specialite $specialite,
        string $telephone,
        ?string $structureNom = null,
        ?string $adresse = null,
        ?string $codePostal = null,
        ?string $structureVille = null
    ) {
        $this->id = $id;
        $this->nom = $nom;
        $this->prenom = $prenom;
        $this->ville = $ville;
        $this->email = $email;
        $this->specialite = $specialite;
        $this->telephone = $telephone;
        $this->structureNom = $structureNom;
        $this->adresse = $adresse;
        $this->codePostal = $codePostal;
        $this->structureVille = $structureVille;
    }

    public function getId(): string
    {
        return $this->id;
    }

    public function getNom(): string
    {
        return $this->nom;
    }

    public function getPrenom(): string
    {
        return $this->prenom;
    }

    public function getVille(): string
    {
        return $this->ville;
    }

    public function getEmail(): string
    {
        return $this->email;
    }

    public function getSpecialite(): Specialite
    {
        return $this->specialite;
    }

    public function getTelephone(): string
    {
        return $this->telephone;
    }

    public function getStructureNom(): ?string
    {
        return $this->structureNom;
    }

    public function getAdresse(): ?string
    {
        return $this->adresse;
    }

    public function getCodePostal(): ?string
    {
        return $this->codePostal;
    }

    public function getStructureVille(): ?string
    {
        return $this->structureVille;
    }

}