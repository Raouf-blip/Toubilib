<?php

namespace toubilib\core\application\ports;

use toubilib\core\domain\entities\praticien\Praticien;

interface PraticienRepositoryInterface
{
    public function findAll(): array;
    public function findById(string $id): ?Praticien;
    public function getMotifsVisite(string $praticienId): array;
}