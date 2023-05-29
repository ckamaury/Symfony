<?php

namespace CkAmaury\Symfony\Database;

use CkAmaury\Symfony\APP;
use Doctrine\ORM\EntityManager;

class Database {

    public static function getContainer(){
        return APP::getKernel()->getContainer();
    }
    public static function getManager($base = null): EntityManager{
        if(is_null($base)){
            return self::getContainer()->get('doctrine.orm.entity_manager');
        }
        else{
            return self::getContainer()->get('doctrine')->getManager($base);
        }
    }

    public static function flush($entity = null):void{
        self::getManager()->flush($entity = null);
    }
    public static function clear($entityName = null):void{
        APP::getManager()->clear($entityName = null);
    }
    public static function persist($entity,bool $flush = false):void{
        self::getManager()->persist($entity);
        if($flush) self::flush();
    }
    public static function remove($entity,bool $flush = false):void{
        self::getManager()->remove($entity);
        if($flush) self::flush();
    }
    public static function contains($entity):bool{
        return self::getManager()->contains($entity);
    }

    public static function execute(string $sql, array $parameters = array()):int{
        $conn = APP::getManager()->getConnection();
        $stmt = $conn->prepare($sql);
        return $stmt->executeStatement($parameters);
    }
    public static function resetIdsOfTable(string $tableName):int{
        $sql = 'set @rn := 0;';
        $sql .= 'UPDATE '.$tableName.' set id = (@rn := @rn + 1) order by id;';
        return self::execute($sql);
    }

}