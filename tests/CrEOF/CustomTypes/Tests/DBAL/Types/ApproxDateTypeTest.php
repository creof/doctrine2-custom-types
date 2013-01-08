<?php

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
