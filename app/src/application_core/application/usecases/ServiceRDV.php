<?php
namespace toubilib\core\application\usecases;

use toubilib\core\application\ports\RDVRepositoryInterface;
use DateTime;

class ServiceRDV
{
    private RDVRepositoryInterface $rdvRepository;

    public function __construct(RDVRepositoryInterface $rdvRepository)
    {
        $this->rdvRepository = $rdvRepository;
    }

    public function listerCreneauxOccupes(int $praticienId, DateTime $debut, DateTime $fin): array
    {
        return $this->rdvRepository->findBusySlots($praticienId, $debut, $fin);
    }
}
