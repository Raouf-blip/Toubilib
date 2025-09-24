<?php

namespace toubilib\core\application\ports;

use DateTime;

interface RDVRepositoryInterface
{
    public function findBusySlots(string $praticienId, DateTime $debut, DateTime $fin): array;
}
