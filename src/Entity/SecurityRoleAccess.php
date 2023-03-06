<?php

namespace CkAmaury\Symfony\Entity;

use CkAmaury\Symfony\Database\DB_Timed;
use CkAmaury\Symfony\Repository\SecurityRoleAccessRepository;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=SecurityRoleAccessRepository::class)
 * @ORM\HasLifecycleCallbacks()
 */
class SecurityRoleAccess extends DB_Timed {

    /**
     * @ORM\ManyToOne(targetEntity=SecurityRole::class)
     * @ORM\JoinColumn(nullable=false)
     * @ORM\Id
     */
    private SecurityRole $fk_role;

    /**
     * @ORM\ManyToOne(targetEntity=SecurityAccess::class)
     * @ORM\JoinColumn(nullable=false)
     * @ORM\Id
     */
    private SecurityAccess $fk_access;

    public function getFkRole(): SecurityRole {
        return $this->fk_role;
    }
    public function setFkRole(SecurityRole $fk_role): self {
        $this->fk_role = $fk_role;
        return $this;
    }

    public function getFkAccess(): SecurityAccess {
        return $this->fk_access;
    }
    public function setFkAccess(SecurityAccess $fk_access): self {
        $this->fk_access = $fk_access;
        return $this;
    }

}
