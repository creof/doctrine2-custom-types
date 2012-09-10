<?php

namespace CrEOF\DBAL\Types;

use CrEOF\Exception\InvalidValueException;
use Doctrine\DBAL\Types\BooleanType;
use Doctrine\DBAL\Platforms\AbstractPlatform;

/**
 * Gender type
 *
 * Store gender string of "m" or "f" in database as boolean value
 */
class GenderType extends BooleanType
{
    const GENDER = 'gender';

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
        return (null === $value ? null : ($value ? 'm' : 'f'));
    }

    /**
     * Convert PHP value to database value
     *
     * @param string           $value
     * @param AbstractPlatform $platform
     *
     * @throws InvalidValueException
     *
     * @return bool
     */
    public function convertToDatabaseValue($value, AbstractPlatform $platform)
    {
        switch (strtolower($value)) {
            case null:
                break;
            case 'male':
                //no break
            case 'm':
                $value = true;
                break;
            case 'female':
                //no break
            case 'f':
                $value = false;
                break;
            default:
                throw new InvalidValueException("Invalid gender value: $value");
        }

        return parent::convertToDatabaseValue($value, $platform);
    }

    /**
     * Get type name
     *
     * @return string
     */
    public function getName()
    {
        return self::GENDER;
    }
}
