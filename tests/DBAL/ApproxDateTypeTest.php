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

declare(strict_types = 1);

namespace CrEOF\Doctrine\CustomTypes\Tests\DBAL;

use CrEOF\Doctrine\CustomTypes\PHP\ApproxDate;
use CrEOF\Doctrine\CustomTypes\Tests\DBAL\Entity\ApproxDateEntity;
use CrEOF\Doctrine\CustomTypes\Tests\OrmTestCase;

/**
 * Class ApproxDateTypeTest
 *
 * @author  Derek J. Lambert <dlambert@dereklambert.com>
 * @license http://dlambert.mit-license.org MIT
 */
class ApproxDateTypeTest extends OrmTestCase
{
    public function testNullApproxDate()
    {
        $expected = new ApproxDateEntity();

        $this->getEntityManager()->persist($expected);
        $this->getEntityManager()->flush();

        $id = $expected->getId();

        $this->getEntityManager()->clear();

        $actual = $this->getEntityManager()->getRepository(self::APPROXDATE_ENTITY)->find($id);

        self::assertEquals($expected, $actual);
    }

    public function testEmptyApproxDate()
    {
        $expected = new ApproxDateEntity();

        $expected->setDate(new ApproxDate());

        $this->getEntityManager()->persist($expected);
        $this->getEntityManager()->flush();

        $id = $expected->getId();

        $this->getEntityManager()->clear();

        $actual = $this->getEntityManager()->getRepository(self::APPROXDATE_ENTITY)->find($id);

        self::assertEquals(null, $actual->getDate());
    }

    public function testValueApproxDate()
    {
        $expected1 = new ApproxDateEntity();
        $expected2 = new ApproxDateEntity();
        $expected3 = new ApproxDateEntity();

        $expected1->setDate(new ApproxDate('5/22/12'));
        $expected2->setDate(new ApproxDate('1995'));
        $expected3->setDate(new ApproxDate('11/78'));

        $this->getEntityManager()->persist($expected1);
        $this->getEntityManager()->persist($expected2);
        $this->getEntityManager()->persist($expected3);

        $this->getEntityManager()->flush();

        $id1 = $expected1->getId();
        $id2 = $expected2->getId();
        $id3 = $expected3->getId();

        $this->getEntityManager()->clear();

        $actual1 = $this->getEntityManager()->getRepository(self::APPROXDATE_ENTITY)->find($id1);
        $actual2 = $this->getEntityManager()->getRepository(self::APPROXDATE_ENTITY)->find($id2);
        $actual3 = $this->getEntityManager()->getRepository(self::APPROXDATE_ENTITY)->find($id3);

        self::assertEquals($expected1, $actual1);
        self::assertEquals($expected2, $actual2);
        self::assertEquals($expected3, $actual3);
    }

    public function testQueryApproxDate()
    {
        $expected1 = new ApproxDateEntity();
        $expected2 = new ApproxDateEntity();
        $expected3 = new ApproxDateEntity();

        $expected1->setDate(new ApproxDate('5/22/12'));
        $expected2->setDate(new ApproxDate('1995'));
        $expected3->setDate(new ApproxDate('11/78'));

        $this->getEntityManager()->persist($expected1);
        $this->getEntityManager()->persist($expected2);
        $this->getEntityManager()->persist($expected3);

        $this->getEntityManager()->flush();
        $this->getEntityManager()->clear();

        $actual = $this->getEntityManager()
            ->createQuery('SELECT a FROM CrEOF\Doctrine\CustomTypes\Tests\DBAL\Entity\ApproxDateEntity a WHERE a.date > :date')
            ->setParameter('date', new ApproxDate('2000'))
            ->getResult();

        self::assertCount(1, $actual);

        $actual = $this->getEntityManager()
            ->createQuery('SELECT a FROM CrEOF\Doctrine\CustomTypes\Tests\DBAL\Entity\ApproxDateEntity a WHERE a.date > :date')
            ->setParameter('date', new ApproxDate('1/1980'))
            ->getResult();

        self::assertCount(2, $actual);
    }
}
