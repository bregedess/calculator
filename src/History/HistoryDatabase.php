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

class HistoryDatabase implements CommandHistoryManagerInterface
{
    /**
     * Returns array of command history.
     *
     * @return array
     */
    public function findAll(): array
    {
        return History::selectRaw(
            "   id, 
                UPPER(SUBSTR(command, 1, 1)) || SUBSTR(command, 2) as command,
                description, 
                result, 
                output, 
                created_at as time")
            ->get()
            ->toArray();
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
        $history = new History();
        $history->command       = $command[0];
        $history->description   = $command[1];
        $history->result        = $command[2];
        $history->output        = $command[3];

        if ($history->save()) {
            return true;
        }

        return false;
    }

    /**
     * Clear all data from storage.
     *
     * @return bool Returns true if all data is cleared successfully, false otherwise.
     */
    public function clearAll():bool
    {
        if (History::truncate()) {
            return true;
        }

        return false;
    }

    public function findWithParam(array $params) {
        return History::selectRaw(
            "   id, 
                UPPER(SUBSTR(command, 1, 1)) || SUBSTR(command, 2) as command, 
                description, 
                result, 
                output, 
                created_at as time")
            ->whereIn('command', $params)
            ->get()
            ->toArray();
    }

    public function find($id) {
        return History::selectRaw(
            "   id, 
                UPPER(SUBSTR(command, 1, 1)) || SUBSTR(command, 2) as command, 
                description, 
                result, 
                output, 
                created_at as time")
            ->where('id', $id)
            ->first();
    }

    public function delete($id) {
        return History::selectRaw(
            "   id, 
                UPPER(SUBSTR(command, 1, 1)) || SUBSTR(command, 2) as command, 
                description, 
                result, 
                output, 
                created_at as time")
            ->where('id', $id)
            ->delete();
    }

}
