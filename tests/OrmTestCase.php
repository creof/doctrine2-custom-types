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

namespace CrEOF\Doctrine\CustomTypes\Tests;

use PHPUnit_Framework_TestCase;
use Doctrine\ORM\Tools\Setup;
use Doctrine\ORM\EntityManager;
use Doctrine\DBAL\DriverManager;
use Doctrine\DBAL\Connection;
use Doctrine\DBAL\Types\Type;
use Doctrine\ORM\Tools\SchemaTool;

/**
 * Class OrmTestCase
 *
 * @author  Derek J. Lambert <dlambert@dereklambert.com>
 * @license http://dlambert.mit-license.org MIT
 */
abstract class OrmTestCase extends PHPUnit_Framework_TestCase
{
    const APPROXDATE_ENTITY = 'CrEOF\Doctrine\CustomTypes\Tests\DBAL\Entity\ApproxDateEntity';
    const GENDER_ENTITY     = 'CrEOF\Doctrine\CustomTypes\Tests\DBAL\Entity\GenderEntity';

    /**
     * @var EntityManager
     */
    protected $entityManager;

    /**
     * @var SchemaTool
     */
    protected $schemaTool;

    /**
     * @var bool
     */
    protected static $setup = false;

    /**
     * @var Connection
     */
    protected static $connection;

    public static function setUpBeforeClass()
    {
        static::$connection = static::getConnection();
    }

    protected function setUp()
    {
        $this->entityManager = $this->getEntityManager();
        $this->schemaTool    = $this->getSchemaTool();

        if (! static::$setup) {
            static::$setup = true;

            Type::addType('approx_date', 'CrEOF\Doctrine\CustomTypes\DBAL\ApproxDateType');
            Type::addType('gender', 'CrEOF\Doctrine\CustomTypes\DBAL\GenderType');
        }

        $this->getSchemaTool()->createSchema($this->getAllClassMetadata());
    }

    protected function tearDown()
    {
        $this->getSchemaTool()->dropSchema($this->getAllClassMetadata());

        $this->getEntityManager()->clear();
    }

    /**
     * @return EntityManager
     */
    protected function getEntityManager()
    {
        if (null !== $this->entityManager) {
            return $this->entityManager;
        }

        $config = Setup::createAnnotationMetadataConfiguration([__DIR__ . '/lib'], true);

        return EntityManager::create(static::getConnection(), $config);
    }

    /**
     * @return SchemaTool
     */
    protected function getSchemaTool()
    {
        if (null !== $this->schemaTool) {
            return $this->schemaTool;
        }

        return new SchemaTool($this->getEntityManager());
    }

    /**
     * @return \Doctrine\ORM\Mapping\ClassMetadata[]
     */
    protected function getAllClassMetadata()
    {
        return [
            $this->getEntityManager()->getClassMetadata(self::APPROXDATE_ENTITY),
            $this->getEntityManager()->getClassMetadata(self::GENDER_ENTITY)
        ];
    }

    /**
     * @return Connection
     *
     * @throws \Doctrine\DBAL\DBALException
     */
    protected static function getConnection()
    {
        if (null !== static::$connection) {
            return static::$connection;
        }

        return DriverManager::getConnection(self::getConnectionParameters());
    }

    /**
     * @return array
     */
    protected static function getCommonConnectionParameters()
    {
        $connectionParams = array(
            'driver'   => $GLOBALS['db_type'],
            'user'     => $GLOBALS['db_username'],
            'password' => $GLOBALS['db_password'],
            'host'     => $GLOBALS['db_host'],
            'dbname'   => null,
            'port'     => $GLOBALS['db_port']
        );

        return $connectionParams;
    }

    /**
     * @return array
     * @throws \Doctrine\DBAL\DBALException
     */
    protected static function getConnectionParameters()
    {
        $parameters           = static::getCommonConnectionParameters();
        $parameters['dbname'] = $GLOBALS['db_name'];

        $connection           = DriverManager::getConnection($parameters);
        $dbName               = $connection->getDatabase();

        $connection->close();

        $tmpConnection = DriverManager::getConnection(static::getCommonConnectionParameters());

        $tmpConnection->getSchemaManager()->dropAndCreateDatabase($dbName);
        $tmpConnection->close();

        return $parameters;
    }
}
