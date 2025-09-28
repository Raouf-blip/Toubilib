<?php
namespace toubilib\core\application\usecases;

interface ServicePatientInterface
{
    public function existePatient(string $patientId): bool;
}
