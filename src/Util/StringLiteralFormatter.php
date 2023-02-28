<?php

namespace GraphQL\Util;

/**
 * Class StringLiteralFormatter
 *
 * @package GraphQL\Util
 */
class StringLiteralFormatter
{
    /**
     * Converts the value provided to the equivalent RHS value to be put in a file declaration
     *
     * @param string|int|float|bool $value
     *
     * @return string
     */
    public static function formatValueForRHS($value): string
    {
        if (is_string($value)) {
            if (!static::isVariable($value)) {
                $value = str_replace('"', '\"', $value);
                if (strpos($value, "\n") !== false) {
                    $value = '"""' . $value . '"""';
                } else {
                    $value = "\"$value\"";
                }
            }
        } elseif (is_bool($value)) {
            if ($value) {
                $value = 'true';
            } else {
                $value = 'false';
            }
        } elseif ($value === null) {
            $value = 'null';
        } else {
            $value = (string) $value;
        }

        return $value;
    }

    /**
     * Treat string value as variable if it matches variable regex
     *
     * @param string $value
     *
     * @return bool
     */
    private static function isVariable(string $value): bool {
        return preg_match('/^\$[_A-Za-z][_0-9A-Za-z]*$/', $value);
    }

    /**
     * @param array $array
     *
     * @return string
     */
    public static function formatArrayForGQLQuery(array $array): string
    {
        $arrString = '{';
        $first = true;
        foreach ($array as $name => $element) {
            if ($first) {
                $first = false;
            } else {
                $arrString .= ', ';
            }
            if (is_array($element)) {
                $arrString .= $name . ':';
                if (array_keys($element) !== range(0, count($element) - 1)) {
                    $arrString .= static::formatAssociativeArray($element);
                } else {
                    $arrString .= static::formatSequentialArray($element);
                }
            } else {
                $arrString .= $name . ':' . static::formatValueForRHS($element);
            }
        }
        $arrString .= '}';

        return $arrString;
    }

    /**
     * @param $array
     * @return string
     */
    public static function formatSequentialArray($array): string
    {
        $arrString = '[';
        foreach ($array as $value) {
            $arrString .= static::formatAssociativeArray($value);
        }
        $arrString .= ']';
        return $arrString;
    }

    /**
     * @param $array
     * @return string
     */
    public static function formatAssociativeArray($array): string
    {
        $arrString = '{';
        $first = true;
        foreach ($array as $key => $val) {
            if ($first) {
                $first = false;
            } else {
                $arrString .= ', ';
            }
            if (is_array($val)) {
                $arrString .= $key . ':';
                if (array_keys($val) !== range(0, count($val) - 1)) {
                    $arrString .= static::formatAssociativeArray($val);
                } else {
                    $arrString .= static::formatSequentialArray($val);
                }
            } else {
                $arrString .= $key . ':' . static::formatValueForRHS($val);
            }
        }
        $arrString .= '}';
        return $arrString;
    }

    /**
     * @param string $stringValue
     *
     * @return string
     */
    public static function formatUpperCamelCase(string $stringValue): string
    {
        if (strpos($stringValue, '_') === false) {
            return ucfirst($stringValue);
        }

        return str_replace('_', '', ucwords($stringValue, '_'));
    }

    /**
     * @param string $stringValue
     *
     * @return string
     */
    public static function formatLowerCamelCase(string $stringValue): string
    {
        return lcfirst(static::formatUpperCamelCase($stringValue));
    }
}
