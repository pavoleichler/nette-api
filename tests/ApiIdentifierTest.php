<?php

namespace Tomaj\NetteApi\Test;

use PHPUnit_Framework_TestCase;
use Tomaj\NetteApi\Authorization\NoAuthorization;
use Tomaj\NetteApi\EndpointIdentifier;
use Tomaj\NetteApi\Handlers\AlwaysOkHandler;
use Tomaj\NetteApi\ApiIdentifier;


class ApiIdentifierTest extends PHPUnit_Framework_TestCase
{
    public function testDefaultHandlerWithNoRegisteredHandlers()
    {
        $endpoint = new EndpointIdentifier('POST', 2, 'comments', 'list');
        $handler = new AlwaysOkHandler();
        $authorization = new NoAuthorization();
        
        $apiIdentifer = new ApiIdentifier($endpoint, $handler, $authorization);

        $this->assertEquals($endpoint, $apiIdentifer->getEndpoint());
        $this->assertEquals($handler, $apiIdentifer->getHandler());
        $this->assertEquals($authorization, $apiIdentifer->getAuthorization());
    }
}
