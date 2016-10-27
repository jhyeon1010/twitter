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

        $this->httpClient = new \GuzzleHttp\Client();
    }

    /**
     * @param Request $request
     * @return Response
     */
    public function sendRequest(Request $request)
    {
        list($url, $method, $headers, $params) = $this->prepareRequest($request);

        $options = ['headers' => $headers];
        if ($params) {
            $options = array_merge($options, ['form_params' => $params]);
        }

        $rawResponse = $this->httpClient->request($method, $url, $options);

        return new Response(
            $request,
            $rawResponse->getBody(),
            $rawResponse->getStatusCode(),
            $rawResponse->getHeaders()
        );
    }

    protected function prepareRequest(Request $request)
    {
        $headers = $this->buildRequestHeader($request, $url = $this->buildUrl($request->getEndpoint()));
        $params = null;
        if ($request->getMethod() === 'POST') {
            $params = $request->getParams();
        } else {
            $url .= '?' . http_build_query($request->getParams());
        }

        return [$url, $request->getMethod(), $headers, $params];
    }

    protected function buildRequestHeader(Request $request, $url)
    {
        $oauth = [
            'oauth_consumer_key' => $this->consumerKey,
            'oauth_nonce' => time(),
            'oauth_signature_method' => 'HMAC-SHA1',
            'oauth_token' => $request->getAccessToken(),
            'oauth_timestamp' => time(),
            'oauth_version' => '1.0'
        ];

        $base_info = $this->buildBaseString($url, $request->getMethod(), array_merge($oauth, $request->getParams()));
        $composite_key = rawurlencode($this->consumerSecret) . '&' . rawurlencode($request->getAccessTokenSecret());
        $oauth['oauth_signature'] = base64_encode(hash_hmac('sha1', $base_info, $composite_key, true));

        array_walk($oauth, function (&$value, $key) {
            $value = $key . '="' . rawurlencode($value) . '"';
        });

        return [
            'Authorization' => 'OAuth ' . implode(', ', $oauth),
            'Expect'
        ];
    }

    /**
     * Private method to generate the base string used by cURL
     *
     * @param string $baseURI
     * @param string $method
     * @param array  $params
     *
     * @return string Built base string
     *
     * @see "TwitterAPIExchange" in j7mbo/twitter-api-php
     */
    private function buildBaseString($baseURI, $method, $params)
    {
        $return = array();
        ksort($params);

        foreach($params as $key => $value)
        {
            $return[] = rawurlencode($key) . '=' . rawurlencode($value);
        }

        return $method . "&" . rawurlencode($baseURI) . '&' . rawurlencode(implode('&', $return));
    }

    protected function buildUrl($endpoint)
    {
        $url = static::BASE_API_URL . '/' . static::API_VERSION . '/' . ltrim($endpoint, '/');

        return $url . '.' . static::DEFAULT_FORMAT;
    }
}
