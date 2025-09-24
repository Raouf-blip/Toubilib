<?php
namespace toubilib\core\application\usecases;

use toubilib\core\application\ports\RDVRepositoryInterface;
use toubilib\core\application\dto\RDVDTO;
use DateTime;

class ServiceRDV
{
    private RDVRepositoryInterface $rdvRepository;

    public function __construct(RDVRepositoryInterface $rdvRepository)
    {
        $this->rdvRepository = $rdvRepository;
    }

    public function listerCreneauxOccupes(string $praticienId, DateTime $debut, DateTime $fin): array
    {
        $rdvs = $this->rdvRepository->findBusySlots($praticienId, $debut, $fin);
        
        $dtos = [];
        foreach ($rdvs as $rdv) {
            $dtos[] = new RDVDTO(
                $rdv->getId(),
                $rdv->getPraticienId(),
                $rdv->getPatientId(),
                $rdv->getPatientEmail(),
                $rdv->getDateHeureDebut()->format('Y-m-d H:i:s'),
                $rdv->getDateHeureFin()?->format('Y-m-d H:i:s'),
                $rdv->getStatus(),
                $rdv->getDuree(),
                $rdv->getDateCreation()?->format('Y-m-d H:i:s'),
                $rdv->getMotifVisite()
            );
        }

        return $dtos;
    }
}