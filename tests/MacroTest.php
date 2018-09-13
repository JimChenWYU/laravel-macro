<?php
/**
 * Created by PhpStorm.
 * User: lenovo
 * Date: 9/7/2018
 * Time: 1:32 PM
 */

namespace Tests;

use Illuminate\Support\Traits\Macroable;
use JimChen\Macro\Exceptions\ClassNotFoundException;
use JimChen\Macro\Exceptions\NotMacroableClassException;
use JimChen\Macro\Macro;

class MacroTest extends TestCase
{
    public function testNotClassException()
    {
        $this->expectException(ClassNotFoundException::class);
        $this->expectExceptionMessage('Foo\Bar\TargetClass not exist.');

        $app = $this->createApplication();
        $app['config'] = [
            'macro' => [
                'macros' => [
                    'Foo\Bar\TargetClass' => [
                        MacroClass::class,
                        MacroClass2::class,
                    ]
                ]
            ]
        ];

        (new Macro($app))->bind();
    }

    public function testNotMacroableException()
    {
        $this->expectException(NotMacroableClassException::class);
        $this->expectExceptionMessage('Tests\NotMacroableClass::macro not exist');

        $app = $this->createApplication();
        $app['config'] = [
            'macro' => [
                'macros' => [
                    'Tests\NotMacroableClass' => [
                        MacroClass::class
                    ]
                ]
            ]
        ];

        (new Macro($app))->bind();
    }

    public function testMacro()
    {
        $this->assertFalse(TargetClass::hasMacro('say'));
        $this->assertFalse(TargetClass::hasMacro('wave'));
        $this->assertFalse(TargetClass::hasMacro('recursion'));
        $this->assertFalse(TargetClass::hasMacro('foo'));
        $this->assertFalse(TargetClass::hasMacro('bar'));

        $app = $this->createApplication();
        $app['config'] = [
            'macro' => [
                'macros' => [
                    'Tests\TargetClass' => [
                        MacroClass::class
                    ]
                ]
            ]
        ];

        (new Macro($app))->bind();

        $this->assertTrue(TargetClass::hasMacro('say'));
        $this->assertTrue(TargetClass::hasMacro('wave'));
        $this->assertTrue(TargetClass::hasMacro('recursion'));
        $this->assertTrue(TargetClass::hasMacro('foo'));
        $this->assertFalse(TargetClass::hasMacro('bar'));


        $this->expectOutputString('Good Bye.');
        TargetClass::say('Good Bye.');

        $this->expectOutputString('Wave hand.');
        TargetClass::wave();

        $this->assertEquals(55, TargetClass::recursion());

        $this->assertEquals('bar', TargetClass::foo());
    }
}

class NotMacroableClass
{

}

class TargetClass
{
    use Macroable;
}

class MacroClass
{
    public function say($message = 'Hello')
    {
        ob_flush();
        echo $message;
    }

    public static function wave()
    {
        ob_flush();
        echo 'Wave hand.';
    }

    public function recursion($i = 1, $c = 1)
    {
        if ($i >= 55) {
            return $i;
        }

        $i += $c;

        return self::recursion($i, $c);
    }

    public static function foo()
    {
        return self::bar();
    }

    protected static function bar()
    {
        return 'bar';
    }
}

class MacroClass2
{

}