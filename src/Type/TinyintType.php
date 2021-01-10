<?php
namespace CkAmaury\Symfony\Type;

use Doctrine\DBAL\ParameterType;
use Doctrine\DBAL\Platforms\AbstractPlatform;
use Doctrine\DBAL\Types\PhpIntegerMappingType;
use Doctrine\DBAL\Types\Type;

class TinyintType extends Type
{

    /**
     * {@inheritdoc}
     */
    public function getName() {
        return 'tinyint';
    }

    /**
     * {@inheritdoc}
     */
    public function getSQLDeclaration(array $fieldDeclaration, AbstractPlatform $platform) {
        $return = ($platform->getSmallIntTypeDeclarationSQL($fieldDeclaration));
        return ( str_replace ('SMALLINT','TINYINT' ,$return));
    }

    /**
     * {@inheritdoc}
     */
    public function convertToPHPValue($value, AbstractPlatform $platform) {
        return $value === null ? null : (int)$value;
    }

    /**
     * {@inheritdoc}
     */
    public function getBindingType() {
        return ParameterType::INTEGER;
    }
}
