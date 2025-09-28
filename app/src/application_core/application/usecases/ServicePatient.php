<?php
namespace toubilib\core\application\usecases;

use toubilib\core\application\ports\PatientRepositoryInterface;

class ServicePatient
{
    private PatientRepositoryInterface $patientRepository;

    public function __construct(PatientRepositoryInterface $patientRepository)
    {
        $this->patientRepository = $patientRepository;
    }

    public function existePatient(string $patientId): bool
    {
        return $this->patientRepository->findById($patientId) !== null;
    }
}
