<?php

namespace CkAmaury\Symfony\Repository;

use CkAmaury\Symfony\APP;
use CkAmaury\Symfony\Utils\ArrayUtils;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;

class RepositoryTable extends ServiceEntityRepository {

    private array $values;

    public function findAll():array{
        if(!isset($this->values)) $this->values = $this->findBy([]);
        return $this->values;
    }

    public function unlock():self{
        unset($this->values);
        return $this;
    }

    public function indexBy(string $field, string $field2 = null):self{
        APP::transformDBResult($this->values,$field,$field2);
        return $this;
    }

    public function get(string|int $key):?object{
        return ArrayUtils::get($key,$this->values);
    }



}