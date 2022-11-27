<?php

namespace CkAmaury\Symfony\Database;

use CkAmaury\PhpDatetime\DateTime;
use CkAmaury\Symfony\APP;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\HasLifecycleCallbacks
 * @ORM\MappedSuperclass
 */
class DB_TimedWithID extends DB_EntityWithID {

    /**
     * @ORM\Column(type="datetime",options={"default": "CURRENT_TIMESTAMP"})
     */
    protected ?DateTime $ins_at = null;

    /**
     * @ORM\Column(type="datetime",options={"default": "CURRENT_TIMESTAMP"})
     */
    protected ?DateTime $upd_at = null;

    /**
     * @ORM\PrePersist()
     */
    public function pre_insert(){
        $this->setInsAt(APP::getDB_Time());
        $this->setUpdAt(APP::getDB_Time());
    }

    /**
     * @ORM\PreUpdate()
     */
    public function pre_update(){
        $this->setUpdAt(APP::getDB_Time());
    }

    public function getInsAt(): ?DateTime{
        return $this->ins_at;
    }
    public function setInsAt(DateTime $ins_at): self {
        $this->ins_at = $ins_at;
        return $this;
    }

    public function getUpdAt(): ?DateTime{
        return $this->upd_at;
    }
    public function setUpdAt(DateTime $upd_at): self{
        $this->upd_at = $upd_at;
        return $this;
    }

    public function eraseDatabaseTime(){
        $this->ins_at = null;
        $this->upd_at = null;
    }
    public function save(bool $pFlush = false, ?string $manager = null):self{
        if(is_null($this->ins_at)){
            APP::getManager($manager)->persist($this);
        }
        if($pFlush) $this->flush($manager);
        return $this;
    }

}
