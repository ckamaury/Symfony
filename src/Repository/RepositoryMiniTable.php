<?php

namespace CkAmaury\Symfony\Repository;

use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;

class RepositoryMiniTable extends ServiceEntityRepository {

    protected array $values;

    public function findAll():array{
        if(!isset($this->values)){
            $this->values = parent::findAll();
        }
        return $this->values;
    }

    public function resetValues(){
        unset($this->values);
        $this->findAll();
    }

    public function addValue($value){
        $this->values[] = $value;
    }

}