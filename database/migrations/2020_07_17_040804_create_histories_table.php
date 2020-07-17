<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateHistoriesTable extends Migration
{
    protected $app;

    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        $schema = $this->getSchema();

        $schema->create('histories', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('command')->nullable();
            $table->text('description')->nullable();
            $table->text('result')->nullable();
            $table->text('output')->nullable();
            $table->timestamps();

            $table->index('command');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        //not implemented

        $schema = $this->getSchema();
        $schema->dropIfExists('histories');
    }

    protected function init() {
        $this->app = require __DIR__.'/../../bootstrap/app.php';

        $db     = $this->app->getLaravel()->make('db');
        $config = $this->app->getLaravel()->make('config');
        $connection = $db->connection($config['database']['default']);
        $this->connection = $connection;
    }

    protected function getSchema() {
        $this->init();
        return $this->getConnection()->getSchemaBuilder();
    }
}
