<?php
namespace CkAmaury\Symfony\Database\Type;

use Doctrine\DBAL\ParameterType;
use Doctrine\DBAL\Platforms\AbstractPlatform;
use Doctrine\DBAL\Types\Type;

class MediumintType extends Type {

    public function getName():string {
        return 'mediumint';
    }
    public function getSQLDeclaration(array $fieldDeclaration, AbstractPlatform $platform):string {
        $return = ($platform->getSmallIntTypeDeclarationSQL($fieldDeclaration));
        return ( str_replace ('SMALLINT','MEDIUMINT' ,$return));
    }
    public function convertToPHPValue($value, AbstractPlatform $platform):mixed {
        return $value === null ? null : (int)$value;
    }
    public function getBindingType():int {
        return ParameterType::INTEGER;
    }

}
