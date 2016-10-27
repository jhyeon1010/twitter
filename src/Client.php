<?php
/**
 * Client.php
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

use TwitterAPIExchange;

class Client
{
    const BASE_API_URL = 'https://api.twitter.com';

    const API_VERSION = '1.1';

    const DEFAULT_FORMAT = 'json';

    protected $consumerKey;

    protected $consumerSecret;

    public function __construct($consumerKey, $consumerSecret)
    {
        $this->consumerKey = $consumerKey;
        $this->consumerSecret = $consumerSecret;
    }

    /**
     * @param Request $request
     * @return Response
     */
    public function sendRequest(Request $request)
    {
        $exchange = $this->createExchange($request->getAccessToken(), $request->getAccessTokenSecret());
        $exchange->buildOauth($this->buildUrl($request->getEndpoint()), $request->getMethod());
        if ($request->getMethod() === 'POST') {
            $exchange->setPostfields($request->getParams());
        } else {
            $exchange->setGetfield($request->getParams());
        }

        $rawResponse = $exchange->performRequest();

        return new Response($request, $rawResponse);
    }

    protected function buildUrl($endpoint)
    {
        $url = static::BASE_API_URL . '/' . static::API_VERSION . '/' . ltrim($endpoint, '/');

        return $url . '.' . static::DEFAULT_FORMAT;
    }

    protected function createExchange($accessToken, $accessTokenSecret)
    {
        return new TwitterAPIExchange([
            'oauth_access_token' => $accessToken,
            'oauth_access_token_secret' => $accessTokenSecret,
            'consumer_key' => $this->consumerKey,
            'consumer_secret' => $this->consumerSecret
        ]);
    }
}
