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

namespace CrEOF\CustomTypes\PHP\Types;

use CrEOF\CustomTypes\Exception\InvalidValueException;

/**
 * ApproxDate type
 *
 * A pseudo-date object to store incomplete date values
 *
 * @author  Derek J. Lambert <dlambert@dereklambert.com>
 * @license http://dlambert.mit-license.org MIT
 */
class ApproxDate
{
    /**
     * @var int $month
     */
    protected $month;

    /**
     * @var int $day
     */
    protected $day;

    /**
     * @var int $year
     */
    protected $year;


    /**
     * @param null|string $date
     */
    public function __construct($date = null)
    {
        if ( ! empty($date)) {
            $this->setDate($date);
        }
    }

    /**
     * Set day
     *
     * @param mixed $day
     *
     * @throws InvalidValueException
     *
     * @return self
     */
    public function setDay($day)
    {
        $day = (int) $day;

        switch (true) {
            case empty($day):
                $this->day = null;
                break;
            case ($day >= 1 && $day <= 31):
                $this->day = $day;
                break;
            default:
                throw new InvalidValueException("Invalid day value: $day");
        }

        return $this;
    }

    /**
     * Get day
     *
     * @return int
     */
    public function getDay()
    {
        return $this->day;
    }

    /**
     * Set month
     *
     * @param mixed $month
     *
     * @throws InvalidValueException
     *
     * @return self
     */
    public function setMonth($month)
    {
        $month = (int) $month;

        switch (true) {
            case empty($month):
                $this->month = null;
                break;
            case ($month >=1 && $month <= 12):
                $this->month = $month;
                break;
            default:
                throw new InvalidValueException("Invalid month value: $month");
        }

        return $this;
    }

    /**
     * Get month
     *
     * @return int
     */
    public function getMonth()
    {
        return $this->month;
    }

    /**
     * Set year
     *
     * @param mixed $year
     *
     * @throws InvalidValueException
     *
     * @return self
     */
    public function setYear($year)
    {
        $year = (int) $year;

        switch (true) {
            case empty($year):
                $this->year = null;
                break;
            case ($year < 100):
                $this->year = (int) date('y') + 30 < $year ? 1900 + $year : 2000 + $year;
                break;
            case ($year > 999):
                $this->year = $year;
                break;
            default:
                throw new InvalidValueException("Invalid year value: $year");
        }

        return $this;
    }

    /**
     * Get year
     *
     * @return int
     */
    public function getYear()
    {
        return $this->year;
    }

    /**
     * Set date
     *
     * @param string $date
     *
     * @throws InvalidValueException
     *
     * @return self
     */
    public function setDate($date)
    {
        $regex = <<<EOD
/(?J)
^
(?:
    (?:
        (?<year>\d{4})
        (?<month>\d{1,2})
        (?<day>\d{1,2})
    )
    |
    (?:
        (?P<year>\d{4})
        -
        (?P<month>\d{1,2})
        (?:
            -
            (?P<day>\d{1,2}))?
        )
    |
    (?:
        (?:
            (?<month>\d{1,2})
            [-\.\/]
            (?:
                (?<day>\d{1,2})
                [-\.\/]
            )?
        )?
        (?<year>\d{2}|\d{4})
    )
)
$
/x
EOD;

        switch (1) {
            case preg_match_all($regex, $date, $matches, PREG_SET_ORDER):
                break;
            default:
                throw new InvalidValueException("Invalid date value: $date");
        }

        $date = $matches[0];

        return $this->setMonth($date['month'])
            ->setDay($date['day'])
            ->setYear($date['year']);
    }

    /**
     * Get date
     *
     * @return string
     */
    public function getDate()
    {
        return sprintf('%04d%02d%02d', $this->getYear(), $this->getMonth(), $this->getDay());
    }

    /**
     * Convert value to string
     *
     * @return string
     */
    public function __toString()
    {
        return $this->getDate();
    }

    /**
     * Returns date formatted according to given format
     *
     * @param string $format
     *
     * @return string
     */
    public function format($format)
    {
        $patterns = array(
            'day' => '[dDjlNSwzW]',
            'month' => '[FmMnt]',
            'year' => '[LoYy]',
            'time' => '[aABgGhHisueIOPTZ]',
            'full' => '[crU]'
        );

        foreach ($patterns as $key => $pattern) {
            if (empty($this->$key)) {
                $format = preg_replace("/$pattern\W*/", '', $format);
            }
        }

        $date = new \DateTime();
        $date->setDate($this->year, ($this->month ? $this->month : 1), ($this->day ? $this->day : 1));

        return $date->format($format);
    }

    /**
     * @return string
     */
    protected function getFormatFromDate()
    {
        $patterns = array(
            '/11\D21\D(1999|99)/',
            '/21\D11\D(1999|99)/',
            '/(1999|99)\D11\D21/',
        );
        $replacements = array('mdy', 'dmy', 'ymd');

        $date = new \DateTime();
        $date->setDate(1999, 11, 21);

        return preg_replace($patterns, $replacements, strftime('%x', $date->getTimestamp()));
    }
}
