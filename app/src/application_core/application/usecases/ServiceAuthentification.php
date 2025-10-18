<?php
namespace toubilib\core\application\usecases;

use toubilib\core\application\ports\AuthRepositoryInterface;
use toubilib\core\domain\entities\Auth;

class ServiceAuthentification
{
    private AuthRepositoryInterface $authRepository;

    public function __construct(AuthRepositoryInterface $authRepository)
    {
        $this->authRepository = $authRepository;
    }

}