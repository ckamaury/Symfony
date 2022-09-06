<?php

namespace CkAmaury\Symfony\Password;

use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class PasswordEncoder {


    public function __construct(private UserPasswordHasherInterface $passwordHasher){}

    public function execute($user,string $password):string{
        return $this->passwordHasher->hashPassword($user,$password);
    }

}