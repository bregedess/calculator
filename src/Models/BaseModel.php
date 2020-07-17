<?php
/**
 * Created by PhpStorm.
 * User: Desta-PC
 * Date: 7/17/2020
 * Time: 11:30 AM
 */

namespace Jakmall\Recruitment\Calculator\Models;


use Illuminate\Database\Eloquent\Model;

class BaseModel extends Model
{
    protected $app;

    public function __construct() {
        $this->app = require __DIR__.'/../../bootstrap/app.php';

        $connection = $this->app->getLaravel()->make('db');
        $events     = $this->app->getLaravel()->make('events');

        Model::setConnectionResolver($connection);
        Model::setEventDispatcher($events);

        parent::__construct();
    }

}
