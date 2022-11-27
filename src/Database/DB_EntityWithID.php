<?php

namespace CkAmaury\Symfony\Database;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\HasLifecycleCallbacks()
 */
class DB_EntityWithID extends DB_Entity {

    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     */
    private ?int $id = null;

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
