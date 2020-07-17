<?php

namespace Jakmall\Recruitment\Calculator\History;

use Illuminate\Contracts\Container\Container;
use Illuminate\Database\Connectors\ConnectionFactory;
use Illuminate\Database\Console\Migrations\InstallCommand;
use Illuminate\Database\DatabaseManager;
use Illuminate\Database\Migrations\DatabaseMigrationRepository;
use Illuminate\Database\Migrations\MigrationCreator;
use Illuminate\Database\Migrations\Migrator;
use Illuminate\Events\Dispatcher;
use Illuminate\Filesystem\Filesystem;
use Illuminate\Contracts\Queue\Factory as QueueFactoryContract;
use Jakmall\Recruitment\Calculator\Commands\MigrateCommand;
use Jakmall\Recruitment\Calculator\Container\ContainerServiceProviderInterface;
use Jakmall\Recruitment\Calculator\History\Infrastructure\CommandHistoryManagerInterface;

class CommandHistoryServiceProvider implements ContainerServiceProviderInterface
{
    /**
     * @inheritDoc
     */
    public function register(Container $container): void
    {
        $this->registerEvents($container);
        $this->registerFilesBindings($container);
        $this->registerDatabaseServices($container);
        $this->registerMigrationRepository($container);
        $this->registerMigrator($container);
        $this->registerMigrateCommand($container);
        $this->registerMigrateInstallCommand($container);

        $container->bind(CommandHistoryManagerInterface::class,
            HistoryDatabase::class);

        $container->bind(CommandHistoryManagerInterface::class,
            HistoryFile::class);
    }

    /**
     * Register the service provider.
     * @param Container $container
     * @return void
     */
    public function registerEvents(Container $container)
    {
        $container->singleton('events', function ($container) {
            return (new Dispatcher($container))->setQueueResolver(function () use ($container) {
                return $container->make(QueueFactoryContract::class);
            });
        });
    }

    /**
     * Register container bindings for the application.
     * @param Container $container
     * @return void
     */
    protected function registerFilesBindings(Container $container)
    {
        $container->singleton('files', function () {
            return new Filesystem();
        });
    }

    /**
     * Register the primary database bindings.
     * @param Container $container
     * @return void
     */
    protected function registerDatabaseServices(Container $container)
    {
        // The connection factory is used to create the actual connection instances on
        // the database. We will inject the factory into the manager so that it may
        // make the connections while they are actually needed and not of before.
        $container->singleton('db.factory', function ($container) {
            return new ConnectionFactory($container);
        });

        // The database manager is used to resolve various connections, since multiple
        // connections might be managed. It also implements the connection resolver
        // interface which may be used by other components requiring connections.
        $container->singleton('db', function ($container) {
            return new DatabaseManager($container, $container['db.factory']);
        });

        $container->bind('db.connection', function ($container) {
            return $container['db']->connection();
        });
    }

    protected function registerMigrationRepository(Container $container)
    {
        $container->singleton('migration.repository', function ($container) {
            $table = 'calculator_migrations';

            return new DatabaseMigrationRepository($container['db'], $table);
        });
    }

    /**
     * Register the migrator service.
     * @param Container $container
     * @return void
     */
    protected function registerMigrator(Container $container)
    {
        // The migrator is responsible for actually running and rollback the migration
        // files in the application. We'll pass in our database connection resolver
        // so the migrator can resolve any of these connections when it needs to.
        $container->singleton('migrator', function ($container) {
            $repository = $container['migration.repository'];

            return new Migrator($repository, $container['db'], $container['files'], $container['events']);
        });

        $container->singleton('migration.creator', function ($container) {
            return new MigrationCreator($container['files']);
        });
    }

    /**
     * Register the command.
     * @param Container $container
     * @return void
     */
    protected function registerMigrateCommand(Container $container)
    {
        $container->singleton('command.migrate', function ($container) {
            return new MigrateCommand($container['migrator']);
        });
    }

    /**
     * Register the command.
     * @param Container $container
     * @return void
     */
    protected function registerMigrateInstallCommand(Container $container)
    {
        $container->singleton('command.migrate.install', function ($container) {
            return new InstallCommand($container['migration.repository']);
        });
    }
}
