<?php

namespace CrEOF\Tests\PHP\Types;

use CrEOF\PHP\Types\ApproxDate;

class ApproxDateTest extends \PHPUnit_Framework_TestCase
{
    public function testDateNoArg()
    {
        $date = new ApproxDate();
        $this->assertEquals('00000000', $date->getDate());
    }

    public function testGoodDateOneArg()
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
        );

        foreach ($tests as $test) {
            $date = new ApproxDate($test['date']);
            $this->assertEquals($test['result'], $date->getDate());
        }
    }

    public function testGoodDateThreeArg()
    {
        $tests = array(
            array('year' => 45, 'month' => null, 'day' => null, 'result' => '19450000'),
            array('year' => 2011, 'month' => null, 'day' => null, 'result' => '20110000'),
            array('year' => 85, 'month' => 3, 'day' => null, 'result' => '19850300'),
            array('year' => '86', 'month' => '04', 'day' => null, 'result' => '19860400'),
            array('year' => 2003, 'month' => 5, 'day' => null, 'result' => '20030500'),
            array('year' => 1999, 'month' => 11, 'day' => null, 'result' => '19991100'),
            array('year' => 1992, 'month' => 1, 'day' => 3, 'result' => '19920103'),
            array('year' => '1992', 'month' => '01', 'day' => '03', 'result' => '19920103'),
            array('year' => 93, 'month' => 11, 'day' => 5, 'result' => '19931105'),
            array('year' => 95, 'month' => 12, 'day' => 15, 'result' => '19951215')
        );

        foreach ($tests as $test) {
            $date = new ApproxDate($test['year'], $test['month'], $test['day']);
            $this->assertEquals($test['result'], $date->getDate());
        }
    }

    public function testBadNumArgs()
    {
        $this->setExpectedException('CrEOF\Exception\InvalidValueException');
        $date = new ApproxDate(45, 2);
    }

    public function testBadArgsOrder()
    {
        $this->setExpectedException('CrEOF\Exception\InvalidValueException');
        $date = new ApproxDate(3, 11, 2012);
    }

    public function testBadMonthArg()
    {
        $this->setExpectedException('CrEOF\Exception\InvalidValueException');
        $date = new ApproxDate(2012, 14, 20);
    }

    public function testBadDayArg()
    {
        $this->setExpectedException('CrEOF\Exception\InvalidValueException');
        $date = new ApproxDate(2012, 3, 54);
    }

    public function testBadDayString()
    {
        $this->setExpectedException('CrEOF\Exception\InvalidValueException');
        $date = new ApproxDate('3/34/89');
    }

    public function testBadDayString2()
    {
        $this->setExpectedException('CrEOF\Exception\InvalidValueException');
        $date = new ApproxDate('19880254');
    }

    public function testBadMonthString()
    {
        $this->setExpectedException('CrEOF\Exception\InvalidValueException');
        $date = new ApproxDate('14/3/89');
    }

    public function testBadMonthString2()
    {
        $this->setExpectedException('CrEOF\Exception\InvalidValueException');
        $date = new ApproxDate('19861503');
    }

    public function testBadYearString()
    {
        $this->setExpectedException('CrEOF\Exception\InvalidValueException');
        $date = new ApproxDate('3/3/899');
    }

    public function testBadYearString2()
    {
        $this->setExpectedException('CrEOF\Exception\InvalidValueException');
        $date = new ApproxDate('3/3/89953');
    }

    public function testBadDateString()
    {
        $this->setExpectedException('CrEOF\Exception\InvalidValueException');
        $date = new ApproxDate('198');
    }

    public function testBadDateString2()
    {
        $this->setExpectedException('CrEOF\Exception\InvalidValueException');
        $date = new ApproxDate('19987');
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
