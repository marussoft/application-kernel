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
    
    public function __construct(
        ExtensionCollector $extensionCollector, 
        RouteBuilder $routeBuilder, 
        RequestHandler $handler, 
        Response $response,
        HookHandler $hook
    )
    {
        $this->extensionCollector = $extensionCollector;
        $this->routeBuilder = $routeBuilder;
        $this->handler = $handler;
        $this->response = $response;
        $this->hook = $hook;
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
        $this->hook->run();
    }
    
    public function getResponse() : Response
    {
        return $this->response;
    }
}
