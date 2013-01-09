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

namespace CrEOF\CustomTypes\Tests\DBAL\Types;

use CrEOF\CustomTypes\PHP\Types\ApproxDate;
use Doctrine\ORM\Query;

class ApproxDateTypeTest extends \Doctrine\Tests\OrmFunctionalTestCase
{
    private static $isSetup = false;

    const RECORD = 'CrEOF\CustomTypes\Tests\DBAL\Types\Record';

    protected function setUp() {
        parent::setUp();
        if (!static::$isSetup) {
            $this->_schemaTool->createSchema(array(
                                                  $this->_em->getClassMetadata(self::RECORD),
                                             ));
            static::$isSetup = true;
        }
    }

    protected function tearDown()
    {
        parent::tearDown();

        $conn = static::$_sharedConn;

        $this->_sqlLoggerStack->enabled = false;

        $conn->executeUpdate('DELETE FROM Record');

        $this->_em->clear();
    }

    public function testNullApproxDate()
    {
        $record = new Record();
        $this->_em->persist($record);
        $this->_em->flush();

        $id = $record->getId();

        $this->_em->clear();

        $queryRecord = $this->_em->getRepository(self::RECORD)->find($id);
        $this->assertEquals($record, $queryRecord);
    }

    public function testEmptyApproxDate()
    {
        $record = new Record();
        $record->setDate(new ApproxDate());
        $this->_em->persist($record);
        $this->_em->flush();

        $id = $record->getId();

        $this->_em->clear();

        $queryRecord = $this->_em->getRepository(self::RECORD)->find($id);
        $this->assertEquals($record, $queryRecord);
    }

    public function testValueApproxDate()
    {
        $record1 = new Record();
        $record1->setDate(new ApproxDate('5/22/12'));
        $this->_em->persist($record1);

        $record2 = new Record();
        $record2->setDate(new ApproxDate('1995'));
        $this->_em->persist($record2);

        $record3 = new Record();
        $record3->setDate(new ApproxDate('11/78'));
        $this->_em->persist($record3);

        $this->_em->flush();

        $id1 = $record1->getId();
        $id2 = $record2->getId();
        $id3 = $record3->getId();

        $this->_em->clear();

        $queryRecord1 = $this->_em->getRepository(self::RECORD)->find($id1);
        $this->assertEquals($record1, $queryRecord1);

        $queryRecord2 = $this->_em->getRepository(self::RECORD)->find($id2);
        $this->assertEquals($record2, $queryRecord2);

        $queryRecord3 = $this->_em->getRepository(self::RECORD)->find($id3);
        $this->assertEquals($record3, $queryRecord3);
    }

    public function testQueryApproxDate()
    {
        $record1 = new Record();
        $record1->setDate(new ApproxDate('5/22/12'));
        $this->_em->persist($record1);

        $record2 = new Record();
        $record2->setDate(new ApproxDate('1995'));
        $this->_em->persist($record2);

        $record3 = new Record();
        $record3->setDate(new ApproxDate('11/78'));
        $this->_em->persist($record3);

        $this->_em->flush();
        $this->_em->clear();

        $records = $this->_em
            ->createQuery('SELECT r FROM CrEOF\CustomTypes\Tests\DBAL\Types\Record r WHERE r.date > :date')
            ->setParameter('date', new ApproxDate('2000'))
            ->getResult();
        $this->assertCount(1, $records);

        $records = $this->_em
            ->createQuery('SELECT r FROM CrEOF\CustomTypes\Tests\DBAL\Types\Record r WHERE r.date < :date')
            ->setParameter('date', new ApproxDate('1/1980'))
            ->getResult();
        $this->assertCount(1, $records);
    }
}

/**
 * @Entity
 */
class Record
{
    /**
     * @var int $id
     *
     * @Id
     * @GeneratedValue(strategy="AUTO")
     * @Column(type="integer")
     */
    protected $id;

    /**
     * @var ApproxDate $date
     *
     * @Column(type="approx_date", nullable=true)
     */
    protected $date;

    /**
     * Get id
     *
     * @return int
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Set date
     *
     * @param ApproxDate $date
     *
     * @return self
     */
    public function setDate(ApproxDate $date)
    {
        $this->date = $date;

        return $this;
    }

    /**
     * Get date
     *
     * @return ApproxDate
     */
    public function getDate()
    {
        return $this->date;
    }
}
