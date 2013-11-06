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

namespace CrEOF\CustomTypes\Tests;

use Doctrine\DBAL\Types\Type;

/**
 * Abstract ORM test class
 *
 * @author  Derek J. Lambert <dlambert@dereklambert.com>
 * @license http://dlambert.mit-license.org MIT
 */
abstract class OrmTest extends \Doctrine\Tests\OrmFunctionalTestCase
{
    /**
     * @var bool
     */
    protected static $_setup = false;

    /**
     * @var \Doctrine\DBAL\Connection
     */
    protected static $_sharedConn;

    const APPROXDATE_ENTITY = 'CrEOF\CustomTypes\Tests\DBAL\Types\ApproxDateEntity';
    const GENDER_ENTITY = 'CrEOF\CustomTypes\Tests\DBAL\Types\GenderEntity';

    protected function setUp()
    {
        parent::setUp();

        if ( ! static::$_setup) {
            static::$_setup = true;

            Type::addType('approx_date', 'CrEOF\CustomTypes\DBAL\Types\ApproxDateType');
            Type::addType('gender', 'CrEOF\CustomTypes\DBAL\Types\GenderType');

            $this->_schemaTool->createSchema(
                array(
                    $this->_em->getClassMetadata(self::APPROXDATE_ENTITY),
                    $this->_em->getClassMetadata(self::GENDER_ENTITY)
                )
            );
        }
    }

    protected function tearDown()
    {
        parent::tearDown();

        $conn = static::$_sharedConn;

        $this->_sqlLoggerStack->enabled = false;

        $conn->executeUpdate('DELETE FROM ApproxDateEntity');
        $conn->executeUpdate('DELETE FROM GenderEntity');

        $this->_em->clear();
    }
}
