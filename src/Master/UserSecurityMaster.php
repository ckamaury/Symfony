<?php

namespace CkAmaury\Symfony\Master;

use CkAmaury\Symfony\APP;
use CkAmaury\Symfony\Entity\SecurityAccess;
use CkAmaury\Symfony\Entity\SecurityRole;

abstract class UserSecurityMaster {

    protected array $securityRoles = [];
    protected array $securityAccesses = [];

    abstract protected function importRolesOfUser();

    protected function initialize():void{
        APP::getRepository(SecurityAccess::class)->findAll();
        APP::getRepository(SecurityRole::class)->findAll();
        $this->importRolesOfUser();
        $this->loadSecurityAccesses();
    }

    private function loadSecurityAccesses():self{
        $this->securityAccesses = APP::getRepository(SecurityAccess::class)->getAllByRoles($this->getSecurityRoles());
        return $this;
    }

    public function getSecurityRoles(): array {
        return $this->securityRoles;
    }
    public function getSecurityAccesses(): array {
        return $this->securityAccesses;
    }

    public function getRoles():array{
        $roles = [];
        foreach($this->getSecurityAccesses() as $access){
            $roles[] = 'ROLE_'.$access->getFullName();
        }
        return $roles;
    }
    public function hasAccess(string $access):bool{
        return in_array('ROLE_'.$access,$this->getRoles());
    }
    public function countPrimaryAccess():int{
        $count = 0;
        foreach($this->getSecurityAccesses() as $access){
            if(is_null($access->getFkParent())) $count++;
        }
        return $count;
    }
    public function isActive():bool{
        return $this->countPrimaryAccess() > 0;
    }

}