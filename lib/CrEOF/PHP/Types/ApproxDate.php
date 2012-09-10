<?php

namespace CrEOF\PHP\Types;

use CrEOF\Exception\InvalidValueException;

/**
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


    public function __construct()
    {
        switch (func_num_args()) {
            case 0:
                break;
            case 1:
                $this->setDate(func_get_arg(0));
                break;
            case 3:
                $this->setYear(func_get_arg(0))
                    ->setMonth(func_get_arg(1))
                    ->setDay(func_get_arg(2));
                break;
            default:
                throw new InvalidValueException('Invalid arguments');
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
            '/^(?P<year>\d{4})(?P<month>0[0-9]|1[012])(?P<day>0[0-9]|[12][0-9]|3[01])$/'
        );

        //$regex = '/^(?:(?P<month>0?[1-9]|1[012])[-\.\/](?:(?P<day>0?[1-9]|[12][0-9]|3[01])[-\.\/])?)?(?P<year>\d{2}|\d{4})$/';
        //$regex = '/(?J)^(?:(?P<year>\d{4})(?P<month>0[1-9]|1[012])(?P<day>0[1-9]|[12][0-9]|3[01]))|(?:(?P<month>0?[1-9]|1[012])[-\.\/](?:(?P<day>0?[1-9]|[12][0-9]|3[01])[-\.\/])?)?(?P<year>\d{2}|\d{4})$/';
        $matches = null;
        $matched = null;

        foreach ($patterns as $pattern) {
            $matched = preg_match($pattern, $date, $matches);
            if ($matched) {
                $this->setDay($matches['day'])
                    ->setMonth($matches['month'])
                    ->setYear($matches['year']);
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
     * @return string
     */
    protected function getDateFormat()
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
