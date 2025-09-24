<?php
namespace toubilib\core\application\usecases;

use toubilib\core\application\dto\InputRDVDTO;
use toubilib\core\domain\entities\RDV;
use toubilib\core\application\dto\RDVDTO;
use DateTime;

interface ServiceRDVInterface
{
    public function listerCreneauxOccupes(string $praticienId, DateTime $debut, DateTime $fin): array;

    public function consulterRdv(string $rdvId): ?RDVDTO;

    public function creerRdv(InputRDVDTO $dto): RDV;
}
