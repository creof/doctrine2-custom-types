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
        );

        foreach ($tests as $test) {
            $date = new ApproxDate($test['date']);
            $this->assertEquals($test['result'], $date->getDate());
        }
    }

    public function testBadDay()
    {
        $this->setExpectedException('CrEOF\Exception\InvalidValueException');
        $date = new ApproxDate('3/34/89');
    }

    public function testBadDay2()
    {
        $this->setExpectedException('CrEOF\Exception\InvalidValueException');
        $date = new ApproxDate('19880254');
    }

    public function testBadMonth()
    {
        $this->setExpectedException('CrEOF\Exception\InvalidValueException');
        $date = new ApproxDate('14/3/89');
    }

    public function testBadMonth2()
    {
        $this->setExpectedException('CrEOF\Exception\InvalidValueException');
        $date = new ApproxDate('19861503');
    }

    public function testBadYear()
    {
        $this->setExpectedException('CrEOF\Exception\InvalidValueException');
        $date = new ApproxDate('3/3/899');
    }

    public function testBadYear2()
    {
        $this->setExpectedException('CrEOF\Exception\InvalidValueException');
        $date = new ApproxDate('3/3/89953');
    }

    public function testBadDate()
    {
        $this->setExpectedException('CrEOF\Exception\InvalidValueException');
        $date = new ApproxDate('198');
    }

    public function testBadDate2()
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
