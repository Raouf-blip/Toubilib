<?php
namespace toubilib\core\application\usecases;

use toubilib\core\application\dto\AuthDTO;

interface ServiceAuthInterface
{
    public function authentifier(string $email , string $password): ?AuthDTO;
}