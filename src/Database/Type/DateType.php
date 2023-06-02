<?php
namespace CkAmaury\Symfony\Database\Type;

use CkAmaury\Symfony\DateTime\DateTime;
use DateTimeInterface;
use Doctrine\DBAL\Platforms\AbstractPlatform;
use Doctrine\DBAL\Types\ConversionException;
use Doctrine\DBAL\Types\Types;

class DateType extends \Doctrine\DBAL\Types\DateTimeType {

    public function getName():string {
        return Types::DATE_MUTABLE;
    }
    public function getSQLDeclaration(array $column, AbstractPlatform $platform):string {
        return $platform->getDateTypeDeclarationSQL($column);
    }
    public function convertToDatabaseValue($value, AbstractPlatform $platform):mixed {
        if (is_null($value)) return null;
        elseif ($value instanceof DateTimeInterface) {
            return $value->format($platform->getDateFormatString());
        }
        throw ConversionException::conversionFailedInvalidType($value, $this->getName(), ['null', 'DateTime']);
    }
    public function convertToPHPValue($value, AbstractPlatform $platform): null|DateTimeInterface|DateTime {
        if (is_null($value) || $value instanceof DateTimeInterface) {
            return $value;
        }
        return new DateTime($value);
    }

}
