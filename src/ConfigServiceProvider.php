<?php

/**
 * Created by PhpStorm.
 * User: Desta-PC
 * Date: 7/17/2020
 * Time: 12:02 AM
 */

namespace Jakmall\Recruitment\Calculator;


use Illuminate\Contracts\Container\Container;
use Illuminate\Config\Repository as ConfigRepository;
use Jakmall\Recruitment\Calculator\Container\ContainerServiceProviderInterface;

class ConfigServiceProvider implements ContainerServiceProviderInterface
{
    /**
     * @inheritDoc
     */
    public function register(Container $container): void
    {
        $this->registerConfigBindings($container);
    }

    /**
     * Register container bindings for the application.
     * @param Container $container
     * @return void
     */
    protected function registerConfigBindings(Container $container)
    {
        $container->singleton('config', function () {
            return new ConfigRepository;
        });
    }
}
