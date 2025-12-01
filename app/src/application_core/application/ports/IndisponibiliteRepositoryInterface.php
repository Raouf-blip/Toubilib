<?php
namespace toubilib\core\application\ports;

use toubilib\core\domain\entities\Indisponibilite;
use DateTime;

interface IndisponibiliteRepositoryInterface
{
    public function save(Indisponibilite $indisponibilite): void;
    public function findByPraticienId(string $praticienId): array;
    public function findByPraticienIdAndDateRange(string $praticienId, DateTime $debut, DateTime $fin): array;
    public function delete(string $indisponibiliteId): void;
    public function findById(string $id): ?Indisponibilite;
}

