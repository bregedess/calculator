<?php

/**
 * Created by PhpStorm.
 * User: Desta-PC
 * Date: 7/16/2020
 * Time: 12:03 PM
 */

use Jakmall\Recruitment\Tests\TestCase;

class ExponentCommandTest extends TestCase
{
    /**
     *  @test
     */
    public function empty_argument()
    {
        $this->expectException(RuntimeException::class);

        $this->app->call('pow');

    }

    /**
     *  @test
     */
    public function exponent_command()
    {
        $this->app->call('pow', [
            'base' => 5,
            'exp' => 5,
        ]);

        $this->assertEquals("5 ^ 5 = 3125\n", $this->app->output());
    }

}
