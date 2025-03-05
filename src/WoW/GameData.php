<?php

namespace LeeMarkWood\BlizzardApi\WoW;

use LeeMarkWood\BlizzardApi\BlizzardApi;
use LeeMarkWood\BlizzardApi\Enums\BaseURL;
use LeeMarkWood\BlizzardApi\Enums\Game;
use LeeMarkWood\BlizzardApi\Enums\Region;
use stdClass;
use Exception;

class GameData extends BlizzardApi
{
    public function __construct(?Region $region = null, ?string $accessToken = '')
    {
        $this->game = Game::WoW;
        parent::__construct($region, $accessToken);
    }

    /**
     * @param  string  $endpointUri
     * @return string
     */
    protected function requestUrl(string $endpointUri = ''): string
    {
        $url = $this->baseUrl(BaseURL::game_data);
        if ($endpointUri !== '') {
            $url .= "/{$endpointUri}";
        }

        return $url;
    }

    /**
     * @param  array  $options
     * @return array|stdClass
     * @throws Exception
     * @link https://develop.battle.net/documentation/world-of-warcraft/game-data-apis
     */
    public function getRealmList(array $options): array|stdClass
    {
        return $this->performRequest($this->requestUrl('realm/index'), $options);
    }
}