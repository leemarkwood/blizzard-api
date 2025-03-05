<?php

namespace LeeMarkWood\BlizzardApi\Facades;

use Illuminate\Support\Facades\Facade;

/**
 * @see \LeeMarkWood\BlizzardApi\BlizzardApi
 */
class BlizzardApi extends Facade
{
    protected static function getFacadeAccessor(): string
    {
        return \LeeMarkWood\BlizzardApi\BlizzardApi::class;
    }
}
