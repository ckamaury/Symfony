<?php

namespace CkAmaury\Symfony\Database;

use Doctrine\ORM\Mapping as ORM;

#[ORM\HasLifecycleCallbacks]
#[ORM\MappedSuperclass]
abstract class Entity {

    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    protected ?int $id = null;

    public function setId(?int $id): static {
        $this->id = $id;
        return $this;
    }
    public function getId(): ?int {
        return $this->id;
    }

    public function __toString(): string {
        return $this->getId();
    }

    public function isSame($entity):bool{
        return  get_class($this) == get_class($entity)
            &&  $this->getId() == $entity->getId();
    }
    public function isDifferent($entity):bool{
        return  !$this->isSame($entity);
    }

    /* ===== DATABASE ===== */
    public function persist(bool $flush = false):static{
        if(is_null($this->getId())){
            Database::persist($this,$flush);
        }
        return $this;
    }
    public function remove(bool $flush = false):static{
        Database::remove($this,$flush);
        return $this;
    }
    public function isLoaded():bool{
        return Database::contains($this);
    }

}