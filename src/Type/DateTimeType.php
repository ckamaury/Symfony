<?php

namespace CkAmaury\Symfony\Type;

use CkAmaury\PhpDatetime\DateTime;
use DateTimeInterface;
use Doctrine\DBAL\Platforms\AbstractPlatform;
use Doctrine\DBAL\Types\ConversionException;
use Doctrine\DBAL\Types\Types;


class DateTimeType extends \Doctrine\DBAL\Types\DateTimeType
{
    /**
     * {@inheritdoc}
     */
    public function getName()
    {
        return Types::DATETIME_MUTABLE;
    }

    /**
     * {@inheritdoc}
     */
    public function getSQLDeclaration(array $fieldDeclaration, AbstractPlatform $platform)
    {
        return $platform->getDateTimeTypeDeclarationSQL($fieldDeclaration);
    }

    /**
     * {@inheritdoc}
     */
    public function convertToDatabaseValue($value, AbstractPlatform $platform)
    {
        if ($value === null) {
            return $value;
        }

        if ($value instanceof DateTimeInterface) {
            return $value->format($platform->getDateTimeFormatString());
        }

        throw ConversionException::conversionFailedInvalidType($value, $this->getName(), ['null', 'DateTime']);
    }

    /**
     * {@inheritdoc}
     */
    public function convertToPHPValue($value, AbstractPlatform $platform)
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
