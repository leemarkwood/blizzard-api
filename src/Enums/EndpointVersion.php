<?php

namespace LeeMarkWood\BlizzardApi\Enums;

enum EndpointVersion: string
{
    case retail = '%s';
    case classic = 'classic-%s';
    case classic1x = 'classic1x-%s';
}