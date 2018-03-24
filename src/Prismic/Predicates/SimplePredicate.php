<?php
namespace Gwsn\Prismic\Prismic\Predicates;


/**
 * Class SimplePredicate
 *
 * @package Prismic
 */
class SimplePredicate
{

    /**
     * @param string $name
     * @param string $fragment
     * @param array  $args
     * @return string
     */
    public static function query($name, $fragment, array $args = []): string
    {
        $query = "[[" . $name . "(";
        if ($name == "similar") {
            $query .= "\"" . $fragment . "\"";
        } else {
            $query .= $fragment;
        }
        foreach ($args as $arg) {
            $query .= ", " . self::serializeField($arg);
        }
        $query .= ")]]";
        return $query;
    }

    /**
     * @param mixed $value
     *
     * @return string
     */
    private static function serializeField($value): string
    {
        if (is_string($value)) {
            return "\"" . $value . "\"";
        }
        if (is_array($value)) {
            $str_array = [];
            foreach ($value as $elt) {
                array_push($str_array, self::serializeField($elt));
            }
            return "[" . join(", ", $str_array) . "]";
        }
        return (string) $value;
    }

}