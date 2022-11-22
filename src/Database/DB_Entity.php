<?php

namespace CkAmaury\Symfony\Database;

use CkAmaury\PhpDatetime\DateTime;
use CkAmaury\Symfony\APP;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\HasLifecycleCallbacks()
 */
class DB_Entity {

    public function remove(bool $pFlush = false, ?string $manager = null):self{
        APP::getManager()->remove($this);
        if($pFlush) $this->flush($manager);
        return $this;
    }
    public function save(bool $pFlush = false, ?string $manager = null):self{
        if($this->getId() == 0){
            APP::getManager($manager)->persist($this);
        }
        if($pFlush) $this->flush($manager);
        return $this;
    }

    protected function convertDate($p_Value){
        if(is_object($p_Value) && gettype($p_Value) == DateTime::class){
            return $p_Value;
        }
        elseif(is_null($p_Value)){
            return NULL;
        }
        elseif(is_string($p_Value)){
            return new DateTime($p_Value);
        }
        elseif(is_object($p_Value)){
            return (new DateTime())->setTimestamp($p_Value->getTimestamp());
        }
        else{
            return $p_Value;
        }
    }
    protected function convertObject($p_Value,$p_Class){
        if(is_null($p_Value)){
            return NULL;
        }
        elseif(!is_object($p_Value) && intval($p_Value) > 0){
            return APP::getReference($p_Class,intval($p_Value));
        }
        else{
            return $p_Value;
        }
    }

    public function isLoaded():bool{
        return APP::getManager()->contains($this);
    }

    /** @deprecated  */
    public function isSame($entity):bool{
        return $this->hasSameId($entity);
    }
    public function hasSameId($entity):bool{
        return ($this->getId() == $entity->getId());
    }
    public function hasNotSameId($entity):bool{
        return !$this->hasSameId($entity);
    }

    protected function flush(?string $manager = null){
        APP::getManager($manager)->flush();
    }

}
