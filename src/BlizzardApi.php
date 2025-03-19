<?php

namespace LeeMarkWood\BlizzardApi;

use Exception;
use Faker\Provider\Base;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Http;
use LeeMarkWood\BlizzardApi\Enums\BaseURL;
use LeeMarkWood\BlizzardApi\Enums\EndpointNamespace;
use LeeMarkWood\BlizzardApi\Enums\EndpointVersion;
use LeeMarkWood\BlizzardApi\Enums\Game;
use LeeMarkWood\BlizzardApi\Enums\Region;
use stdClass;

abstract class BlizzardApi
{
    protected $api_key;

    protected $api_secret;

    /**
     * @var string Cached access token.
     */
    protected string $accessToken;

    /**
     * @var Region API region
     */
    protected Region $region;

    /**
     * @var Game Game name
     */
    protected Game $game = Game::None;

    /**
     * Creates an interface for calling API Endpoints
     *
     * @param  Region|null  $region  One of the supported API regions: Region::US, REGION_EU, REGION_KO or REGION_TW
     * @param  string|null  $accessToken  Allow to specify an access_token for the requests, useful for specifying a
     *                                    token obtained using authorization_code flow.
     *
     * @throws Exception In case a token cannot be obtained.
     */
    public function __construct(?Region $region = null, ?string $accessToken = '')
    {
        $this->region = $region ?: config('blizzard-api.region', Region::EU);
        $this->api_key = config('blizzard-api.api_key');
        $this->api_secret = config('blizzard-api.api_secret');

        // Using an externally created token
        if ($accessToken) {
            $this->accessToken = $accessToken;
        } else {
            // Get token from cache or request a new one
            $this->accessToken = $this->getAccessToken();
        }
    }

    /**
     * @param  BaseURL  $scope  API scope to apply the base URL
     * @return string Base URL to call endpoints
     */
    protected function baseUrl(BaseURL $scope): string
    {
        return sprintf($scope->value, $this->region->value, $this->game->value);
    }

    /**
     * Retrieve the access token from Blizzard and cache it.
     */
    public function getAccessToken()
    {
        return Cache::remember($this->region->value.'.blizzard_access_token', 60, function () {
            $response = Http::withBasicAuth($this->api_key, $this->api_secret)
                ->asForm()
                ->post($this->baseUrl(BaseURL::oauth_token), [
                    'grant_type' => 'client_credentials',
                ]);

            if ($response->failed()) {
                throw new \Exception('Unable to get access token from Blizzard.');
            }
 
            $data = $response->json();

            return $data['access_token'] ?? null;
        });
    }

    /**
     * @param  string  $url  The endpoint url
     * @param  array  $options  An array containing options for a single request
     *
     * @throws Exception
     */
    public function performRequest(string $url, array $options = []): stdClass|array
    {
        $url = $this->prepareURL($url, $options);

        if(Cache::has($url)) {
            return Cache::get($url);
        }

        $responseCode = 0;
        $data = $this->execute($url, $responseCode);

        if ($responseCode === 200) {
            return Cache::remember($url,$options['ttl'] ?? 86400, function () use ($data) {
                return json_decode($data);
            });
        }

        throw new Exception("Request to '$url' failed", $responseCode);
    }

    /**
     * @param  string  $url  The endpoint URL
     * @param  array  $options  Options and additional query string parameters
     * @return string Well formed URL
     */
    protected function prepareURL(string $url, array $options): string
    {
        $queryString = $this->extractQueryString($options);
        if ($queryString) {
            if (str_contains($url, '?')) {
                $url .= "&$queryString";
            } else {
                $url .= "?$queryString";
            }
        }

        return $url;
    }

    /**
     * @param  array  $options  An array containing options for a single request
     * @return string The query string params for this request
     */
    private function extractQueryString(array &$options): string
    {
        $defaultOptions = [
            'ttl' => 86400,
            'region' => $this->region,
            'accessToken' => $this->accessToken,
            'namespace' => EndpointNamespace::static,
            'version' => EndpointVersion::retail,
        ];

        $queryString = array_diff_key($options, $defaultOptions);

        if (array_key_exists('namespace', $options)) {
            $queryString['namespace'] = $this->endpointNamespace($options['namespace'], $options['version']);
        }

        $options = array_intersect_key($options, $defaultOptions);

        return http_build_query($queryString);
    }

    /**
     * @param  EndpointNamespace  $namespace  The endpoint namespace
     * @param  EndpointVersion  $version  The desired version of the endpoint
     * @return string The appropriate namespace for the endpoint namespace, version and region
     */
    protected function endpointNamespace(EndpointNamespace $namespace, EndpointVersion $version = EndpointVersion::retail): string
    {
        return sprintf($namespace->value, sprintf($version->value, $this->region->value));
    }

    /**
     * @param  string  $url  API endpoint full url with querystring params
     * @param  int  $responseStatus  HTTP response code
     * @return string|null JSON object response
     */
    public function execute(string $url, int &$responseStatus): ?string
    {
        try {
            $response = Http::withToken($this->accessToken)
                ->get($url);

            $responseStatus = $response->status();

            return $response->body();
        } catch (Exception) {
            $responseStatus = 0;

            return null;
        }
    }
}
