<?php
namespace Gwsn\Prismic\Prismic\Predicates;

use DateTime;

/**
 * A set of helpers to build predicates
 * @package Prismic
 */
class Predicates {

    /**
     * @param string $fragment
     * @param string $value
     *
     * @return string
     */
    public static function at($fragment, $value): string
    {
        return SimplePredicate::query("at", $fragment, array($value));
    }

    /**
     * @param string $fragment
     * @param string $value
     *
     * @return string
     */
    public static function not($fragment, $value): string
    {
        return SimplePredicate::query("not", $fragment, array($value));
    }

    /**
     * @param string $fragment
     * @param string $values
     *
     * @return string
     */
    public static function any($fragment, $values): string
    {
        return SimplePredicate::query("any", $fragment, array($values));
    }

    /**
     * @param string $fragment
     * @param string $values
     *
     * @return string
     */
    public static function in($fragment, $values) {
        return SimplePredicate::query("in", $fragment, array($values));
    }

    /**
     * @param string $fragment
     *
     * @return string
     */
    public static function has($fragment): string
    {
        return SimplePredicate::query("has", $fragment);
    }

    /**
     * @param string $fragment
     *
     * @return string
     */
    public static function missing($fragment): string
    {
        return SimplePredicate::query("missing", $fragment);
    }

    /**
     * @param string $fragment
     * @param string $value
     *
     * @return string
     */
    public static function fulltext($fragment, $value): string
    {
        return SimplePredicate::query("fulltext", $fragment, array($value));
    }

    /**
     * @param string $documentId
     * @param int    $maxResults
     *
     * @return string
     */
    public static function similar($documentId, $maxResults): string
    {
        return SimplePredicate::query("similar", $documentId, array($maxResults));
    }

    /**
     * @param string $fragment
     * @param int    $lowerBound
     *
     * @return string
     */
    public static function lt($fragment, $lowerBound): string
    {
        return SimplePredicate::query("number.lt", $fragment, array($lowerBound));
    }

    /**
     * @param string $fragment
     * @param int    $upperBound
     *
     * @return string
     */
    public static function gt($fragment, $upperBound): string
    {
        return SimplePredicate::query("number.gt", $fragment, array($upperBound));
    }

    /**
     * @param string $fragment
     * @param int    $lowerBound
     * @param int    $upperBound
     *
     * @return string
     */
    public static function inRange($fragment, $lowerBound, $upperBound): string
    {
        return SimplePredicate::query("number.inRange", $fragment, array($lowerBound, $upperBound));
    }

    /**
     * @param string       $fragment
     * @param DateTime|int $before
     *
     * @return string
     */
    public static function dateBefore($fragment, $before): string
    {
        if ($before instanceof DateTime) {
            $before = $before->getTimestamp() * 1000;
        }
        return SimplePredicate::query("date.before", $fragment, array($before));
    }

    /**
     * @param string       $fragment
     * @param DateTime|int $after
     *
     * @return string
     */
    public static function dateAfter($fragment, $after): string
    {
        if ($after instanceof DateTime) {
            $after = $after->getTimestamp() * 1000;
        }
        return SimplePredicate::query("date.after", $fragment, array($after));
    }

    /**
     * @param string       $fragment
     * @param DateTime|int $before
     * @param DateTime|int $after
     *
     * @return string
     */
    public static function dateBetween($fragment, $before, $after): string
    {
        if ($before instanceof DateTime) {
            $before = $before->getTimestamp() * 1000;
        }
        if ($after instanceof DateTime) {
            $after = $after->getTimestamp() * 1000;
        }
        return SimplePredicate::query("date.between", $fragment, array($before, $after));
    }

    /**
     * @param string $fragment
     * @param string $day
     *
     * @return string
     */
    public static function dayOfMonth($fragment, $day): string
    {
        return SimplePredicate::query("date.day-of-month", $fragment, array($day));
    }

    /**
     * @param string $fragment
     * @param string $day
     *
     * @return string
     */
    public static function dayOfMonthBefore($fragment, $day): string
    {
        return SimplePredicate::query("date.day-of-month-before", $fragment, array($day));
    }

    /**
     * @param string $fragment
     * @param string $day
     *
     * @return string
     */
    public static function dayOfMonthAfter($fragment, $day): string
    {
        return SimplePredicate::query("date.day-of-month-after", $fragment, array($day));
    }

    /**
     * @param string $fragment
     * @param string $day
     *
     * @return string
     */
    public static function dayOfWeek($fragment, $day): string
    {
        return SimplePredicate::query("date.day-of-week", $fragment, array($day));
    }

    /**
     * @param string $fragment
     * @param string $day
     *
     * @return string
     */
    public static function dayOfWeekBefore($fragment, $day): string
    {
        return SimplePredicate::query("date.day-of-week-before", $fragment, array($day));
    }

    /**
     * @param string $fragment
     * @param string $day
     *
     * @return string
     */
    public static function dayOfWeekAfter($fragment, $day): string
    {
        return SimplePredicate::query("date.day-of-week-after", $fragment, array($day));
    }

    /**
     * @param string $fragment
     * @param string $month
     *
     * @return string
     */
    public static function month($fragment, $month): string
    {
        return SimplePredicate::query("date.month", $fragment, array($month));
    }

    /**
     * @param string $fragment
     * @param string $month
     *
     * @return string
     */
    public static function monthBefore($fragment, $month): string
    {
        return SimplePredicate::query("date.month-before", $fragment, array($month));
    }

    /**
     * @param string $fragment
     * @param string $month
     *
     * @return string
     */
    public static function monthAfter($fragment, $month): string
    {
        return SimplePredicate::query("date.month-after", $fragment, array($month));
    }

    /**
     * @param string $fragment
     * @param string $year
     *
     * @return string
     */
    public static function year($fragment, $year): string
    {
        return SimplePredicate::query("date.year", $fragment, array($year));
    }

    /**
     * @param string $fragment
     * @param string $hour
     *
     * @return string
     */
    public static function hour($fragment, $hour): string
    {
        return SimplePredicate::query("date.hour", $fragment, array($hour));
    }

    /**
     * @param string $fragment
     * @param string $hour
     *
     * @return string
     */
    public static function hourBefore($fragment, $hour): string
    {
        return SimplePredicate::query("date.hour-before", $fragment, array($hour));
    }

    /**
     * @param string $fragment
     * @param string $hour
     *
     * @return string
     */
    public static function hourAfter($fragment, $hour): string
    {
        return SimplePredicate::query("date.hour-after", $fragment, array($hour));
    }

    /**
     * @param string $fragment
     * @param string $latitude
     * @param string $longitude
     * @param string $radius
     *
     * @return string
     */
    public static function near($fragment, $latitude, $longitude, $radius): string
    {
        return SimplePredicate::query("geopoint.near", $fragment, array($latitude, $longitude, $radius));
    }

}
