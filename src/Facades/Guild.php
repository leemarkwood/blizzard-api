<?php


namespace LeeMarkWood\BlizzardApi\Facades;

use Illuminate\Support\Facades\Facade;

/**
 * @see \LeeMarkWood\BlizzardApi\BlizzardApi
 */
class Guild extends Facade
{
    protected static function getFacadeAccessor(): string
    {
        return \LeeMarkWood\BlizzardApi\WoW\Guild::class;
    }
}
