<?php
/**
 * Twitter.php
 *
 * PHP version 5
 *
 * @category
 * @package
 * @author      XE Developers <developers@xpressengine.com>
 * @copyright   2015 Copyright (C) NAVER Corp. <http://www.navercorp.com>
 * @license     http://www.gnu.org/licenses/old-licenses/lgpl-2.1.html LGPL-2.1
 * @link        https://xpressengine.io
 */

namespace Jhyeon1010\Twitter;

class Twitter
{
    protected $consumerKey;

    protected $consumerSecret;

    protected $defaultAccessToken;

    protected $defaultAccessTokenSecret;

    public function __construct(array $config)
    {
        $this->consumerKey = $config['consumer_key'];
        $this->consumerSecret = $config['consumer_secret'];
        if(isset($config['default_oauth_access_token'])) {
            $this->defaultAccessToken = $config['default_oauth_access_token'];
        }
        if(isset($config['default_oauth_access_token_secret'])) {
            $this->defaultAccessTokenSecret = $config['default_oauth_access_token_secret'];
        }

        $this->client = new Client($this->consumerKey, $this->consumerSecret);
    }

    public function get($endpoint, array $params = [], $accessToken = null, $accessTokenSecret = null)
    {
        return $this->sendRequest(
            'GET',
            $endpoint,
            $params,
            $accessToken,
            $accessTokenSecret
        );
    }

    public function post($endpoint, array $params = [], $accessToken = null, $accessTokenSecret = null)
    {
        return $this->sendRequest(
            'POST',
            $endpoint,
            $params,
            $accessToken,
            $accessTokenSecret
        );
    }

    public function sendRequest($method, $endpoint, array $params = [], $accessToken = null, $accessTokenSecret = null)
    {
        $request = $this->request($method, $endpoint, $params, $accessToken, $accessTokenSecret);

        return $this->lastResponse = $this->client->sendRequest($request);
    }

    public function request($method, $endpoint, array $params = [], $accessToken = null, $accessTokenSecret = null)
    {
        $accessToken = $accessToken ?: $this->defaultAccessToken;
        $accessTokenSecret = $accessTokenSecret ?: $this->defaultAccessTokenSecret;

        return new Request(
            $accessToken,
            $accessTokenSecret,
            $method,
            $endpoint,
            $params
        );
    }
}
