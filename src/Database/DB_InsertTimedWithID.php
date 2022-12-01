<?php

namespace CkAmaury\Symfony\Database;

use CkAmaury\PhpDatetime\DateTime;
use CkAmaury\Symfony\APP;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\HasLifecycleCallbacks
 * @ORM\MappedSuperclass
 */
class DB_InsertTimedWithID extends DB_EntityWithID {

    /**
     * @ORM\Column(type="datetime",options={"default": "CURRENT_TIMESTAMP"})
     */
    protected ?DateTime $ins_at = null;

    /**
     * @ORM\PrePersist()
     */
    public function pre_insert(){
        $this->setInsAt(APP::getDB_Time());
    }

    public function getInsAt(): ?DateTime{
        return $this->ins_at;
    }
    public function setInsAt(DateTime $ins_at): self {
        $this->ins_at = $ins_at;
        return $this;
    }

    public function eraseDatabaseTime(){
        $this->ins_at = null;
    }
    public function save(bool $pFlush = false, ?string $manager = null):self{
        if(is_null($this->ins_at)){
            APP::getManager($manager)->persist($this);
        }
        if($pFlush) $this->flush($manager);
        return $this;
    }

}
