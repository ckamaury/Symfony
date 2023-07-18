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

    public function setId(?int $id): self {
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
        return is_object($entity)
            && get_class($this) == get_class($entity)
            && $this->isSameId($entity->getId());
    }
    public function isDifferent($entity):bool{
        return  !$this->isSame($entity);
    }
    public function isSameId(int $id):bool{
        return $this->getId() == $id;
    }
    public function isDifferentId($entity):bool{
        return  !$this->isSameId($entity);
    }

    /* ===== DATABASE ===== */
    public function persist(bool $flush = false):self{
        return $this->save($flush);
    }
    public function save(bool $flush = false):self{
        if(is_null($this->getId())) Database::persist($this);
        if($flush) Database::flush();
        return $this;
    }
    public function remove(bool $flush = false):self{
        Database::remove($this,$flush);
        return $this;
    }
    public function isLoaded():bool{
        return Database::contains($this);
    }
    public function isNotLoaded():bool{
        return !$this->isLoaded();
    }

    /* ===== STATIC ===== */
    public static function getReference(int $id):self{
        return Database::getReference(static::class,$id);
    }

}