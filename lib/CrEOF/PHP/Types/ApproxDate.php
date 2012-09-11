<?php
/**
 * Copyright (C) 2012 Derek J. Lambert
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

namespace CrEOF\PHP\Types;

use CrEOF\Exception\InvalidValueException;

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
        if (!empty($date)) {
            $this->setDate(func_get_arg(0));
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

        if (empty($day)) {
            $this->day = null;
        } elseif ($day >= 1 && $day <= 31) {
            $this->day = $day;
        } else {
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

        if (empty($month)) {
            $this->month = null;
        } elseif ($month >=1 && $month <= 12) {
            $this->month = $month;
        } else {
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

        if (empty($year)) {
            $this->year = null;
        } elseif (strlen($year) == 2) {
            $this->year = date('y') + 30 < $year ? "19$year" : "20$year";
        } elseif (strlen($year) == 4) {
            $this->year = $year;
        } else {
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
        $patterns = array(
            '/^(?:(?P<month>0?[1-9]|1[012])[-\.\/](?:(?P<day>0?[1-9]|[12][0-9]|3[01])[-\.\/])?)?(?P<year>\d{2}|\d{4})$/',
            '/^(?P<year>\d{4})(?P<month>0[0-9]|1[012])(?P<day>0[0-9]|[12][0-9]|3[01])$/',
            '/^(?P<year>\d{4})-(?P<month>0[0-9]|1[012])(?:-(?P<day>0[0-9]|[12][0-9]|3[01]))?$/'
        );

        $matches = null;
        $matched = null;

        foreach ($patterns as $pattern) {
            $matched = preg_match($pattern, $date, $matches);
            if ($matched) {
                foreach (array('year', 'month', 'day') as $piece) {
                    if (isset($matches[$piece])) {
                        $this->{"set$piece"}($matches[$piece]);
                    }
                }
                break;
            }
        }

        if (!$matched) {
            throw new InvalidValueException("Invalid date value: $date");
        }

        return $this;
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
