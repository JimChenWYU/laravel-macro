<?php

/*
 * This file is part of the jimchen/laravel-macro.
 *
 * (c) JimChen <18219111672@163.com>
 *
 * This source file is subject to the MIT license that is bundled.
 */

namespace JimChen\Macro;

use Illuminate\Support\ServiceProvider;

class LaravelMacroServiceProvider extends ServiceProvider
{
    /**
     * Macro Boot.
     */
    public function boot()
    {
        $this->publishes([
            __DIR__.'/config/macro.php' => config_path('macro.php'),
        ]);
    }

    /**
     * Macro Register.
     */
    public function register()
    {
        $macro = new Macro($this->app);

        $macro->bind();
    }
}
