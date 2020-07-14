<?php
/**
 * Created by PhpStorm.
 * User: Desta-PC
 * Date: 7/13/2020
 * Time: 8:07 PM
 */

namespace Jakmall\Recruitment\Calculator\Commands;

class SubtractCommand extends StandardCalculator
{
    protected function getCommandVerb(): string
    {
        return 'subtract';
    }

    protected function getCommandPassiveVerb(): string
    {
        return 'subtracted';
    }

    protected function getOperator(): string
    {
        return '-';
    }

    /**
     * @param int|float $number1
     * @param int|float $number2
     *
     * @return int|float
     */
    protected function calculate($number1, $number2)
    {
        return $number1 - $number2;
    }

}
