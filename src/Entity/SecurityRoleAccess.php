<?php

namespace CkAmaury\Symfony\Entity;

use CkAmaury\Symfony\Database\EntityTimed;
use CkAmaury\Symfony\Repository\SecurityRoleAccessRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: SecurityRoleAccessRepository::class)]
#[ORM\HasLifecycleCallbacks]
class SecurityRoleAccess extends EntityTimed {

    #[ORM\ManyToOne(targetEntity: SecurityRole::class)]
    private SecurityRole $role;

    #[ORM\ManyToOne(targetEntity: SecurityAccess::class)]
    private SecurityAccess $access;

    public function getRole(): SecurityRole {
        return $this->role;
    }
    public function setRole(SecurityRole $role): self {
        $this->role = $role;
        return $this;
    }

    public function getAccess(): SecurityAccess {
        return $this->access;
    }
    public function setAccess(SecurityAccess $access): self {
        $this->access = $access;
        return $this;
    }

}
