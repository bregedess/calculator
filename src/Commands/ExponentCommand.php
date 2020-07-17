<?php
/**
 * Created by PhpStorm.
 * User: Desta-PC
 * Date: 7/16/2020
 * Time: 12:05 PM
 */
namespace Jakmall\Recruitment\Calculator\Commands;


use Illuminate\Console\Command;
use Jakmall\Recruitment\Calculator\History\HistoryDatabase;
use Jakmall\Recruitment\Calculator\History\HistoryFile;

class ExponentCommand extends Command
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
    protected function getCommandVerb(): string
    {
        return 'pow';
    }

    /**
     * @return string
     */
    protected function getOperator(): string
    {
        return '^';
    }

    /**
     * @param int|float $base
     * @param int|float $exp
     * @return mixed
     */
    protected function calculate($base, $exp)
    {
        return pow($base, $exp);
    }

    public function __construct(HistoryDatabase $historyDatabase, HistoryFile $historyFile)
    {
        $this->historyDatabase = $historyDatabase;
        $this->historyFile = $historyFile;

        $commandVerb = $this->getCommandVerb();

        $this->signature = sprintf(
            '%s 
            {base : The base number}
            {exp : The exponent number}',
            $this->getCommandVerb()
        );

        $this->description = sprintf('%s the given Numbers', ucfirst($commandVerb));

        parent::__construct();
    }

    public function handle(): void
    {
        $base = $this->getBase();
        $exp = $this->getExponent();
        $description = $this->generateCalculationDescription($base, $exp);
        $result = $this->calculate($base, $exp);
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

    protected function getBase(): string
    {
        return $this->argument('base');
    }

    protected function getExponent(): string
    {
        return $this->argument('exp');
    }

    protected function generateCalculationDescription($base, $exp): string
    {
        $operator = $this->getOperator();
        $glue = sprintf(' %s ', $operator);

        return $base. $glue. $exp;
    }

}
