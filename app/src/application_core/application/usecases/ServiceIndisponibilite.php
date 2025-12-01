<?php
namespace toubilib\core\application\usecases;

use toubilib\core\application\dto\InputIndisponibiliteDTO;
use toubilib\core\application\dto\IndisponibiliteDTO;
use toubilib\core\application\ports\IndisponibiliteRepositoryInterface;
use toubilib\core\domain\entities\Indisponibilite;
use toubilib\core\application\ports\PraticienRepositoryInterface;
use Ramsey\Uuid\Uuid;
use Exception;
use DateTime;

class ServiceIndisponibilite implements ServiceIndisponibiliteInterface
{
    private IndisponibiliteRepositoryInterface $indisponibiliteRepository;
    private PraticienRepositoryInterface $praticienRepository;

    public function __construct(
        IndisponibiliteRepositoryInterface $indisponibiliteRepository,
        PraticienRepositoryInterface $praticienRepository
    ) {
        $this->indisponibiliteRepository = $indisponibiliteRepository;
        $this->praticienRepository = $praticienRepository;
    }

    public function creerIndisponibilite(InputIndisponibiliteDTO $dto): IndisponibiliteDTO
    {
        // Vérifier que le praticien existe
        $praticien = $this->praticienRepository->findById($dto->praticienId);
        if (!$praticien) {
            throw new Exception("Praticien inexistant");
        }

        // Valider les dates
        $dateDebut = new DateTime($dto->dateDebut);
        $dateFin = new DateTime($dto->dateFin);
        $now = new DateTime();

        // Vérifier que date_fin > date_debut
        if ($dateFin <= $dateDebut) {
            throw new Exception("La date de fin doit être postérieure à la date de début");
        }

        // Vérifier que la date de début n'est pas dans le passé
        if ($dateDebut < $now) {
            throw new Exception("Impossible de créer une indisponibilité dans le passé");
        }

        // Créer l'entité
        $id = Uuid::uuid4()->toString();
        $indisponibilite = new Indisponibilite(
            $id,
            $dto->praticienId,
            $dateDebut,
            $dateFin,
            $dto->raison,
            $now
        );

        // Sauvegarder
        $this->indisponibiliteRepository->save($indisponibilite);

        return new IndisponibiliteDTO($indisponibilite);
    }

    public function listerIndisponibilites(string $praticienId): array
    {
        $indisponibilites = $this->indisponibiliteRepository->findByPraticienId($praticienId);
        $dtos = [];
        foreach ($indisponibilites as $indisponibilite) {
            $dtos[] = new IndisponibiliteDTO($indisponibilite);
        }
        return $dtos;
    }

    public function supprimerIndisponibilite(string $indisponibiliteId): void
    {
        $indisponibilite = $this->indisponibiliteRepository->findById($indisponibiliteId);
        if (!$indisponibilite) {
            throw new Exception("Indisponibilité inexistante");
        }

        $this->indisponibiliteRepository->delete($indisponibiliteId);
    }

    public function trouverIndisponibilitesChevauchantes(string $praticienId, DateTime $debut, DateTime $fin): array
    {
        return $this->indisponibiliteRepository->findByPraticienIdAndDateRange($praticienId, $debut, $fin);
    }
}

