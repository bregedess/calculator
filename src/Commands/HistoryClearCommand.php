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

class HistoryClearCommand extends Command
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
        return 'history:clear';
    }

    public function __construct()
    {
        $this->signature = sprintf(
            '%s',
            $this->getCommandVerb()
        );

        $this->description = sprintf('Clear calculator history');

        parent::__construct();
    }

    public function handle(): void
    {
        foreach ($this->driver as $key => $driver) {
            $driver = new $this->driver[$key];

            if ($driver->clearAll()) {
                $this->comment('History cleared!');
            }
        }
    }

}
