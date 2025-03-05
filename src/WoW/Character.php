<?php

namespace LeeMarkWood\BlizzardApi\WoW;

use Illuminate\Support\Facades\URL;
use Illuminate\Support\Str;
use LeeMarkWood\BlizzardApi\BlizzardApi;
use LeeMarkWood\BlizzardApi\Enums\BaseURL;
use LeeMarkWood\BlizzardApi\Enums\EndpointNamespace;
use LeeMarkWood\BlizzardApi\Enums\Game;
use LeeMarkWood\BlizzardApi\Enums\Region;
use stdClass;
use Exception;

class Character extends BlizzardApi
{
    protected string $name;
    protected string $realm;

    public function __construct(Region|null $region = null, string|null $accessToken = '', string $realm = '', string $name = '')
    {
        $this->game = Game::WoW;
        $this->setName($name);
        $this->setRealm($realm);
        parent::__construct($region, $accessToken);
    }

    /**
     * @param  string  $name
     */
    public function setName(string $name): void
    {
        $this->name = Str::slug($name);
    }

    /**
     * @param  string  $realm
     */
    public function setRealm(string $realm): void
    {
        $this->realm = Str::slug($realm);
    }

    /**
     * @param  string  $endpointUri
     * @return string
     */
    public function requestUrl(string $endpointUri = ''): string
    {
        $url = $this->baseUrl(BaseURL::profile) . "/character/{$this->realm}/{$this->name}";
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

    public function achievements()
    {

    }

    public function appearance()
    {

    }

    /**
     * @throws Exception
     */
    public function equipment(): array|stdClass
    {
        return $this->performRequest($this->requestUrl('equipment'));
    }

}