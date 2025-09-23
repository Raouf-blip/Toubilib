<?php

namespace toubilib\core\domain\repositories;

use toubilib\core\domain\entities\RDV;

interface RDVRepositoryInterface
{
    // liste des rendez-vous d'un praticien dans une période donnée
    public function findByPraticienAndPeriode(string $praticienId, \DateTime $dateDebut, \DateTime $dateFin): array;
}
