<?php
/**
 * Created by PhpStorm.
 * User: Desta-PC
 * Date: 7/14/2020
 * Time: 11:26 PM
 */

use Illuminate\Console\Application;
use Illuminate\Container\Container;
use Illuminate\Events\Dispatcher;

require_once __DIR__.'/../vendor/autoload.php';

$container = new Container();
$dispatcher = new Dispatcher();

$app = new Application($container, $dispatcher, '0.6');
$app->setName('Calculator');

$appConfig = require __DIR__.'/../config/app.php';
$providers = $appConfig['providers'];

foreach ($providers as $provider) {
    $container->make($provider)->register($container);
}

$container['config']->set('database', require __DIR__.'/../config/database.php');

$commands = require __DIR__.'/../commands.php';
$commands = collect($commands)
    ->map(
        function ($command) use ($app) {
            return $app->getLaravel()->make($command);
        }
    )
    ->all()
;

$app->addCommands($commands);

return $app;
