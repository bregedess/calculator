#!/usr/bin/env php
<?php

use Symfony\Component\Console\Input\ArgvInput;
use Symfony\Component\Console\Output\ConsoleOutput;

try {
    require_once __DIR__.'/bootstrap/app.php';

    $app->run(new ArgvInput(), new ConsoleOutput());
} catch (Throwable $e) {
    die($e->getMessage());
}
