<?php

namespace CkAmaury\Symfony\Database\Type;

use CkAmaury\PhpDatetime\DateTime;
use DateTimeInterface;
use Doctrine\DBAL\Platforms\AbstractPlatform;
use Doctrine\DBAL\Types\ConversionException;
use Doctrine\DBAL\Types\Types;

class DateTimeType extends \Doctrine\DBAL\Types\DateTimeType {

    public function getName():string{
        return Types::DATETIME_MUTABLE;
    }
    public function getSQLDeclaration(array $fieldDeclaration, AbstractPlatform $platform):string{
        return $platform->getDateTimeTypeDeclarationSQL($fieldDeclaration);
    }
    public function convertToDatabaseValue($value, AbstractPlatform $platform):mixed
    {
        if ($value === null) {
            return $value;
        }

        if ($value instanceof DateTimeInterface) {
            return $value->format($platform->getDateTimeFormatString());
        }

        throw ConversionException::conversionFailedInvalidType($value, $this->getName(), ['null', 'DateTime']);
    }
    public function convertToPHPValue($value, AbstractPlatform $platform):mixed
    {
        if ($value === null || $value instanceof DateTimeInterface) {
            return $value;
        }

        $val = new DateTime($value);

        if (! $val) {
            throw ConversionException::conversionFailedFormat($value, $this->getName(), $platform->getDateTimeFormatString());
        }

        return $val;
    }

}
