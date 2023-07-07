<?php

namespace CkAmaury\Symfony\Repository;

use CkAmaury\Symfony\APP;
use CkAmaury\Symfony\Utils\ArrayUtils;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;

class RepositoryTable extends ServiceEntityRepository {

    private array $values;

    public function findAll():array{
        if($this->isNotLoaded()) $this->values = $this->findBy([]);
        return $this->values;
    }

    public function reset():self{
        unset($this->values);
        return $this;
    }
    public function isLoaded():bool{
        return isset($this->values);
    }
    public function isNotLoaded():bool{
        return !$this->isLoaded();
    }

    public function indexBy(string $field, string $field2 = null):self{
        $this->values = APP::transformDBResult($this->values,$field,$field2);
        return $this;
    }

    public function get(string|int $key):?object{
        return ArrayUtils::get($key,$this->values);
    }
    public function getValues(): array {
        return $this->values;
    }
    protected function setValues(array $values):self{
        $this->values = $values;
        return $this;
    }
    public function addValue(object $value, string $key = null):self{
        if(!is_null($key)) $this->values[$key] = $value;
        else $this->values[] = $value;
        return $this;
    }

}