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

namespace CrEOF\Doctrine\CustomTypes\Tests\DBAL;

use CrEOF\Doctrine\CustomTypes\Exception\InvalidArgumentException;
use CrEOF\Doctrine\CustomTypes\Tests\OrmTestCase;
use CrEOF\Doctrine\CustomTypes\Tests\DBAL\Entity\GenderEntity;

/**
 * Class GenderTypeTest
 *
 * @author  Derek J. Lambert <dlambert@dereklambert.com>
 * @license http://dlambert.mit-license.org MIT
 */
class GenderTypeTest extends OrmTestCase
{
    public function testNullGender()
    {
        $gender = new GenderEntity();

        $this->getEntityManager()->persist($gender);
        $this->getEntityManager()->flush();

        $id = $gender->getId();

        $this->getEntityManager()->clear();

        $queryGender = $this->getEntityManager()->getRepository(self::GENDER_ENTITY)->find($id);

        self::assertEquals($gender, $queryGender);
    }

    public function testGoodGender()
    {
        $gender1 = new GenderEntity();
        $gender2 = new GenderEntity();
        $gender3 = new GenderEntity();
        $gender4 = new GenderEntity();

        $gender1->setGender('m');
        $gender2->setGender('MALE');
        $gender3->setGender('f');
        $gender4->setGender('FEMALE');

        $this->getEntityManager()->persist($gender1);
        $this->getEntityManager()->persist($gender2);
        $this->getEntityManager()->persist($gender3);
        $this->getEntityManager()->persist($gender4);
        $this->getEntityManager()->flush();

        $id1 = $gender1->getId();
        $id2 = $gender2->getId();
        $id3 = $gender3->getId();
        $id4 = $gender4->getId();

        $this->getEntityManager()->clear();

        $queryGender1 = $this->getEntityManager()->getRepository(self::GENDER_ENTITY)->find($id1);
        self::assertEquals('m', $queryGender1->getGender());

        $queryGender2 = $this->getEntityManager()->getRepository(self::GENDER_ENTITY)->find($id2);
        self::assertEquals('m', $queryGender2->getGender());

        $queryGender3 = $this->getEntityManager()->getRepository(self::GENDER_ENTITY)->find($id3);
        self::assertEquals('f', $queryGender3->getGender());

        $queryGender4 = $this->getEntityManager()->getRepository(self::GENDER_ENTITY)->find($id4);
        self::assertEquals('f', $queryGender4->getGender());
    }

    /**
     * Test bad gender value
     *
     * @expectedException InvalidArgumentException
     */
    public function testBadGender()
    {
        $gender1 = new GenderEntity();

        $gender1->setGender('w');

        $this->getEntityManager()->persist($gender1);
        $this->getEntityManager()->flush();
    }

    public function testGenderFind()
    {
        $gender1 = new GenderEntity();
        $gender2 = new GenderEntity();
        $gender3 = new GenderEntity();
        $gender4 = new GenderEntity();

        $gender1->setGender('m');
        $gender2->setGender('m');
        $gender3->setGender('f');
        $gender4->setGender('f');

        $this->getEntityManager()->persist($gender1);
        $this->getEntityManager()->persist($gender2);
        $this->getEntityManager()->persist($gender3);
        $this->getEntityManager()->persist($gender4);
        $this->getEntityManager()->flush();
        $this->getEntityManager()->clear();

        $males = $this->getEntityManager()->getRepository(self::GENDER_ENTITY)->findByGender('m');
        self::assertCount(2, $males);

        $females = $this->getEntityManager()->getRepository(self::GENDER_ENTITY)->findByGender('f');
        self::assertCount(2, $females);

        $this->getEntityManager()->clear();

        $males = $this->getEntityManager()->getRepository(self::GENDER_ENTITY)->findByGender('male');
        self::assertCount(2, $males);

        $females = $this->getEntityManager()->getRepository(self::GENDER_ENTITY)->findByGender('female');
        self::assertCount(2, $females);
    }
}
