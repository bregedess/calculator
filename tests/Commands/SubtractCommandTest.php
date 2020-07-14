<?php

/**
 * Created by PhpStorm.
 * User: Desta-PC
 * Date: 7/13/2020
 * Time: 9:18 PM
 */

use Jakmall\Recruitment\Tests\TestCase;

class SubtractCommandTest extends TestCase
{
    /**
     *  @test
     */
    public function empty_argument()
    {
        $this->expectException(RuntimeException::class);

        $this->app->call('subtract');

    }

    /**
     *  @test
     */
    public function subtract_command()
    {
        $this->app->call('subtract', ['numbers' => [1, 2, 3]]);

        $this->assertEquals("1 - 2 - 3 = -4\n", $this->app->output());
    }

}
