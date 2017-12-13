<?php

namespace Yish\Imgur\Facades;

use Illuminate\Support\Facades\Facade;

class Upload extends Facade
{
    protected static function getFacadeAccessor()
    {
        return \Yish\Imgur\Upload::class;
    }
}