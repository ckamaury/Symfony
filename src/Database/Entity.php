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

    /* ===== DATABASE ===== */
    public function persist(bool $flush = false):static{
        return $this->save($flush);
    }
    public function save(bool $flush = false):static{
        if(is_null($this->getId())) Database::persist($this);
        if($flush) Database::flush();
        return $this;
    }
    public function remove(bool $flush = false):static{
        Database::remove($this,$flush);
        return $this;
    }
    public function isLoaded():bool{
        return Database::contains($this);
    }

    /* ===== STATIC ===== */
    public static function getReference(int $id):static{
        return Database::getReference(static::class,$id);
    }

}