<?php

namespace CkAmaury\Symfony\Database;

use CkAmaury\Symfony\APP;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\Id\AssignedGenerator;
use Doctrine\ORM\Mapping\ClassMetadataInfo;

class Database {

    public static function getContainer(){
        return APP::getKernel()->getContainer();
    }
    public static function getDoctrine(){
        return self::getContainer()->get('doctrine');
    }
    public static function getManager($base = null): EntityManager{
        return self::getDoctrine()->getManager($base);
    }
    public static function getRepository(string $class){
        return self::getManager()->getRepository($class);
    }
    public static function getReference($entityName, $id) {
        return self::getManager()->getReference($entityName, $id);
    }
    public static function getTableName(string $className):string{
        return self::getManager()->getClassMetadata($className)->getTableName();
    }
    public static function resetManager():bool{
        if (!self::getManager()->isOpen()) {
            self::getDoctrine()->resetManager();
        }
        return self::getManager()->isOpen();
    }

    public static function flush($entity = null):void{
        self::getManager()->flush($entity);
    }
    public static function clear($entityName = null):void{
        self::getManager()->clear($entityName);
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
        $conn = self::getManager()->getConnection();
        $stmt = $conn->prepare($sql);
        return $stmt->executeStatement($parameters);
    }
    public static function resetIds(string $className):int{
        $tableName = self::getTableName($className);
        return self::resetIdsOfTable($tableName);
    }
    public static function resetIdsOfTable(string $tableName):int{
        self::clear();
        $sql = "set @rn := 0;";
        $sql .= "UPDATE $tableName set id = (@rn := @rn + 1) order by id;";
        $sql .= "ALTER TABLE $tableName AUTO_INCREMENT = 1;";
        return self::execute($sql);
    }
    public static function forceId(string $class):void{
        $metadata = self::getManager()->getClassMetaData($class);
        $metadata->setIdGeneratorType(ClassMetadataInfo::GENERATOR_TYPE_NONE);
        $metadata->setIdGenerator(new AssignedGenerator());
    }

    public static function transformArrayInString(array $values):string{
        foreach($values as $key => $value){
            if(is_string($value)) $values[$key] = "'$value'";
        }
        return implode(',',$values);
    }

}