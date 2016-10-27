<?php
/**
 * Response.php
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

class Response
{
    protected $request;

    protected $body;

    protected $decodedBody;

    public function __construct(Request $request, $body)
    {
        $this->request = $request;
        $this->body = $body;

        $this->decodedBody = json_decode($this->body, true);
    }

    public function getRequest()
    {
        return $this->request;
    }

    public function getBody()
    {
        return $this->decodedBody;
    }

    public function getRawBody()
    {
        return $this->body;
    }
}
