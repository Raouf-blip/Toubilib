<?php

namespace toubilib\core\application\usecases;

use toubilib\core\application\dto\PraticienDTO;
use toubilib\core\application\ports\PraticienRepositoryInterface;

class ServicePraticien implements ServicePraticienInterface
{
    private PraticienRepositoryInterface $praticienRepository;

    public function __construct(PraticienRepositoryInterface $praticienRepository)
    {
        $this->praticienRepository = $praticienRepository;
    }

    public function listerPraticiens(): array
    {
        $praticiens = $this->praticienRepository->findAll();
        $praticienDTOs = [];
        foreach ($praticiens as $praticien) {
            $praticienDTOs[] = new PraticienDTO($praticien);
        }
        return $praticienDTOs;
    }
}