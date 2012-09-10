<?php

namespace CrEOF\DBAL\Types;

use CrEOF\PHP\Types\ApproxDate;
use Doctrine\DBAL\Platforms\AbstractPlatform;
use Doctrine\DBAL\Types\IntegerType;

class ApproxDateType extends IntegerType
{
    const APPROX_DATE = 'approx_date';

    /**
     * Convert database value to PHP value
     *
     * @param string           $value
     * @param AbstractPlatform $platform
     *
     * @return null|string
     */
    public function convertToPHPValue($value, AbstractPlatform $platform)
    {
        return ($value === null ? null : new ApproxDate($value));
    }

    /**
     * Convert PHP value to database value
     *
     * @param string           $value
     * @param AbstractPlatform $platform
     *
     * @return bool
     */
    public function convertToDatabaseValue($value, AbstractPlatform $platform)
    {
        return parent::convertToDatabaseValue(($value === null ? null : $value), $platform);
    }

    /**
     * Get type name
     *
     * @return string
     */
    public function getName()
    {
        return self::APPROX_DATE;
    }
}
