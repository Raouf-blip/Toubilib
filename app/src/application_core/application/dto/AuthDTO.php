<?php
namespace toubilib\core\application\dto;

use toubilib\core\domain\entities\Auth;

class AuthDTO
{
    public string $id;
    public string $email;
    public string $mdp;
    public int $role;

    public function __construct(Auth $auth)
    {
        $this->id = $auth->getId();
        $this->email = $auth->getEmail();
        $this->mdp = $auth->getMdp();
        $this->role = $auth->getRole();
    }
}