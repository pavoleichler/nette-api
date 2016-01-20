<?php

namespace Tomaj\NetteApi;

use Tomaj\NetteApi\Authorization\ApiAuthorizationInterface;
use Tomaj\NetteApi\Handlers\ApiHandlerInterface;

class ApiIdentifier
{
    private $endpoint;

    private $handler;

    private $auhorization;

    public function __construct(EndpointInterface $endpoint, ApiHandlerInterface $handler, ApiAuthorizationInterface $authorization)
    {
        $this->endpoint = $endpoint;
        $this->handler = $handler;
        $this->authorization = $authorization;
    }

    public function getEndpoint()
    {
        return $this->endpoint;
    }

    public function getHandler()
    {
        return $this->handler;
    }

    public function getAuthorization()
    {
        return $this->authorization;
    }
}
