<?php

namespace Tomaj\NetteApi\Test\Params;

use PHPUnit_Framework_TestCase;
use Nette\Application\LinkGenerator;
use Nette\Application\Routers\SimpleRouter;
use Nette\Http\Url;
use Tomaj\NetteApi\ApiDecider;
use Tomaj\NetteApi\Authorization\NoAuthorization;
use Tomaj\NetteApi\EndpointIdentifier;
use Tomaj\NetteApi\Handlers\AlwaysOkHandler;
use Tomaj\NetteApi\Link\ApiLink;

class ApiDeciderTest extends PHPUnit_Framework_TestCase
{
    public function testDefaultHandlerWithNoRegisteredHandlers()
    {
        $linkGenerator = new LinkGenerator(new SimpleRouter([]), new Url('http://test/'));
        $apiLink = new ApiLink($linkGenerator);

        $apiDecider = new ApiDecider($apiLink);
        $result = $apiDecider->getApi('POST', 1, 'article', 'list');

        $this->assertInstanceOf('Tomaj\NetteApi\EndpointIdentifier', $result->getEndpoint());
        $this->assertInstanceOf('Tomaj\NetteApi\Authorization\NoAuthorization', $result->getAuthorization());
        $this->assertInstanceOf('Tomaj\NetteApi\Handlers\DefaultHandler', $result->getHandler());
    }

    public function testFindRightHandler()
    {
        $linkGenerator = new LinkGenerator(new SimpleRouter([]), new Url('http://test/'));
        $apiLink = new ApiLink($linkGenerator);

        $apiDecider = new ApiDecider($apiLink);
        $apiDecider->addApi(
            new EndpointIdentifier('POST', 2, 'comments', 'list'),
            new AlwaysOkHandler(),
            new NoAuthorization()
        );

        $result = $apiDecider->getApi('POST', 2, 'comments', 'list');

        $this->assertInstanceOf('Tomaj\NetteApi\EndpointIdentifier', $result->getEndpoint());
        $this->assertInstanceOf('Tomaj\NetteApi\Authorization\NoAuthorization', $result->getAuthorization());
        $this->assertInstanceOf('Tomaj\NetteApi\Handlers\AlwaysOkHandler', $result->getHandler());

        $this->assertEquals('POST', $result->getEndpoint()->getMethod());
        $this->assertEquals(2, $result->getEndpoint()->getVersion());
        $this->assertEquals('comments', $result->getEndpoint()->getPackage());
        $this->assertEquals('list', $result->getEndpoint()->getApiAction());
    }

    public function testGetHandlers()
    {
        $linkGenerator = new LinkGenerator(new SimpleRouter([]), new Url('http://test/'));
        $apiLink = new ApiLink($linkGenerator);

        $apiDecider = new ApiDecider($apiLink);

        $this->assertEquals(0, count($apiDecider->getApiIdentifiers()));

        $apiDecider->addApi(
            new EndpointIdentifier('POST', 2, 'comments', 'list'),
            new AlwaysOkHandler(),
            new NoAuthorization()
        );

        $this->assertEquals(1, count($apiDecider->getApiIdentifiers()));
    }
}
