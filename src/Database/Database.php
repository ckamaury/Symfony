<?php

namespace CkAmaury\Symfony\Database;


use CkAmaury\Symfony\APP;

class Database {

    public static function flush(){
        APP::getManager()->flush();
    }

    public static function clear($entityName = null){
        APP::getManager()->clear($entityName = null);
    }

    public static function flushAndClear($entityName = null){
        self::flush();
        self::clear();
    }

    public static function forceId(string $class){
        $metadata = APP::getManager()->getClassMetaData($class);
        $metadata->setIdGeneratorType(ClassMetadata::GENERATOR_TYPE_NONE);
        $metadata->setIdGenerator(new AssignedGenerator());
    }

    public static function execute(string $sql,array $parameters = array()){
        $conn = APP::getManager()->getConnection();
        $stmt = $conn->prepare($sql);
        $stmt->execute($parameters);
        return $stmt->rowCount();
    }

    public static function resetIds(string $table_name){

        $sql = 'set @rn := 0;';
        $sql .= 'UPDATE '.$table_name.' set id = (@rn := @rn + 1) order by id;';
        APP::execute($sql);
    }
}