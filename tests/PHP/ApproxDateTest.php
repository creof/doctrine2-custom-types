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

namespace CrEOF\Doctrine\CustomTypes\Tests\PHP;

use CrEOF\Doctrine\CustomTypes\Exception\InvalidArgumentException;
use CrEOF\Doctrine\CustomTypes\PHP\ApproxDate;
use PHPUnit_Framework_TestCase;

/**
 * Class ApproxDateTest
 *
 *
 * @author  Derek J. Lambert <dlambert@dereklambert.com>
 * @license http://dlambert.mit-license.org MIT
 */
class ApproxDateTest extends PHPUnit_Framework_TestCase
{
    /**
     * @return array
     */
    public function dateValueData()
    {
        return [
            'testNoValue' => [
                'input' => null,
                'expected' => '00000000'
            ],
            'testYearWithoutCentury' => [
                'input' => '45',
                'expected' => '19450000'
            ],
            'testYearWithCentury' => [
                'input' => '2011',
                'expected' => '20110000'
            ],
            'testNoPadMonthSlashYearWithoutCentury' => [
                'input' => '3/85',
                'expected' => '19850300'
            ],
            'testPadMonthSlashYearWithoutCentury' => [
                'input' => '04/86',
                'expected' => '19860400'
            ],
            'testNoPadMonthSlashYearWithCentury' => [
                'input' => '5/2003',
                'expected' => '20030500'
            ],
            'testPadMonthSlashYearWithCentury' => [
                'input' => '05/2003',
                'expected' => '20030500'
            ],
            'testMonthSlashYearWithCentury' => [
                'input' => '11/1999',
                'expected' => '19991100'
            ],
            'testNoPadMonthSlashNoPadDaySlashYearWithCentury' => [
                'input' => '1/3/1992',
                'expected' => '19920103'
            ],
            'testPadMonthSlashPadDaySlashYearWithCentury' => [
                'input' => '01/03/1992',
                'expected' => '19920103'
            ],
            'testMonthSlashPadDaySlashYearWithCentury' => [
                'input' => '11/5/93',
                'expected' => '19931105'
            ],
            'testPadMonthSlashNoPadDaySlashYearWithoutCentury' => [
                'input' => '01/5/93',
                'expected' => '19930105'
            ],
            'testMonthSlashDaySlashYearWithoutCentury' => [
                'input' => '12/15/95',
                'expected' => '19951215'
            ],
            'testYearPadMonthPadDay' => [
                'input' => '19990000',
                'expected' => '19990000'
            ],
            'testYearMonthPadDay' => [
                'input' => '19981000',
                'expected' => '19981000'
            ],
            'testYearMonthDay' => [
                'input' => '19980323',
                'expected' => '19980323'
            ],
            'testYearWithCenturyDashMonth' => [
                'input' => '1998-10',
                'expected' => '19981000'
            ],
            'testYearWithCenturyDashNoPadMonth' => [
                'input' => '1998-3',
                'expected' => '19980300'
            ],
            'testYearWithCenturyDashPadMonthDashDay' => [
                'input' => '1998-03-23',
                'expected' => '19980323'
            ],
            'testYearWithCenturyDashMonthTrailingDash' => [
                'input' => '1998-10-',
                'expected' => new InvalidArgumentException('Invalid date value "1998-10-"')
            ],
            'testYearWithCenturyTrailingDash' => [
                'input' => '1998-',
                'expected' => new InvalidArgumentException('Invalid date value "1998-"')
            ],
            'testYearWithouyCenturyDashMonthTrailingDash' => [
                'input' => '98-10-',
                'expected' => new InvalidArgumentException('Invalid date value "98-10-"')
            ],
            'testBadDayDashMDY' => [
                'input' => '3/34/89',
                'expected' => new InvalidArgumentException('Invalid day value "34"')
            ],
            'testBadDayYMD' => [
                'input' => '19880254',
                'expected' => new InvalidArgumentException('Invalid day value "54"')
            ],
            'testBadDayDashYMD' => [
                'input' => '1988-02-54',
                'expected' => new InvalidArgumentException('Invalid day value "54"')
            ],
            'testBadMonthSlashMDY' => [
                'input' => '14/3/89',
                'expected' => new InvalidArgumentException('Invalid month value "14"')
            ],
            'testBadMonthYMD' => [
                'input' => '19861503',
                'expected' => new InvalidArgumentException('Invalid month value "15"')
            ],
            'testBadMonthDashYMD' => [
                'input' => '1986-15-03',
                'expected' => new InvalidArgumentException('Invalid month value "15"')
            ],
            'testShortYearSlashMDY' => [
                'input' => '3/3/899',
                'expected' => new InvalidArgumentException('Invalid date value "3/3/899"')
            ],
            'testLongYearSlashMDY' => [
                'input' => '3/3/89953',
                'expected' => new InvalidArgumentException('Invalid date value "3/3/89953"')
            ],
            'testLongYearDashYMD' => [
                'input' => '89953-3-3',
                'expected' => new InvalidArgumentException('Invalid date value "89953-3-3"')
            ],
            'testShortYear' => [
                'input' => '198',
                'expected' => new InvalidArgumentException('Invalid date value "198"')
            ],
            'testLongYear' => [
                'input' => '19987',
                'expected' => new InvalidArgumentException('Invalid date value "19987"')
            ],
            'testLongDate' => [
                'input' => '199807123',
                'expected' => new InvalidArgumentException('Invalid date value "199807123"')
            ]
        ];
    }

    /**
     * @return array
     */
    public function dateFormatData()
    {
        return [
            'set1' => [
                'input' => '45',
                'format' => 'n/j/Y',
                'expected' => '1945'
            ],
            'set2' => [
                'input' => '2011',
                'format' => 'm/d/Y',
                'expected' => '2011'
            ],
            'set3' => [
                'input' => '3/85',
                'format' => 'F jS, Y',
                'expected' => 'March 1985'
            ],
            'set4' => [
                'input' => '04/86',
                'format' => 'M j, Y',
                'expected' => 'Apr 1986'
            ],
            'set5' => [
                'input' => '5/2003',
                'format' => 'j-M-Y',
                'expected' => 'May-2003'
            ],
            'set6' => [
                'input' => '1/3/1992',
                'format' => 'F jS, Y',
                'expected' => 'January 3rd, 1992'
            ],
            'set7' => [
                'input' => '19990000',
                'format' => 'm/d/Y',
                'expected' => '1999'
            ],
            'set8' => [
                'input' => '19981000',
                'format' => 'Ymd g:h:a',
                'expected' => '199810'
            ],
        ];
    }

    /**
     * @param mixed $input
     * @param mixed $expected
     *
     * @dataProvider dateValueData
     */
    public function testDateData($input, $expected)
    {
        try {
            $actual = (new ApproxDate($input))->getDate();
        } catch (InvalidArgumentException $e) {
            $actual = $e;
        }

        self::assertEquals($expected, $actual);
    }

    /**
     * @param mixed $input
     * @param mixed $format
     * @param mixed $expected
     *
     * @dataProvider dateFormatData
     */
    public function testDateFormat($input, $format, $expected)
    {
        $date = new ApproxDate($input);

        self::assertEquals($expected, $date->format($format));
    }
}
