<?php

if (!function_exists('addPercentTo')) {
    /**
     * @param float $number
     * @param float $percents
     * @param int $precision
     * @return float
     */
    function addPercentTo(float $number, float $percents, $precision = 2): float
    {
        $percentOf = $number * ($percents / 100);

        return round($number + $percentOf, $precision);
    }
}

if (!function_exists('isActiveRoute')) {
    /**
     * @param $name
     * @param string $class
     * @return bool|string
     */
    function isActiveRoute($name, $class = 'active') {
        return request()->routeIs($name) ? $class : null;
    }
}

if (!function_exists('numberIsPositive')) {
    /**
     * @param float $number
     * @return bool
     */
    function numberIsPositive(float $number): bool
    {
        return $number >= 0;
    }
}
