<?php

namespace Jakmall\Recruitment\Tests;

use PHPUnit\Framework\TestCase as BaseTestCase;

abstract class TestCase extends BaseTestCase
{
    protected $app;

    public function __construct($name = null, array $data = [], $dataName = '')
    {
        parent::__construct($name, $data, $dataName);
        $this->app = $this->createApplication();

        $this->app->call('migrate');
    }

    /**
     * Creates the application.
     * @return \Illuminate\Console\Application
     */
    public function createApplication() {
        return require   __DIR__.'/../bootstrap/app.php';
    }
}
