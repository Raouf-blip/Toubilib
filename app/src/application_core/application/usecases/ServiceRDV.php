<?php

namespace toubilib\core\application\usecases;

use toubilib\core\domain\repositories\RDVRepositoryInterface;
use toubilib\core\domain\entities\RDV;

class ServiceRDV
{
    public function __construct(private RDVRepositoryInterface $repository) {}

    
    // Liste des créneaux occupés d'un praticien dans une période donnée
    public function listerCreneauxOccupes(string $praticienId, \DateTime $dateDebut, \DateTime $dateFin): array
    {
        return $this->repository->findByPraticienAndPeriode($praticienId, $dateDebut, $dateFin);
    }
}
