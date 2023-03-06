<?php

namespace CkAmaury\Symfony\Entity;

use CkAmaury\Symfony\Database\DB_EntityWithID;
use CkAmaury\Symfony\Repository\SecurityAccessRepository;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=SecurityAccessRepository::class)
 * @ORM\HasLifecycleCallbacks()
 */
class SecurityAccess extends DB_EntityWithID {

    /**
     * @ORM\ManyToOne(targetEntity=SecurityAccess::class)
     * @ORM\JoinColumn(nullable=true)
     */
    private ?self $fk_parent;

    /** @ORM\Column(type="string", length=100) */
    private string $name;

    /** @ORM\Column(type="string", length=255) */
    private string $description;

    public function getFkParent(): ?self {
        return $this->fk_parent;
    }
    public function setFkParent(?self $fk_parent): self {
        $this->fk_parent = $fk_parent;
        return $this;
    }

    public function getName(): string {
        return $this->name;
    }
    public function setName(string $name): self {
        $this->name = $name;
        return $this;
    }

    public function getDescription(): string {
        return $this->description;
    }
    public function setDescription(string $description): self {
        $this->description = $description;
        return $this;
    }

    public function getFullName():string{
        if(!is_null($this->getFkParent())){
            return implode('_',[$this->getFkParent()->getFullName(),$this->getName()]);
        }
        return $this->getName();
    }
    public function getFormName():string{
        if(!is_null($this->getFkParent())){
            return implode(' > ',[$this->getFkParent()->getFullName(),$this->getName()]);
        }
        return $this->getName();
    }

    public function getLevel():int{
        $access = $this;
        $level = 0;
        while(!is_null($access->getFkParent())){
            $level++;
            $access = $access->getFkParent();
        }
        return $level;
    }
    /*public function countRoles():int{
        return Repository::SecurityRoleAccess()->countRoleByAccess($this);
    }*/

}
