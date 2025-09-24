<?php
namespace toubilib\core\application\dto;

class InputRDVDTO
{
    public function __construct(
        public string $praticienId,
        public string $patientId,
        public \DateTime $dateHeure,
        public string $motifVisite,
        public int $duree
    ) {}
}
