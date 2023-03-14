<?php

namespace CkAmaury\Symfony\UseCase\Security;

use CkAmaury\Symfony\APP;
use CkAmaury\Symfony\Database\Database;
use CkAmaury\Symfony\Entity\SecurityAccess;
use CkAmaury\Symfony\Entity\SecurityRole;
use CkAmaury\Symfony\Entity\SecurityRoleAccess;

class ModifyRoleAccess {

    public function __construct(private SecurityRole $role){}

    public function execute(array $accessList){
        $this->deleteAllAccess();
        APP::getRepository(SecurityAccess::class)->findAll();
        foreach($accessList as $accessId => $authorized){
            if($authorized){
                $access = APP::getRepository(SecurityAccess::class)->find($accessId);
                (new SecurityRoleAccess())
                    ->setFkRole($this->role)
                    ->setFkAccess($access)
                    ->save();
            }
        }
        Database::flush();
    }

    private function deleteAllAccess():void{
        APP::getRepository(SecurityRoleAccess::class)->deleteAllAccessForRole($this->role);
    }

}