<?php
namespace toubilib\core\application\usecases;

use toubilib\core\application\dto\AuthDTO;
use toubilib\core\application\ports\AuthRepositoryInterface;

class ServiceAuth implements ServiceAuthInterface
{
    private AuthRepositoryInterface $authRepository;

    public function __construct(AuthRepositoryInterface $authRepository)
    {
        $this->authRepository = $authRepository;
    }

    public function authentifier(string $email , string $password): ?AuthDTO
    {
        $auth= $this->authRepository->findByEmail($email);
        if(!$auth || !password_verify($password, $auth->getMdp())){
            return null;
        }
        return new AuthDTO($auth);
    }
}