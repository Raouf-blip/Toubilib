<?php

namespace toubilib\core\application\ports;
use toubilib\core\domain\entities\RDV;

use DateTime;

interface RDVRepositoryInterface
{
    public function findBusySlots(string $praticienId, DateTime $debut, DateTime $fin): array;

    public function findById(string $rdvId): ?RDV;

}
