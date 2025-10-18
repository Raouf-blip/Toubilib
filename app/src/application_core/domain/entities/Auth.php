<?php
namespace toubilib\core\domain\entities;

class Auth{
    private string $id;
    private string $email;
    private string $mdp;

    public function __construct(
        string $id,
        string $email,
        string $mdp
    ){
        $this->id=$id;
        $this->email=$email;
        $this->mdp=$mdp;
    }

    public function getId(): string { return $this->id; }
    public function getEmail(): string { return $this->email; }
    public function getMdp(): string { return $this->mdp; }
}