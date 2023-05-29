<?php

namespace CkAmaury\Symfony\Entity;

use CkAmaury\Symfony\APP;
use CkAmaury\Symfony\Database\Entity;
use CkAmaury\Symfony\Repository\SecurityRoleRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: SecurityRoleRepository::class)]
#[ORM\HasLifecycleCallbacks]
class SecurityRole extends Entity {

    #[ORM\Column]
    private string $name;

    #[ORM\Column]
    private string $description;

    /** @var SecurityAccess[] */
    private array $accesses;

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

    public function getRightsAccess():array{
        if(!isset($this->accesses)) $this->loadAccesses();
        return $this->accesses;
    }
    public function loadAccesses():self{
        $this->accesses = APP::getRepository(SecurityAccess::class)->getAllByRole($this);
        return $this;
    }
    public function hasAccess(string|SecurityAccess $right):bool{
        if(is_object($right) && get_class($right) == SecurityAccess::class){
            $right = $right->getFullName();
        }
        foreach($this->getRightsAccess() as $rightsAccess){
            if($rightsAccess->getFullName() == $right) return TRUE;
        }
        return FALSE;
    }
    public function countAccesses():int{
        return count($this->getRightsAccess());
    }

    public function __toString(): string {
        return $this->getName();
    }

}
