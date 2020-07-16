<?php

/**
 * Created by PhpStorm.
 * User: Desta-PC
 * Date: 7/13/2020
 * Time: 9:18 PM
 */

use Jakmall\Recruitment\Tests\TestCase;

class DivideCommandTest extends TestCase
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
    public function divide_command()
    {
        $this->app->call('divide', ['numbers' => [50, 2, 5]]);

        $this->assertEquals("50 / 2 / 5 = 5\n", $this->app->output());
    }

    /**
     *  @test
     */
    public function divide_with_zero()
    {
        $this->expectException(Exception::class);
        $this->expectExceptionMessage('Cannot divide by zero');

        $this->app->call('divide', ['numbers' => [50, 0]]);
    }

}
