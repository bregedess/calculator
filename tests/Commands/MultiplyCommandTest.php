<?php

/**
 * Created by PhpStorm.
 * User: Desta-PC
 * Date: 7/13/2020
 * Time: 9:18 PM
 */

use Jakmall\Recruitment\Tests\TestCase;

class MultiplyCommandTest extends TestCase
{
    /**
     *  @test
     */
    public function empty_argument()
    {
        $this->expectException(RuntimeException::class);

        $this->app->call('multiply');

    }

    /**
     *  @test
     */
    public function multiply_command()
    {
        $this->app->call('multiply', ['numbers' => [5, 5]]);

        $this->assertEquals("5 * 5 = 25\n", $this->app->output());
    }

}
