<?php
/**
 * Request.php
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

class Request
{
    protected $accessToken;

    protected $accessTokenSecret;

    protected $method;

    protected $endpoint;

    protected $params;

    public function __construct($accessToken, $accessTokenSecret, $method, $endpoint, array $params = [])
    {
        $this->accessToken = $accessToken;
        $this->accessTokenSecret = $accessTokenSecret;
        $this->method = strtoupper($method);
        $this->endpoint = $endpoint;
        $this->params = $params;
    }

    /**
     * @return string
     */
    public function getAccessToken()
    {
        return $this->accessToken;
    }

    /**
     * @return string
     */
    public function getAccessTokenSecret()
    {
        return $this->accessTokenSecret;
    }

    public function getMethod()
    {
        return $this->method;
    }

    public function getEndpoint()
    {
        return $this->endpoint;
    }

    public function getParams()
    {
        return $this->params;
    }
}
