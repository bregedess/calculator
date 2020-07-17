<?php
/**
 * Created by PhpStorm.
 * User: Desta-PC
 * Date: 7/13/2020
 * Time: 9:56 PM
 */

namespace Jakmall\Recruitment\Calculator\Commands;


use Illuminate\Console\Command;
use Jakmall\Recruitment\Calculator\History\HistoryDatabase;
use Jakmall\Recruitment\Calculator\History\HistoryFile;

abstract class StandardCalculator extends Command
{
    /**
     * @var string
     */
    protected $signature;

    /**
     * @var string
     */
    protected $description;

    /**
     * @var HistoryDatabase
     */
    protected $historyDatabase;

    /**
     * @var HistoryFile
     */
    protected $historyFile;

    /**
     * @return string
     */
    abstract protected function getCommandVerb(): string;

    /**
     * @return string
     */
    abstract protected function getCommandPassiveVerb(): string;

    /**
     * @return string
     */
    abstract protected function getOperator(): string;

    /**
     * @param int|float $number1
     * @param int|float $number2
     * @return mixed
     */
    abstract protected function calculate($number1, $number2);

    public function __construct(HistoryDatabase $historyDatabase, HistoryFile $historyFile)
    {
        $this->historyDatabase = $historyDatabase;
        $this->historyFile = $historyFile;

        $commandVerb = $this->getCommandVerb();

        $this->signature = sprintf(
            '%s {numbers* : The numbers to be %s}',
            $commandVerb,
            $this->getCommandPassiveVerb()
        );
        $this->description = sprintf('%s all given Numbers', ucfirst($commandVerb));

        parent::__construct();
    }

    public function handle(): void
    {
        $numbers = $this->getInput();
        $description = $this->generateCalculationDescription($numbers);
        $result = $this->calculateAll($numbers);
        $output = sprintf('%s = %s', $description, $result);

        $this->comment($output);

        $this->historyDatabase->log([
            $this->getCommandVerb(),
            $description,
            $result,
            $output
        ]);

        $this->historyFile->log([
            $this->getCommandVerb(),
            $description,
            $result,
            $output
        ]);
    }

    protected function getInput(): array
    {
        return $this->argument('numbers');
    }

    protected function generateCalculationDescription(array $numbers): string
    {
        $operator = $this->getOperator();
        $glue = sprintf(' %s ', $operator);

        return implode($glue, $numbers);
    }

    /**
     * @param array $numbers
     *
     * @return float|int
     */
    protected function calculateAll(array $numbers)
    {
        $number = array_pop($numbers);

        if (count($numbers) <= 0) {
            return $number;
        }

        return $this->calculate($this->calculateAll($numbers), $number);
    }

}
