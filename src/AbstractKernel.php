<?php

declare(strict_types=1);

namespace Marussia\ApplicationKernel;

use Marussia\EventBus\Result;

abstract class AbstractKernel
{
    protected $extensionCollector;
    
    protected $routeBuilder;
    
    protected $bus;
    
    protected $response;
    
    public function __construct(ExtensionCollector $extensionCollector, RouteBuilder $routeBuilder, EventBus $bus, Response $response)
    {
        $this->extensionCollector = $extensionCollector;
        $this->routeBuilder = $routeBuilder;
        $this->bus = $bus;
        $this->response = $response;
    }
    
    public function view(array $data)
    {
        $this->response->view($data);
    }
    
    public function done($data = null) : Result
    {
        return $this->bus->result('done', $data);
    }
    
    public function await(string $timeout) : Result
    {
        return $this->bus->result('await', null, $timeout);
    }
    
    public function fail(string $timeout) : Result
    {
        return $this->bus->result('fail', null, $timeout);
    }
    
    public function terminate(Request $request, Response $response)
    {
        $this->bus->terminate($request, $response);
    }
}
