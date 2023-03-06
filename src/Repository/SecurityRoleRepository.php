<?php

namespace CkAmaury\Symfony\Repository;

use CkAmaury\Symfony\Entity\SecurityRole;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<SecurityRole>
 *
 * @method SecurityRole|null find($id, $lockMode = null, $lockVersion = null)
 * @method SecurityRole|null findOneBy(array $criteria, array $orderBy = null)
 * @method SecurityRole[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class SecurityRoleRepository extends RepositoryMiniTable {

    public function __construct(ManagerRegistry $registry) {
        parent::__construct($registry, SecurityRole::class);
    }

    /** @return SecurityRole[] */
    public function findAll():array{
        if(!isset($this->values)){
            $this->values = $this->createQueryBuilder('role')
                ->orderBy('role.name', 'ASC')
                ->getQuery()
                ->getResult();
        }
        return $this->values;
    }

}
