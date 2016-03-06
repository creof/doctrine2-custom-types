<?php
/**
 * Copyright (C) 2016 Derek J. Lambert
 *
 * Permission is hereby granted, free of charge, to any person obtaining a copy
 * of this software and associated documentation files (the "Software"), to deal
 * in the Software without restriction, including without limitation the rights
 * to use, copy, modify, merge, publish, distribute, sublicense, and/or sell
 * copies of the Software, and to permit persons to whom the Software is
 * furnished to do so, subject to the following conditions:
 *
 * The above copyright notice and this permission notice shall be included in
 * all copies or substantial portions of the Software.
 *
 * THE SOFTWARE IS PROVIDED "AS IS", WITHOUT WARRANTY OF ANY KIND, EXPRESS OR
 * IMPLIED, INCLUDING BUT NOT LIMITED TO THE WARRANTIES OF MERCHANTABILITY,
 * FITNESS FOR A PARTICULAR PURPOSE AND NONINFRINGEMENT. IN NO EVENT SHALL THE
 * AUTHORS OR COPYRIGHT HOLDERS BE LIABLE FOR ANY CLAIM, DAMAGES OR OTHER
 * LIABILITY, WHETHER IN AN ACTION OF CONTRACT, TORT OR OTHERWISE, ARISING FROM,
 * OUT OF OR IN CONNECTION WITH THE SOFTWARE OR THE USE OR OTHER DEALINGS IN THE
 * SOFTWARE.
 */

namespace CrEOF\Doctrine\CustomTypes\DBAL;

use CrEOF\Doctrine\CustomTypes\PHP\ApproxDate;
use Doctrine\DBAL\Platforms\AbstractPlatform;
use Doctrine\DBAL\Types\IntegerType;

/**
 * Doctrine ApproxDateType
 *
 * Stores partial dates in an integer field.
 *
 * @author  Derek J. Lambert <dlambert@dereklambert.com>
 * @license http://dlambert.mit-license.org MIT
 */
class ApproxDateType extends IntegerType
{
    /**
     * @param mixed            $value
     * @param AbstractPlatform $platform
     *
     * @return ApproxDate|null
     */
    public function convertToPHPValue($value, AbstractPlatform $platform)
    {
        if (null === $value) {
            return null;
        }

        return new ApproxDate((string) $value);
    }

    /**
     * @param mixed            $value
     * @param AbstractPlatform $platform
     *
     * @return int|null
     */
    public function convertToDatabaseValue($value, AbstractPlatform $platform)
    {
        $dbValue = (int)(string) $value;

        if (0 === $dbValue) {
            $dbValue = null;
        }

        return $dbValue;
    }

    /**
     * @return string
     */
    public function getName()
    {
        return array_search(get_class($this), static::getTypesMap(), true);
    }

    /**
     * @param AbstractPlatform $platform
     *
     * @return array
     */
    public function getMappedDatabaseTypes(AbstractPlatform $platform)
    {
        return array($this->getName());
    }

    /**
     * @param AbstractPlatform $platform
     *
     * @return bool
     */
    public function requiresSQLCommentHint(AbstractPlatform $platform)
    {
        return true;
    }
}
