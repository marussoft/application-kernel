<?php

declare(strict_types=1);

namespace Marussia\ApplicationKernel;

use Marussia\Request\Request;
use Marussia\Router\Router;
use Marussia\ApplicationKernel\Config;

class RequestBundle
{
    private $request;
    
    private $router;
    
    private $config;

    public function __construct(Router $router, Config $config)
    {
        $this->router = $router;
        $this->config = $config;
    }
    
    public function resolve(Request $request)
    {
        $this->request = $request;
        
        $this->router = Router::create(
            $this->request->getUri(), 
            $this->request->getMethod(), 
            $this->request->server->get('HTTP_HOST'), 
            $this->request->isSecure() ? 'https' : 'http'
        );
        $this->router->setRoutesDirPath(Config::get('kernel.router', 'routes_dir_path'));
        
        $result = $this->router->startRouting();
        
        $request->setAttributes($result->attributes);
        $request->setHandler($result->handler);
        $request->setAction($result->action);
    }
} 
