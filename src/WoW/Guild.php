<?php

namespace LeeMarkWood\BlizzardApi\WoW;

use Exception;
use Illuminate\Support\Str;
use LeeMarkWood\BlizzardApi\BlizzardApi;
use LeeMarkWood\BlizzardApi\Enums\BaseURL;
use LeeMarkWood\BlizzardApi\Enums\Game;
use LeeMarkWood\BlizzardApi\Enums\Region;
use stdClass;

class Guild extends BlizzardApi
{
    protected string $name {
        set {
            $this->name = Str::slug($value);
        }
    }

    protected string $realm {
        set {
            $this->realm = Str::slug($value);
        }
    }

    public function __construct(?Region $region = null, ?string $accessToken = '', string $realm = '', string $name = '')
    {
        $this->game = Game::WoW;
        $this->name = $name;
        $this->realm = $realm;
        parent::__construct($region, $accessToken);
    }

    public function requestUrl(string $endpointUri = ''): string
    {
        $url = $this->baseUrl(BaseURL::game_data)."/guild/{$this->realm}/{$this->name}";
        if ($endpointUri) {
            $url .= "/{$endpointUri}";
        }

        return $url;
    }

    /**
     * @throws Exception
     */
    public function profile(): array|stdClass
    {
        return $this->performRequest($this->requestUrl());
    }

    /**
     * @throws Exception
     */
    public function roster(): array|stdClass
    {
        return $this->performRequest($this->requestUrl('roster'));
    }
}
