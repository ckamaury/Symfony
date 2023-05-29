<?php

namespace CkAmaury\Symfony\Entity;

use CkAmaury\Symfony\Database\Entity;
use CkAmaury\Symfony\Repository\SecurityAccessRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: SecurityAccessRepository::class)]
#[ORM\HasLifecycleCallbacks]
class SecurityAccess extends Entity {

    #[ORM\ManyToOne(targetEntity: SecurityAccess::class)]
    private ?self $parent;

    #[ORM\Column]
    private string $name;

    #[ORM\Column]
    private string $description;

    public function getParent(): ?self {
        return $this->parent;
    }
    public function setParent(?self $parent): self {
        $this->parent = $parent;
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
        if(!is_null($this->getParent())){
            return implode('_',[$this->getParent()->getFullName(),$this->getName()]);
        }
        return $this->getName();
    }
    public function getFormName():string{
        if(!is_null($this->getParent())){
            return implode(' > ',[$this->getParent()->getFullName(),$this->getName()]);
        }
        return $this->getName();
    }

    public function getLevel():int{
        $access = $this;
        $level = 0;
        while(!is_null($access->getParent())){
            $level++;
            $access = $access->getParent();
        }
        return $level;
    }

}
