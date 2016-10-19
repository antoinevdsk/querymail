<?php

namespace Service;

class Date
{

    /**
     * Check if date time is a valid Mysql datetime format
     * @param string $sDateTime
     */
    public static function isValidDateTime($sDateTime)
    {
        return (date('Y-m-d H:i:s', strtotime($sDateTime)) == $sDateTime);
    }

    /**
     * Check if date time is a valid Mysql date format
     * @param string $sDate
     */
    public static function isValidDate($sDate)
    {
        return (date('Y-m-d', strtotime($sDate)) == $sDate);
    }

    /**
     * End of month of a given date
     * @param string $sDate
     */
    public static function endOfMonth($sDate)
    {
        return date('Y-m-d', strtotime('-1 day', strtotime('+1 month', strtotime($sDate))));
    }

    /**
     * Check if date value is empty (like 0000-00-00 for example)
     */
    public static function isEmpty($sDate)
    {
        if ($sDate == '0000-00-00' || $sDate == '0000-00-00 00:00:00' || $sDate == '' || $sDate == null) {
            return true;
        } else {
            return false;
        }
    }

    /**
     * Wrapper of date() native function
     * @param string $sFormat
     * @param integer $iTime
     */
    public static function phpdate($sFormat, $iTime = null)
    {
        if ($iTime == null) {
            return date($sFormat);
        } else {
            return date($sFormat, $iTime);
        }
    }

    /**
     * Wrapper of time() native function
     */
    public static function phptime()
    {
        return time();
    }

    /**
     * Produces a now() datetime for mysql
     * @param $time integer
     */
    public static function mySQLDateTime($time = null)
    {
        if (is_null($time)) $time = time();
        return \Date::forge($time)->format("%Y-%m-%d %H:%M:%S");
    }

    /**
     * Produces a curdate() date for mysql
     * @param $time integer
     */
    public static function mySQLDate($time = null)
    {
        if (is_null($time)) $time = time();
        return \Date::forge($time)->format("%Y-%m-%d");
    }

    /**
     * Produces a time() time for mysql
     * @param $time integer
     */
    public static function mySQLTime($time = null)
    {
        if (is_null($time)) $time = time();
        return \Date::forge($time)->format("%H:%M:%S");
    }

    /**
     * Produces a now() datetime for mysql
     * @param $sFormat string
     */
    public static function mySQLDateTimeFromString($sFormat, $time = null)
    {
        if (is_null($time)) $time = time();
        return self::mySQLDateTime(self::strtotime($sFormat, $time));
    }

    /**
     * Produces a curdate() datetime for mysql
     * @param $sFormat string
     */
    public static function mySQLDateFromString($sFormat, $time = null)
    {
        if (is_null($time)) $time = time();
        return self::mySQLDate(self::strtotime($sFormat, $time));
    }

    /**
     * Return time by reading shift syntax
     *
     * @param integer $iPeriod Period (ex a number of days)
     * @param integer $iPeriodTypeId Period type (ex 1 = DAY)
     * @return integer
     */
    public static function getTimeShift($iPeriod, $iPeriodTypeId, $iCurrentTime = false)
    {
        $time = null;

        if (!$iCurrentTime) {
            $iCurrentTime = time();
        }

        switch ($iPeriodTypeId) {
            case PERIOD_TYPE_DAY:
                $time = strtotime('+' . $iPeriod . 'day', $iCurrentTime);
                break;
            case PERIOD_TYPE_WEEK:
                $time = strtotime('+' . $iPeriod . 'week', $iCurrentTime);
                break;
            case PERIOD_TYPE_MONTH:
                $time = strtotime('+' . $iPeriod . 'month', $iCurrentTime);
                break;
            case PERIOD_TYPE_YEAR:
                $time = strtotime('+' . $iPeriod . 'year', $iCurrentTime);
                break;
        }
        return $time;
    }

    /**
     * Check if given date was yesterday
     * @param string $sDate
     * @return boolean
     */
    public static function isYesterday($sDate)
    {
        if (strlen($sDate) > 10) {
            $sDate = substr($sDate, 0, 10);
        }
        return date('Y-m-d', strtotime('-1 day')) == $sDate;
    }

    /**
     * Check if given date is in the current month
     * @param string $sDate
     * @return boolean
     */
    public static function isSameMonth($sDate)
    {
        $iCurrentMonth = date('m');
        $iGivenMonth = date('m', strtotime($sDate));
        return ($iCurrentMonth == $iGivenMonth);
    }

    /**
     * Create time by reading string
     * @param string $sString
     * @param integer $iTime
     */
    public static function strtotime($sString, $iTime = null)
    {
        if ($iTime == null) $iTime = time();
        return strtotime($sString, $iTime);
    }

    /**
     * Return a range of days between 2 dates
     * @param string $sStartDate
     * @param string $sEndDate
     */
    public static function dateRange($sStartDate, $sEndDate)
    {
        $iCurrentDate = strtotime($sStartDate);
        $iEndDate = strtotime($sEndDate);
        $aDays[] = date("Y-m-d", $iCurrentDate);
        while ($iCurrentDate < $iEndDate) {
            $iCurrentDate = strtotime("+1 day", $iCurrentDate);
            $aDays[] = date("Y-m-d", $iCurrentDate);
        }
        return $aDays;
    }

    /**
     * Return a range of week between 2 dates
     * @param string $sStartDate
     * @param string $sEndDate
     */
    public static function weekRange($sStartDate, $sEndDate)
    {
        $iCurrentDate = strtotime($sStartDate);
        $iEndDate = strtotime($sEndDate);
        while ($iCurrentDate <= $iEndDate) {
            $aDays[] = date("Y \W", strtotime('+3 day', $iCurrentDate)) . self::weekNumberWithoutZero($iCurrentDate);
            $iCurrentDate = strtotime("+1 week", $iCurrentDate);
        }
        $iCurrentDate = strtotime($sEndDate);
        $sDate = date("Y \W", $iCurrentDate) . self::weekNumberWithoutZero($iCurrentDate);
        if (!in_array($sDate, $aDays)) {
            $aDays[] = $sDate;
        }
        return $aDays;
    }

    public static function weekNumberWithoutZero($iTime)
    {
        $sWeekNumber = date("W", $iTime);
        if (substr($sWeekNumber, 0, 1) == '0') {
            $sWeekNumber = substr($sWeekNumber, 1);
        }
        return $sWeekNumber;
    }

    /**
     * Return a range of week between 2 dates
     * @param string $sStartDate
     * @param string $sEndDate
     */
    public static function monthRange($sStartDate, $sEndDate)
    {
        $iCurrentDate = strtotime($sStartDate);
        $iEndDate = strtotime($sEndDate);
        while ($iCurrentDate <= $iEndDate) {
            $aDays[] = date("Y-m", $iCurrentDate);
            $iCurrentDate = strtotime("+1 month", $iCurrentDate);
        }
        return $aDays;
    }

    /**
     * Différence entre 2 dates
     * @param string $sStartDate
     * @param string $sEndDate
     */
    public static function dateDiff($sStartDate, $sEndDate)
    {
        $iStartDate = strtotime($sStartDate);
        $iEndDate = strtotime($sEndDate);
        return $iEndDate - $iStartDate;
    }

    /**
     * return array (start, end) date from a given month date
     */
    public static function getRangeFromMonthDate($sDateMonth)
    {
        $oDate = new Datetime($sDateMonth);

        $oDate->modify('first day of this month');
        $sStartDate = $oDate->format('Y-m-d');

        $oDate->modify('last day of this month');
        $sEndDate = $oDate->format('Y-m-d');

        return array($sStartDate, $sEndDate);
    }

    /**
     * return the date month formated like yyyy month
     */
    public static function getDateMonthLabel($sMonthDate)
    {
        $oDate = new Datetime($sMonthDate);
        return $oDate->format('Y-m');
    }

    /**
     * Get the number of days in the current month
     *
     * @return int
     */
    public static function getNumberOfDaysInMonth()
    {
        return \Date::phpdate('t');
    }
}
