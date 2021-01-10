<?php

namespace CkAmaury\Symfony\Database;

use CkAmaury\Symfony\APP;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\HasLifecycleCallbacks()
 */
class DB_Timed extends DB_Entity {


    /**
     * @ORM\Column(type="datetime",options={"default": "CURRENT_TIMESTAMP"})
     */
    protected $ins_at;

    /**
     * @ORM\Column(type="datetime",options={"default": "CURRENT_TIMESTAMP"})
     */
    protected $upd_at;


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

    public function getInsAt(): ?\DateTimeInterface
    {
        return $this->ins_at;
    }

    public function setInsAt(\DateTimeInterface $ins_at): self
    {
        $this->ins_at = $ins_at;

        return $this;
    }

    public function getUpdAt(): ?\DateTimeInterface
    {
        return $this->upd_at;
    }

    public function setUpdAt(\DateTimeInterface $upd_at): self
    {
        $this->upd_at = $upd_at;

        return $this;
    }

    public function save(bool $pFlush = false){
        if(is_null($this->ins_at)){
            APP::getManager()->persist($this);
        }
        if($pFlush){
            APP::getManager()->flush();
        }
    }

}
