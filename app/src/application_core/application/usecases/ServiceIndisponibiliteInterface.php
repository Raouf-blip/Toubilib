<?php
namespace toubilib\core\application\usecases;

use toubilib\core\application\dto\InputIndisponibiliteDTO;
use toubilib\core\application\dto\IndisponibiliteDTO;

interface ServiceIndisponibiliteInterface
{
    public function creerIndisponibilite(InputIndisponibiliteDTO $dto): IndisponibiliteDTO;
    public function listerIndisponibilites(string $praticienId): array;
    public function supprimerIndisponibilite(string $indisponibiliteId): void;
    public function trouverIndisponibilitesChevauchantes(string $praticienId, \DateTime $debut, \DateTime $fin): array;
}

