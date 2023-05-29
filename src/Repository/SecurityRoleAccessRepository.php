<?php

namespace CkAmaury\Symfony\Repository;

use CkAmaury\Symfony\Entity\SecurityAccess;
use CkAmaury\Symfony\Entity\SecurityRole;
use CkAmaury\Symfony\Entity\SecurityRoleAccess;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<SecurityRoleAccess>
 *
 * @method SecurityRoleAccess|null find($id, $lockMode = null, $lockVersion = null)
 * @method SecurityRoleAccess|null findOneBy(array $criteria, array $orderBy = null)
 * @method SecurityRoleAccess[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class SecurityRoleAccessRepository extends RepositoryMiniTable {

    public function __construct(ManagerRegistry $registry) {
        parent::__construct($registry, SecurityRoleAccess::class);
    }

    public function getDQL_AllByRole(SecurityRole $role) : string{
        return  $this->createQueryBuilder('role_right_access')
            ->select('IDENTITY(role_right_access.access)')
            ->where('role_right_access.role = '.$role->getId())
            ->getDQL();
    }

    /** @param SecurityRole[] $roles */
    public function getDQL_AllByRoles(array $roles) : string{

        $ids = [];
        foreach($roles as $role){
            $ids[] = $role->getId();
        }

        return  $this->createQueryBuilder('role_right_access')
            ->select('IDENTITY(role_right_access.access)')
            ->where('role_right_access.role IN ('.implode(",", $ids).')')
            ->getDQL();
    }

    public function getDQL_AllRoleByAccess(SecurityAccess $access) : string{
        return  $this->createQueryBuilder('roleAccess')
            ->select('IDENTITY(roleAccess.role)')
            ->where('roleAccess.access = '.$access->getId())
            ->getDQL();
    }

    public function deleteAllAccessForRole(SecurityRole $role):int{
        return $this->_em->createQueryBuilder()
            ->delete(SecurityRoleAccess::class,'roleAccess')
            ->where('roleAccess.role = :role')
            ->setParameter('role', $role->getId())
            ->getQuery()
            ->execute();
    }

    public function countRoleByAccess(SecurityAccess $access) : int {
        return $this->createQueryBuilder('roleAccess')
            ->select('count(roleAccess.role)')
            ->where('roleAccess.access = :access')
            ->setParameter('access',$access)
            ->getQuery()
            ->getSingleScalarResult();
    }

}
