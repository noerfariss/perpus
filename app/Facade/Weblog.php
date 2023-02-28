<?php

namespace App\Facade;

use Illuminate\Support\Facades\Facade;

class Weblog extends Facade
{
    protected static function getFacadeAccessor()
    {
        return 'Weblog';
    }
}
