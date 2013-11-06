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

class GenderTypeTest extends \CrEOF\CustomTypes\Tests\OrmTest
{
    public function testNullGender()
    {
        $gender = new GenderEntity();
        $this->_em->persist($gender);
        $this->_em->flush();

        $id = $gender->getId();

        $this->_em->clear();

        $queryGender = $this->_em->getRepository(self::GENDER_ENTITY)->find($id);
        $this->assertEquals($gender, $queryGender);
    }

    public function testGoodGender()
    {
        $gender1 = new GenderEntity();
        $gender1->setGender('m');
        $this->_em->persist($gender1);

        $gender2 = new GenderEntity();
        $gender2->setGender('MALE');
        $this->_em->persist($gender2);

        $gender3 = new GenderEntity();
        $gender3->setGender('f');
        $this->_em->persist($gender3);

        $gender4 = new GenderEntity();
        $gender4->setGender('FEMALE');
        $this->_em->persist($gender4);

        $this->_em->flush();

        $id1 = $gender1->getId();
        $id2 = $gender2->getId();
        $id3 = $gender3->getId();
        $id4 = $gender4->getId();

        $this->_em->clear();

        $queryGender1 = $this->_em->getRepository(self::GENDER_ENTITY)->find($id1);
        $this->assertEquals('m', $queryGender1->getGender());

        $queryGender2 = $this->_em->getRepository(self::GENDER_ENTITY)->find($id2);
        $this->assertEquals('m', $queryGender2->getGender());

        $queryGender3 = $this->_em->getRepository(self::GENDER_ENTITY)->find($id3);
        $this->assertEquals('f', $queryGender3->getGender());

        $queryGender4 = $this->_em->getRepository(self::GENDER_ENTITY)->find($id4);
        $this->assertEquals('f', $queryGender4->getGender());
    }

    /**
     * Test bad gender value
     *
     * @expectedException \CrEOF\CustomTypes\Exception\InvalidValueException
     */
    public function testBadGender()
    {
        $gender1 = new GenderEntity();
        $gender1->setGender('w');
        $this->_em->persist($gender1);

        $this->_em->flush();
    }

    public function testGenderFind()
    {
        $gender1 = new GenderEntity();
        $gender1->setGender('m');
        $this->_em->persist($gender1);

        $gender2 = new GenderEntity();
        $gender2->setGender('m');
        $this->_em->persist($gender2);

        $gender3 = new GenderEntity();
        $gender3->setGender('f');
        $this->_em->persist($gender3);

        $gender4 = new GenderEntity();
        $gender4->setGender('f');
        $this->_em->persist($gender4);

        $this->_em->flush();
        $this->_em->clear();

        $males = $this->_em->getRepository(self::GENDER_ENTITY)->findByGender('m');
        $this->assertCount(2, $males);

        $females = $this->_em->getRepository(self::GENDER_ENTITY)->findByGender('f');
        $this->assertCount(2, $females);

        $this->_em->clear();

        $males = $this->_em->getRepository(self::GENDER_ENTITY)->findByGender('male');
        $this->assertCount(2, $males);

        $females = $this->_em->getRepository(self::GENDER_ENTITY)->findByGender('female');
        $this->assertCount(2, $females);
    }

    public function testMapping()
    {
        $metadata = $this->_em->getClassMetadata(self::GENDER_ENTITY);

        foreach ($metadata->getFieldNames() as $fieldName) {
            $fieldType = $metadata->getTypeOfField($fieldName);

            // Throws exception if mapping does not exist
            $typeMapping = $this->getPlatform()->getDoctrineTypeMapping($fieldType);
        }
    }

    public function testReverseMapping()
    {
        $result = $this->_schemaTool->getUpdateSchemaSql(array($this->_em->getClassMetadata(self::GENDER_ENTITY)), true);

        $this->assertCount(0, $result);
    }
}

/**
 * GenderEntity
 *
 * @Entity
 */
class GenderEntity
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
