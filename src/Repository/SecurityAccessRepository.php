<?php

namespace CkAmaury\Symfony\Repository;

use CkAmaury\Symfony\Entity\SecurityAccess;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<SecurityAccess>
 *
 * @method SecurityAccess|null find($id, $lockMode = null, $lockVersion = null)
 * @method SecurityAccess|null findOneBy(array $criteria, array $orderBy = null)
 * @method SecurityAccess[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class SecurityAccessRepository extends ServiceEntityRepository {

    public function __construct(ManagerRegistry $registry) {
        parent::__construct($registry, SecurityAccess::class);
    }

}
