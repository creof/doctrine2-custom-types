<?php
namespace OMC\StoreBundle\Types;

use Doctrine\DBAL\Platforms\AbstractPlatform;
use Doctrine\DBAL\Types\StringType;

class ApproxDate extends StringType {
    const APPROXDATE = 'approxdate';

    /**
     * Convert database value to PHP value
     *
     * @param string           $value
     * @param AbstractPlatform $platform
     *
     * @return null|string
     */
    public function convertToPHPValue($value, AbstractPlatform $platform) {
        return (null === $value) ? null : ($value ? 'm' : 'f');
    }

    /**
     * Convert PHP value to database value
     *
     * @param string           $value
     * @param AbstractPlatform $platform
     *
     * @return bool
     */
    public function convertToDatabaseValue($value, AbstractPlatform $platform) {
        switch (strtolower($value)) {
            case 'm':
                $value = true;
                break;
            case 'f':
                $value = false;
                break;
        }
        return parent::convertToDatabaseValue($value, $platform);
    }

    /**
     * Get type name
     *
     * @return string
     */
    public function getName() {
        return self::GENDER;
    }
}
