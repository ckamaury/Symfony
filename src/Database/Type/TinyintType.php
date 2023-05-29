<?php
namespace CkAmaury\Symfony\Database\Type;

use Doctrine\DBAL\ParameterType;
use Doctrine\DBAL\Platforms\AbstractPlatform;
use Doctrine\DBAL\Types\Type;

class TinyintType extends Type {
    public function getName():string {
        return 'tinyint';
    }
    public function getSQLDeclaration(array $fieldDeclaration, AbstractPlatform $platform):string {
        $return = ($platform->getSmallIntTypeDeclarationSQL($fieldDeclaration));
        return ( str_replace ('SMALLINT','TINYINT' ,$return));
    }
    public function convertToPHPValue($value, AbstractPlatform $platform):mixed {
        return $value === null ? null : (int)$value;
    }
    public function getBindingType():int {
        return ParameterType::INTEGER;
    }
}
