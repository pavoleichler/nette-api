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
     * @var ApiIdentifier[]
     */
    private $apiIdentifiers = [];

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
    public function getApi($method, $version, $package, $apiAction = '')
    {
        foreach ($this->apiIdentifiers as $identifier) {
            $endpoint = $identifier->getEndpoint();
            if ($endpoint->getMethod() == $method && $endpoint->getVersion() == $version && $endpoint->getPackage() == $package && $endpoint->getApiAction() == $apiAction) {
                $identifier->getHandler()->setEndpointIdentifier($endpoint);
                return $identifier;
            }
        }
        return new ApiIdentifier(
            new EndpointIdentifier($method, $version, $package, $apiAction),
            new DefaultHandler($version, $package, $apiAction),
            new NoAuthorization()
        );
    }

    /**
     * Register new api
     *
     * @param EndpointInterface         $endpointIdentifier
     * @param ApiHandlerInterface       $handler
     * @param ApiAuthorizationInterface $apiAuthorization
     *
     * @return $this
     */
    public function addApi(EndpointInterface $endpointIdentifier, ApiHandlerInterface $handler, ApiAuthorizationInterface $apiAuthorization)
    {
        $this->apiIdentifiers[] = new ApiIdentifier($endpointIdentifier, $handler, $apiAuthorization);
        return $this;
    }

    /**
     * Register new api handler
     *
     * @deprecated use addApi()
     *
     * @param EndpointInterface         $endpointIdentifier
     * @param ApiHandlerInterface       $handler
     * @param ApiAuthorizationInterface $apiAuthorization
     *
     * @return $this
     */
    public function addApiHandler(EndpointInterface $endpointIdentifier, ApiHandlerInterface $handler, ApiAuthorizationInterface $apiAuthorization)
    {
        return $this->addApi($endpointIdentifier, $handler, $apiAuthorization);
    }

    /**
     * Get all registered api identifiers
     *
     * @return ApiIdentifier[]
     */
    public function getApiIdentifiers()
    {
        return $this->apiIdentifiers;
    }
}
