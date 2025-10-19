<?php

namespace toubilib\core\application\usecases;

use toubilib\core\application\dto\PraticienDTO;

interface ServicePraticienInterface
{
    public function listerPraticiens(): array;
    public function RecherchePraticienByID(string $id): ?PraticienDTO;
    public function getMotifsVisite(string $praticienId): array;
    public function getMoyensPaiement(string $praticienId): array;
}