<?php

namespace CkAmaury\Symfony\Repository;

use CkAmaury\Symfony\APP;
use CkAmaury\Symfony\Entity\SecurityAccess;
use CkAmaury\Symfony\Entity\SecurityRole;
use CkAmaury\Symfony\Entity\SecurityRoleAccess;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<SecurityAccess>
 *
 * @method SecurityAccess|null find($id, $lockMode = null, $lockVersion = null)
 * @method SecurityAccess|null findOneBy(array $criteria, array $orderBy = null)
 * @method SecurityAccess[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class SecurityAccessRepository extends RepositoryMiniTable {

    public function __construct(ManagerRegistry $registry) {
        parent::__construct($registry, SecurityAccess::class);
    }

    /** @return SecurityAccess[] */
    public function getAllByRole(SecurityRole $role):array{

        $sub_query = APP::getRepository(SecurityRoleAccess::class)->getDQL_AllByRole($role);

        return $this->createQueryBuilder('security_access')
            ->where('security_access.id IN ('.$sub_query.')')
            ->orderBy('security_access.name', 'ASC')
            ->getQuery()
            ->getResult();
    }

    /**
     * @param SecurityRole[] $roles
     * @return SecurityAccess[]
     */
    public function getAllByRoles(array $roles):array{

        $sub_query = APP::getRepository(SecurityRoleAccess::class)->getDQL_AllByRoles($roles);

        return $this->createQueryBuilder('security_access')
            ->where('security_access.id IN ('.$sub_query.')')
            ->orderBy('security_access.name', 'ASC')
            ->getQuery()
            ->getResult();
    }

}
