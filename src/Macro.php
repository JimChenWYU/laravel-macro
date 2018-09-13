<?php

/*
 * This file is part of the jimchen/laravel-macro.
 *
 * (c) JimChen <18219111672@163.com>
 *
 * This source file is subject to the MIT license that is bundled.
 */

namespace JimChen\Macro;

use ReflectionClass;
use ReflectionMethod;
use Illuminate\Support\Collection;
use JimChen\Macro\Contracts\Register;
use Illuminate\Contracts\Foundation\Application;
use JimChen\Macro\Exceptions\ClassNotFoundException;
use JimChen\Macro\Exceptions\NotMacroableClassException;

class Macro implements Register
{
    /**
     * @var Collection
     */
    protected $macros;

    /**
     * @var Application
     */
    protected $app;

    /**
     * Constructor.
     * @param Application $app
     */
    public function __construct(Application $app)
    {
        $this->app = $app;

        $this->macros = Collection::make($app['config']['macro']['macros']);
    }

    /**
     * Binding.
     */
    public function bind()
    {
        $this->macros->each(function ($macroClass, $class) {
            if (!class_exists($class)) {
                throw new ClassNotFoundException("$class not exist.");
            }

            if (!method_exists($class, 'macro')) {
                throw new NotMacroableClassException("$class::macro not exist.");
            }

            return Collection::make($macroClass)
                ->unique()
                ->map(function ($macro) {
                    $this->app->singleton($macro, $macro);

                    return (new ReflectionClass($macro))->getMethods(
                        ReflectionMethod::IS_PUBLIC
                    );
                })
                ->flatten(2)
                ->each(function (ReflectionMethod $method) use ($class) {
                    if ($method->isStatic()) {
                        $class::macro($method->getName(), [$method->class, $method->getName()]);
                    } else {
                        $class::macro($method->getName(), [$this->app->make($method->class), $method->getName()]);
                    }

                    return true;
                });
        });

        return $this;
    }
}
