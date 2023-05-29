<?php

namespace CkAmaury\Symfony\Password;

use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Security\Core\User\PasswordAuthenticatedUserInterface;

class PasswordEncoder {

    public function __construct(private readonly UserPasswordHasherInterface $passwordHasher){}

    public function execute(PasswordAuthenticatedUserInterface $user,string $password):string{
        return $this->passwordHasher->hashPassword($user,$password);
    }

}