<?php

namespace Cr4sec\AppleAppsFlyer;

use GuzzleHttp\Promise\PromiseInterface;
use Illuminate\Http\Client\Response;
use Illuminate\Support\Facades\Http;

class Client
{
    const API_URL = 'https://api2.appsflyer.com';

    /** @var string */
    private $authenticationToken;

    /** @var string */
    private $appId;

    private $httpClient;

    public function __construct()
    {
        $this->authenticationToken = config('apple-appsflyer.appsflyer.authentication_token');
        $this->setAppId(config('apple-appsflyer.appsflyer.app_id'));

        $this->httpClient = Http::withHeaders(['authentication' => $this->authenticationToken]);
    }

    /**
     * @param  string  $appId
     */
    public function setAppId(string $appId)
    {
        $this->appId = $appId;
    }

    /**
     * @param  array  $payload
     * @return PromiseInterface|Response
     */
    public function sendEvent(array $payload)
    {
        return $this
            ->httpClient
            ->post(
                sprintf('%s/inappevent/%s', self::API_URL, $this->appId),
                $payload
            );
    }
}
