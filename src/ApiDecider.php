<?php

namespace Tomaj\NetteApi;

use Tomaj\NetteApi\Authorization\ApiAuthorizationInterface;
use Tomaj\NetteApi\Authorization\NoAuthorization;
use Tomaj\NetteApi\Handlers\ApiHandlerInterface;
use Tomaj\NetteApi\Handlers\DefaultHandler;
use Tomaj\NetteApi\Link\ApiLink;

class ApiDecider
{
    /**
     * @var ApiHandlerInterface[]
     */
    private $handlers = [];

    /**
     * @var ApiLink
     */
    private $apiLink;

    /**
     * ApiDecider constructor.
     *
     * @param ApiLink $apiLink
     */
    public function __construct(ApiLink $apiLink)
    {
        $this->apiLink = $apiLink;
    }

    /**
     * Get api handler that match input method, version, package and apiAction.
     * If decider cannot find handler for given handler, returns defaults.
     *
     * @param string   $method
     * @param integer  $version
     * @param string   $package
     * @param string   $apiAction
     *
     * @return ApiIdentifier
     */
    public function getApiHandler($method, $version, $package, $apiAction = '')
    {
        foreach ($this->handlers as $handler) {
            $identifier = $handler->getEndpoint();
            if ($method == $identifier->getMethod() && $identifier->getVersion() == $version && $identifier->getPackage() == $package && $identifier->getApiAction() == $apiAction) {
                $handler->getHandler()->setEndpointIdentifier($handler->getEndpoint());
                return $handler;
            }
        }
        return new ApiIdentifier(
            new EndpointIdentifier($method, $version, $package, $apiAction),
            new DefaultHandler($version, $package, $apiAction),
            new NoAuthorization()
        );
    }

    /**
     * Register new api handler
     *
     * @param EndpointInterface         $endpointIdentifier
     * @param ApiHandlerInterface       $handler
     * @param ApiAuthorizationInterface $apiAuthorization
     *
     * @return $this
     */
    public function addApiHandler(EndpointInterface $endpointIdentifier, ApiHandlerInterface $handler, ApiAuthorizationInterface $apiAuthorization)
    {
        $this->handlers[] = new ApiIdentifier($endpointIdentifier, $handler, $apiAuthorization);
        return $this;
    }

    /**
     * Get all registered handlers
     *
     * @return Handlers\ApiHandlerInterface[]
     */
    public function getHandlers()
    {
        return $this->handlers;
    }
}
