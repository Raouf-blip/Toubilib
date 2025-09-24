<?php

namespace toubilib\core\application\ports;

use DateTime;

interface RDVRepositoryInterface
{
    public function findBusySlots(int $praticienId, DateTime $debut, DateTime $fin): array;
}
