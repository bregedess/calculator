<?php
/**
 * Created by PhpStorm.
 * User: Desta-PC
 * Date: 7/16/2020
 * Time: 10:43 AM
 */

namespace Jakmall\Recruitment\Calculator\Commands;


class DivideCommand extends StandardCalculator
{
    protected function getCommandVerb(): string
    {
        return 'divide';
    }

    protected function getCommandPassiveVerb(): string
    {
        return 'divided';
    }

    protected function getOperator(): string
    {
        return '/';
    }

    /**
     * @param int|float $number1
     * @param int|float $number2
     *
     * @throws \Exception
     *
     * @return int|float
     */
    protected function calculate($number1, $number2)
    {
        if ($number2 == 0) {
            throw new \Exception('Cannot divide by zero');
        }

        return $number1 / $number2;
    }

}
