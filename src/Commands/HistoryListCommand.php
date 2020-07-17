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

class HistoryListCommand extends Command
{
    /**
     * @var string
     */
    protected $signature;

    /**
     * @var string
     */
    protected $description;

    protected $driver = [
        'database'  => HistoryDatabase::class,
        'file'      => HistoryFile::class,
    ];

    /**
     * @return string
     */
    protected function getCommandVerb(): string
    {
        return 'history:list';
    }

    protected function getDefaultStorage(): string
    {
        return 'database';
    }

    public function __construct()
    {
        $this->signature = sprintf(
            '%s 
            {--D|driver=%s  : Driver for storage connection}
            {commands?*     : Filter the history by commands}',
            $this->getCommandVerb(),
            $this->getDefaultStorage()
        );

        $this->description = sprintf('Show calculator history');

        parent::__construct();
    }

    public function handle(): void
    {
        $commands = $this->argument('commands');
        $driver = new $this->driver[$this->getDriver()];

        if (!empty($commands)) {
            $dataSet = $driver->findWithParam($commands);
        } else {
            $dataSet = $driver->findAll();
        }

        if (empty($dataSet)) {
            $this->comment('History is empty.');
        } else {
            $this->table(['No', 'Command', 'Description', 'Result', 'Output', 'Time'], $dataSet);
        }

    }

    protected function getDriver(): string
    {
        return $this->input->getOption('driver');
    }

}
