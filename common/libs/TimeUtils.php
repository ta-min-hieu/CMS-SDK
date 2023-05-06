<?php

/**
 * Created by PhpStorm.
 *
 * Date: 18-Jun-16
 * Time: 11:14
 */

namespace common\libs;

use DateTime;
use InvalidArgumentException;

class TimeUtils {

    /**
     * Return the first day of the Week/Month/Quarter/Year that the
     * current/provided date falls within
     *
     * @param string   $period The period to find the first day of. ('year', 'quarter', 'month', 'week')
     * @param DateTime $date   The date to use instead of the current date
     *
     * @return DateTime
     * @throws InvalidArgumentException
     */
    public static function firstDayOf($period, $date = null) {
        $period = strtolower($period);
        $validPeriods = array('year', 'quarter', 'month', 'week');

        if (!in_array($period, $validPeriods))
            throw new InvalidArgumentException('Period must be one of: ' . implode(', ', $validPeriods));

        $newDate = ($date === null) ? new DateTime() : new DateTime($date);

        switch ($period) {
            case 'year':
                $newDate->modify('first day of january ' . $newDate->format('Y'));
                break;
            case 'quarter':
                $month = $newDate->format('n');

                if ($month < 4) {
                    $newDate->modify('first day of january ' . $newDate->format('Y'));
                } elseif ($month > 3 && $month < 7) {
                    $newDate->modify('first day of april ' . $newDate->format('Y'));
                } elseif ($month > 6 && $month < 10) {
                    $newDate->modify('first day of july ' . $newDate->format('Y'));
                } elseif ($month > 9) {
                    $newDate->modify('first day of october ' . $newDate->format('Y'));
                }
                break;
            case 'month':
                $newDate->modify('first day of this month');
                break;
            case 'week':
                $newDate->modify(($newDate->format('w') === '0') ? 'monday last week' : 'monday this week');
                break;
        }

        return $newDate;
    }

    /**
     * Return the last day of the Week/Month/Quarter/Year that the
     * current/provided date falls within
     *
     * @param string   $period The period to find the last day of. ('year', 'quarter', 'month', 'week')
     * @param DateTime $date   The date to use instead of the current date
     *
     * @return DateTime
     * @throws InvalidArgumentException
     */
    public static function lastDayOf($period, DateTime $date = null) {
        $period = strtolower($period);
        $validPeriods = array('year', 'quarter', 'month', 'week');

        if (!in_array($period, $validPeriods))
            throw new InvalidArgumentException('Period must be one of: ' . implode(', ', $validPeriods));

        $newDate = ($date === null) ? new DateTime() : clone $date;

        switch ($period) {
            case 'year':
                $newDate->modify('last day of december ' . $newDate->format('Y'));
                break;
            case 'quarter':
                $month = $newDate->format('n');

                if ($month < 4) {
                    $newDate->modify('last day of march ' . $newDate->format('Y'));
                } elseif ($month > 3 && $month < 7) {
                    $newDate->modify('last day of june ' . $newDate->format('Y'));
                } elseif ($month > 6 && $month < 10) {
                    $newDate->modify('last day of september ' . $newDate->format('Y'));
                } elseif ($month > 9) {
                    $newDate->modify('last day of december ' . $newDate->format('Y'));
                }
                break;
            case 'month':
                $newDate->modify('last day of this month');
                break;
            case 'week':
                $newDate->modify(($newDate->format('w') === '0') ? 'now' : 'sunday this week');
                break;
        }

        return $newDate;
    }

}
