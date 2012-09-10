<?php

namespace CrEOF\Tests\DBAL\Types;

use Doctrine\ORM\Query;
use CrEOF\PHP\Types\ApproxDate;

class GenderTypeTest extends \Doctrine\Tests\OrmFunctionalTestCase
{
    private static $isSetup = false;

    const GENDER = 'CrEOF\Tests\DBAL\Types\Gender';

    protected function setUp() {
        parent::setUp();
        if (!static::$isSetup) {
            $this->_schemaTool->createSchema(array(
                                                  $this->_em->getClassMetadata(self::GENDER),
                                             ));
            static::$isSetup = true;
        }
    }

    protected function tearDown()
    {
        parent::tearDown();

        $conn = static::$_sharedConn;

        $this->_sqlLoggerStack->enabled = false;

        $conn->executeUpdate('DELETE FROM Gender');

        $this->_em->clear();
    }

    public function testNullGender()
    {
        $gender = new Gender();
        $this->_em->persist($gender);
        $this->_em->flush();

        $id = $gender->getId();

        $this->_em->clear();

        $queryGender = $this->_em->getRepository(self::GENDER)->find($id);
        $this->assertEquals($gender, $queryGender);
    }

    public function testGoodGender()
    {
        $gender1 = new Gender();
        $gender1->setGender('m');
        $this->_em->persist($gender1);

        $gender2 = new Gender();
        $gender2->setGender('MALE');
        $this->_em->persist($gender2);

        $gender3 = new Gender();
        $gender3->setGender('f');
        $this->_em->persist($gender3);

        $gender4 = new Gender();
        $gender4->setGender('FEMALE');
        $this->_em->persist($gender4);

        $this->_em->flush();

        $id1 = $gender1->getId();
        $id2 = $gender2->getId();
        $id3 = $gender3->getId();
        $id4 = $gender4->getId();

        $this->_em->clear();

        $queryGender1 = $this->_em->getRepository(self::GENDER)->find($id1);
        $this->assertEquals('m', $queryGender1->getGender());

        $queryGender2 = $this->_em->getRepository(self::GENDER)->find($id2);
        $this->assertEquals('m', $queryGender2->getGender());

        $queryGender3 = $this->_em->getRepository(self::GENDER)->find($id3);
        $this->assertEquals('f', $queryGender3->getGender());

        $queryGender4 = $this->_em->getRepository(self::GENDER)->find($id4);
        $this->assertEquals('f', $queryGender4->getGender());
    }

    public function testBadGender()
    {
        $this->setExpectedException('CrEOF\Exception\InvalidValueException');

        $gender1 = new Gender();
        $gender1->setGender('w');
        $this->_em->persist($gender1);

        $this->_em->flush();
    }
}

/**
 * @Entity
 */
class Gender
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
     * @var string $gender
     *
     * @Column(type="gender", nullable=true)
     */
    protected $gender;

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
     * Set gender
     *
     * @param string $gender
     *
     * @return self
     */
    public function setGender($gender)
    {
        $this->gender = $gender;

        return $this;
    }

    /**
     * Get gender
     *
     * @return string
     */
    public function getGender()
    {
        return $this->gender;
    }
}
