<?php

/**
 * Created by PhpStorm.
 * User: Desta-PC
 * Date: 7/13/2020
 * Time: 9:18 PM
 */

use Jakmall\Recruitment\Tests\TestCase;

class AddCommandTest extends TestCase
{
    /**
     *  @test
     */
    public function empty_argument()
    {
        $this->expectException(RuntimeException::class);

        $this->app->call('add');

    }

    /**
     *  @test
     */
    public function add_command()
    {
        $this->app->call('add', ['numbers' => [1, 2, 3]]);

        $this->assertEquals("1 + 2 + 3 = 6\n", $this->app->output());
    }

}
