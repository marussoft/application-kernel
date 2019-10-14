<?php

declare(strict_types=1);

namespace Marussia\ApplicationKernel;

abstract class AbstractKernel
{
    protected $extensionCollector;

    protected $routeBuilder;

    protected $response;

    protected $handler;

    protected $template;

    protected $view = '';

    protected $hook;
    
    protected $serviceManager;

    public function __construct(
        ExtensionCollector $extensionCollector,
        RouteBuilder $routeBuilder,
        RequestHandler $handler,
        Response $response,
        Logger $log,
        ServiceManager $serviceManager
        
    )
    {
        $this->extensionCollector = $extensionCollector;
        $this->routeBuilder = $routeBuilder;
        $this->handler = $handler;
        $this->response = $response;
        $this->log = $log;
        $this->serviceManager = $serviceMamager;
    }

    public function view(string $view, array $data = [])
    {
        $this->response->setContent($data);
        $this->response->setView($view);
    }

    public function addHook($hook) : void
    {
        $this->hook->add($hook);
    }

    public function terminate()
    {
//         $this->hook->run();
    }

    public function getResponse() : Response
    {
        return $this->response;
    }
}
