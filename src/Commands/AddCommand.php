<?php

namespace Jakmall\Recruitment\Calculator\Commands;

class AddCommand extends StandardCalculator
{
    protected function getCommandVerb(): string
    {
        return 'add';
    }

    protected function getCommandPassiveVerb(): string
    {
        return 'added';
    }

    protected function getOperator(): string
    {
        return '+';
    }

    /**
     * @param int|float $number1
     * @param int|float $number2
     *
     * @return int|float
     */
    protected function calculate($number1, $number2)
    {
        return $number1 + $number2;
    }
}