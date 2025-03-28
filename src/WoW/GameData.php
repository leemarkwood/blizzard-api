<?php

namespace LeeMarkWood\BlizzardApi\WoW;

use Exception;
use LeeMarkWood\BlizzardApi\BlizzardApi;
use LeeMarkWood\BlizzardApi\Enums\BaseURL;
use LeeMarkWood\BlizzardApi\Enums\EndpointNamespace;
use LeeMarkWood\BlizzardApi\Enums\Game;
use LeeMarkWood\BlizzardApi\Enums\Region;
use phpDocumentor\Reflection\Types\Integer;
use stdClass;

class GameData extends BlizzardApi
{
    public function __construct(?Region $region = null, ?string $accessToken = '')
    {
        $this->game = Game::WoW;
        $this->endpointNamespace = EndpointNamespace::dynamic;

        parent::__construct($region, $accessToken);
    }

    protected function requestUrl(string $endpointUri = ''): string
    {
        $url = $this->baseUrl(BaseURL::game_data);
        if ($endpointUri !== '') {
            $url .= "/{$endpointUri}";
        }

        return $url;
    }

    /**
     * @throws Exception
     *
     * @link https://develop.battle.net/documentation/world-of-warcraft/game-data-apis
     */
    public function getRealmList(array $options): array|stdClass
    {
        $options = array_merge($options, ['namespace' => EndpointNamespace::dynamic]);

        return $this->performRequest($this->requestUrl('realm/index'), $options);
    }

    /**
     * @throws Exception
     */
    public function item(Integer $item, array $options): array|stdClass
    {
        return $this->performRequest($this->requestUrl('item/'.$item), $options);
    }
}
