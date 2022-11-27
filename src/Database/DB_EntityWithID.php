<?php

namespace CkAmaury\Symfony\Database;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\HasLifecycleCallbacks
 * @ORM\MappedSuperclass
 */
class DB_EntityWithID extends DB_Entity {

    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer",options={"unsigned"=true})
     */
    protected ?int $id = null;

    public function getId(): ?int {
        return $this->id;
    }

    public function __toString(): string {
        return $this->getId();
    }

    public function hasSameId($entity):bool{
        return ($this->getId() == $entity->getId());
    }
    public function hasNotSameId($entity):bool{
        return !$this->hasSameId($entity);
    }


}
