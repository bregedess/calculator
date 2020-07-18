<?php
/**
 * Created by PhpStorm.
 * User: Desta-PC
 * Date: 7/17/2020
 * Time: 11:01 AM
 */

namespace Jakmall\Recruitment\Calculator\History;


use Jakmall\Recruitment\Calculator\History\Infrastructure\CommandHistoryManagerInterface;
use Jakmall\Recruitment\Calculator\Models\History;

class HistoryFile implements CommandHistoryManagerInterface
{

    protected $pathStorage = __DIR__ . '/../../storage/app/';

    protected $file = 'histories.json';

    /**
     * @return string
     */
    public function getFile(): string
    {
        return $this->file;
    }

    /**
     * @return string
     */
    public function getPathStorage(): string
    {
        return $this->pathStorage;
    }

    /**
     * Returns array of command history.
     *
     * @return array
     */
    public function findAll(): array
    {
        $histories = $this->checkAndGetFile();
        $histories = collect(json_decode($histories, true));
        $histories = $this->transformer($histories);

        return $histories->toArray();
    }

    /**
     * Log command data to storage.
     *
     * @param mixed $command The command to log.
     *
     * @return bool Returns true when command is logged successfully, false otherwise.
     */
    public function log($command): bool
    {
        $histories = $this->checkAndGetFile();
        $histories = collect(json_decode($histories, true));

        $lastId = $histories->last()['id'] ?? 0;
        $lastId = $lastId + 1;

        $histories->push([
            'id' => $lastId,
            'command' => $command[0],
            'description' => $command[1],
            'result' => $command[2],
            'output' => $command[3],
            'created_at' => date('Y-m-d H:i:s'),
        ]);

        $histories = $histories->toJson(JSON_PRETTY_PRINT);

        file_put_contents($this->getPathStorage().$this->getFile(), $histories);

        return true;
    }

    /**
     * Clear all data from storage.
     *
     * @return bool Returns true if all data is cleared successfully, false otherwise.
     */
    public function clearAll():bool
    {
        file_put_contents($this->getPathStorage().$this->getFile(), []);

        return true;
    }

    public function findWithParam(array $params) {
        $histories = $this->checkAndGetFile();
        $histories = collect(json_decode($histories, true));

        $histories = $histories->whereIn('command', $params);
        $histories = $this->transformer($histories);

        return $histories->toArray();
    }

    public function delete($id) {
        $histories = $this->checkAndGetFile();
        $histories = collect(json_decode($histories, true));

        $history = $histories->where('id', $id);
        $histories->forget($history->keys()[0]);
        $histories = $histories->values()->toJson(JSON_PRETTY_PRINT);

        file_put_contents($this->getPathStorage().$this->getFile(), $histories);

        return true;
    }

    protected function transformer($histories) {
        $histories = $histories->map(function ($item) {
            $item['command'] = ucfirst($item['command']);

            return $item;
        });

        return $histories;
    }

    protected function checkAndGetFile() {
        if (file_exists($this->getPathStorage().$this->getFile())) {
            return file_get_contents($this->getPathStorage().$this->getFile());
        } else {
            return file_put_contents($this->getPathStorage().$this->getFile(), []);
        }
    }

}
