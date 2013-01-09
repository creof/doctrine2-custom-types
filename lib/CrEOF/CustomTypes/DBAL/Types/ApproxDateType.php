<?php
/**
 * Copyright (C) 2012-2013 Derek J. Lambert
 *
 * Permission is hereby granted, free of charge, to any person obtaining a copy of this software and associated
 * documentation files (the "Software"), to deal in the Software without restriction, including without limitation
 * the rights to use, copy, modify, merge, publish, distribute, sublicense, and/or sell copies of the Software, and
 * to permit persons to whom the Software is furnished to do so, subject to the following conditions:
 *
 * The above copyright notice and this permission notice shall be included in all copies or substantial portions of
 * the Software.
 *
 * THE SOFTWARE IS PROVIDED "AS IS", WITHOUT WARRANTY OF ANY KIND, EXPRESS OR IMPLIED, INCLUDING BUT NOT LIMITED TO
 * THE WARRANTIES OF MERCHANTABILITY, FITNESS FOR A PARTICULAR PURPOSE AND NONINFRINGEMENT. IN NO EVENT SHALL THE
 * AUTHORS OR COPYRIGHT HOLDERS BE LIABLE FOR ANY CLAIM, DAMAGES OR OTHER LIABILITY, WHETHER IN AN ACTION OF CONTRACT,
 * TORT OR OTHERWISE, ARISING FROM, OUT OF OR IN CONNECTION WITH THE SOFTWARE OR THE USE OR OTHER DEALINGS IN THE
 * SOFTWARE.
 */

namespace CrEOF\DBAL\Types;

use CrEOF\CustomTypes\PHP\Types\ApproxDate;
use Doctrine\DBAL\Platforms\AbstractPlatform;
use Doctrine\DBAL\Types\IntegerType;

/**
 * Doctrine2 ApproxDateType
 *
 * Stores partial dates in an integer field.
 *
 * @author  Derek J. Lambert <dlambert@dereklambert.com>
 * @license http://dlambert.mit-license.org MIT
 */
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
