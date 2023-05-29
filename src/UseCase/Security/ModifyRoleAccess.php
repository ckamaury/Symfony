<?php

namespace CkAmaury\Symfony\UseCase\Security;

use CkAmaury\Symfony\Database\Database;
use CkAmaury\Symfony\Entity\SecurityAccess;
use CkAmaury\Symfony\Entity\SecurityRole;
use CkAmaury\Symfony\Entity\SecurityRoleAccess;

class ModifyRoleAccess {

    public function __construct(private readonly SecurityRole $role){}

    public function execute(array $accessList):void{
        $this->deleteAllAccess();
        Database::getRepository(SecurityAccess::class)->findAll();
        foreach($accessList as $accessId => $authorized){
            if($authorized){
                $access = Database::getRepository(SecurityAccess::class)->find($accessId);
                (new SecurityRoleAccess())
                    ->setRole($this->role)
                    ->setAccess($access)
                    ->persist();
            }
        }
        Database::flush();
    }

    private function deleteAllAccess():void{
        Database::getRepository(SecurityRoleAccess::class)->deleteAllAccessForRole($this->role);
    }

}