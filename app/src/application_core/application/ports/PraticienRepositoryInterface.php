<?php

namespace toubilib\core\application\ports;

interface PraticienRepositoryInterface
{
    public function findAll(): array;
}