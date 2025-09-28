<?php
namespace toubilib\core\application\usecases;

use toubilib\core\application\ports\RDVRepositoryInterface;
use toubilib\core\application\dto\InputRDVDTO;
use toubilib\core\domain\entities\RDV;
use toubilib\core\application\dto\RDVDTO;
use DateTime;
use Exception;
use Ramsey\Uuid\Uuid;

class ServiceRDV implements ServiceRDVInterface
{
    private RDVRepositoryInterface $rdvRepository;
    private ServicePraticienInterface $servicePraticien;
    private ServicePatient $servicePatient;

    public function __construct(
        RDVRepositoryInterface $rdvRepository,
        ServicePraticienInterface $servicePraticien,
        ServicePatient $servicePatient
    ) {
        $this->rdvRepository = $rdvRepository;
        $this->servicePraticien = $servicePraticien;
        $this->servicePatient = $servicePatient;
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

    public function consulterRdv(string $rdvId): ?RDVDTO
    {
        $rdv = $this->rdvRepository->findById($rdvId);

        if (!$rdv) {
            return null;
        }

        return new RDVDTO(
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

    public function creerRendezVous(InputRDVDTO $dto): RDV
    {
        $debut = new DateTime($dto->dateHeureDebut);
        $fin = (clone $debut)->modify("+{$dto->duree} minutes");

        if (!$this->servicePraticien->RecherchePraticienByID($dto->praticienId)) {
            throw new Exception("Praticien inexistant");
        }

        if (!$this->servicePatient->existePatient($dto->patientId)) {
            throw new Exception("Patient inexistant");
        }

        $motifsAutorises = $this->servicePraticien->getMotifsVisite($dto->praticienId);
        if (!in_array($dto->motifVisite, $motifsAutorises, true)) {
            throw new Exception("Motif de visite non autorisé pour ce praticien");
        }

        $jour = (int)$debut->format('N');
        $heure = (int)$debut->format('H');
        if ($jour > 5 || $heure < 8 || $heure >= 19) {
            throw new Exception("Créneau horaire invalide (lun-ven 08:00-19:00)");
        }

        $creneauxOccupes = $this->rdvRepository->findBusySlots($dto->praticienId, $debut, $fin);
        foreach ($creneauxOccupes as $existing) {
            if ($existing->getDateHeureDebut() < $fin && $existing->getDateHeureFin() > $debut) {
                throw new Exception("Praticien déjà occupé sur ce créneau");
            }
        }

        $rdv = new RDV(
            Uuid::uuid4()->toString(),
            $dto->praticienId,
            $dto->patientId,
            $dto->patientEmail ?? null,
            $debut,
            $fin,
            0,
            $dto->duree,
            new DateTime(),
            $dto->motifVisite
        );

        // persist
        $this->rdvRepository->save($rdv);

        return $rdv;
    }

    public function annulerRendezVous(string $rdvId): void
    {
        $rdv = $this->rdvRepository->findById($rdvId);
        if (!$rdv) {
            throw new Exception("RDV inexistant");
        }

        $rdv->annuler(); // méthode métier sur l'entité RDV, change le status 

        $this->rdvRepository->updateStatus($rdv->getId(), $rdv->getStatus());
    }


}
