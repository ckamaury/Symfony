<?php

namespace CkAmaury\Symfony\Repository;

use App\Entity\TicketStatusType;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

class RepositoryMiniTable extends ServiceEntityRepository {

    protected array $values;

    public function findAll():array{
        if(!isset($this->values)){
            $this->values = parent::findAll();
        }
        return $this->values;
    }

}