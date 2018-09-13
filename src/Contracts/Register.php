<?php

/*
 * This file is part of the jimchen/laravel-macro.
 *
 * (c) JimChen <18219111672@163.com>
 *
 * This source file is subject to the MIT license that is bundled.
 */

namespace JimChen\Macro\Contracts;

interface Register
{
    /**
     * Binding.
     *
     * @return $this
     */
    public function bind();
}
