<?php

declare(strict_types=1);

namespace Marussia\ApplicationKernel;

use Marussia\EventBus\Result;
use Marussia\Template\Template;

abstract class AbstractKernel
{
    protected $extensionCollector;
    
    protected $routeBuilder;
    
    protected $bus;
    
    protected $response;
    
    protected $handler;
    
    protected $template;
    
    protected $view;
    
    public function __construct(
        ExtensionCollector $extensionCollector, 
        RouteBuilder $routeBuilder, 
        RequestHandler $handler, 
        Response $response,
        Template $template
    )
    {
        $this->extensionCollector = $extensionCollector;
        $this->routeBuilder = $routeBuilder;
        $this->handler = $handler;
        $this->response = $response;
        $this->template = $template;
    }
    
    public function view(string $view, array $data)
    {
        $this->template->content($data);
        
        $viewFile = str_replace('.', '/', $view);
        
        $this->view($viewFile);
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
