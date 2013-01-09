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

namespace CrEOF\CustomTypes\Tests\PHP\Types;

use CrEOF\CustomTypes\PHP\Types\ApproxDate;

class ApproxDateTest extends \PHPUnit_Framework_TestCase
{
    public function testDateNoArg()
    {
        $date = new ApproxDate();
        $this->assertEquals('00000000', $date->getDate());
    }

    public function testGoodDate()
    {
        $tests = array(
            array('date' => '45', 'result' => '19450000'),
            array('date' => '2011', 'result' => '20110000'),
            array('date' => '3/85', 'result' => '19850300'),
            array('date' => '04/86', 'result' => '19860400'),
            array('date' => '5/2003', 'result' => '20030500'),
            array('date' => '11/1999', 'result' => '19991100'),
            array('date' => '1/3/1992', 'result' => '19920103'),
            array('date' => '01/03/1992', 'result' => '19920103'),
            array('date' => '11/5/93', 'result' => '19931105'),
            array('date' => '12/15/95', 'result' => '19951215'),
            array('date' => '19990000', 'result' => '19990000'),
            array('date' => '19981000', 'result' => '19981000'),
            array('date' => '19980323', 'result' => '19980323'),
            array('date' => '1998-10', 'result' => '19981000'),
            array('date' => '1998-03-23', 'result' => '19980323'),
        );

        foreach ($tests as $test) {
            $date = new ApproxDate($test['date']);
            $this->assertEquals($test['result'], $date->getDate());
        }
    }

    /**
     * Test bad day value
     *
     * @expectedException \CrEOF\CustomTypes\Exception\InvalidValueException
     */
    public function testBadDay()
    {
        new ApproxDate('3/34/89');
    }

    /**
     * Test bad day value
     *
     * @expectedException \CrEOF\CustomTypes\Exception\InvalidValueException
     */
    public function testBadDay2()
    {
        new ApproxDate('19880254');
    }

    /**
     * Test bad day value
     *
     * @expectedException \CrEOF\CustomTypes\Exception\InvalidValueException
     */
    public function testBadDay3()
    {
        new ApproxDate('1988-02-54');
    }

    /**
     * Test bad month value
     *
     * @expectedException \CrEOF\CustomTypes\Exception\InvalidValueException
     */
    public function testBadMonth()
    {
        new ApproxDate('14/3/89');
    }

    /**
     * Test bad month value
     *
     * @expectedException \CrEOF\CustomTypes\Exception\InvalidValueException
     */
    public function testBadMonth2()
    {
        new ApproxDate('19861503');
    }

    /**
     * Test bad month value
     *
     * @expectedException \CrEOF\CustomTypes\Exception\InvalidValueException
     */
    public function testBadMonth3()
    {
        new ApproxDate('1986-15-03');
    }

    /**
     * Test bad year value
     *
     * @expectedException \CrEOF\CustomTypes\Exception\InvalidValueException
     */
    public function testBadYear()
    {
        new ApproxDate('3/3/899');
    }

    /**
     * Test bad year value
     *
     * @expectedException \CrEOF\CustomTypes\Exception\InvalidValueException
     */
    public function testBadYear2()
    {
        new ApproxDate('3/3/89953');
    }

    /**
     * Test bad year value
     *
     * @expectedException \CrEOF\CustomTypes\Exception\InvalidValueException
     */
    public function testBadYear3()
    {
        new ApproxDate('89953-3-3');
    }

    /**
     * Test bad date value
     *
     * @expectedException \CrEOF\CustomTypes\Exception\InvalidValueException
     */
    public function testBadDate()
    {
        new ApproxDate('198');
    }

    /**
     * Test bad date value
     *
     * @expectedException \CrEOF\CustomTypes\Exception\InvalidValueException
     */
    public function testBadDate2()
    {
        new ApproxDate('19987');
    }

    /**
     * Test bad date value
     *
     * @expectedException \CrEOF\CustomTypes\Exception\InvalidValueException
     */
    public function testBadDate3()
    {
        new ApproxDate('199807123');
    }

    public function testDateFormat()
    {
        $tests = array(
            array('date' => '45', 'format' => 'n/j/Y', 'result' => '1945'),
            array('date' => '2011', 'format' => 'm/d/Y', 'result' => '2011'),
            array('date' => '3/85', 'format' => 'F jS, Y', 'result' => 'March 1985'),
            array('date' => '04/86', 'format' => 'M j, Y', 'result' => 'Apr 1986'),
            array('date' => '5/2003', 'format' => 'j-M-Y', 'result' => 'May-2003'),
            array('date' => '1/3/1992', 'format' => 'F jS, Y', 'result' => 'January 3rd, 1992'),
            array('date' => '19990000', 'format' => 'm/d/Y', 'result' => '1999'),
            array('date' => '19981000', 'format' => 'Ymd g:h:a', 'result' => '199810'),
        );

        foreach ($tests as $test) {
            $date = new ApproxDate($test['date']);
            $this->assertEquals($test['result'], $date->format($test['format']));
        }
    }
}
