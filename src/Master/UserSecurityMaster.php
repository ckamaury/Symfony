<?php

namespace CkAmaury\Symfony\Master;

use CkAmaury\Symfony\Database\Database;
use CkAmaury\Symfony\Entity\SecurityAccess;
use CkAmaury\Symfony\Entity\SecurityRole;

abstract class UserSecurityMaster {

    /** @var SecurityRole[]  */
    protected array $securityRoles = [];

    /** @var SecurityAccess[]  */
    protected array $securityAccesses = [];

    abstract protected function importRolesOfUser();

    protected function initialize():void{
        Database::getRepository(SecurityAccess::class)->findAll();
        Database::getRepository(SecurityRole::class)->findAll();
        $this->importRolesOfUser();
        $this->loadSecurityAccesses();
    }

    private function loadSecurityAccesses():self{
        $this->securityAccesses = Database::getRepository(SecurityAccess::class)->getAllByRoles($this->getSecurityRoles());
        return $this;
    }

    public function getSecurityRoles(): array {
        return $this->securityRoles;
    }
    public function getSecurityAccesses(): array {
        return $this->securityAccesses;
    }
    public function getSecurityRolesString(): string {
        $return = [];
        foreach($this->getSecurityRoles() as $role){
            $return[] = $role->getDescription();
        }
        return implode(' | ',$return);
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
            if(is_null($access->getParent())) $count++;
        }
        return $count;
    }
    public function isActive():bool{
        return $this->countPrimaryAccess() > 0;
    }

}