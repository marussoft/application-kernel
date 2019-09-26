<?php

declare(strict_types=1);

namespace Marussia\ApplicationKernel;

use Marussia\Request\Request;
use Marussia\Router\Router;
use Marussia\ApplicationKernel\Config;

class RouteBuilder
{
    private $request;
    
    private $router;
    
    private $response;
    
    public function __construct(Response $response)
    {
        $this->response = $response;
    }
    
    public function resolve(Request $request) : void
    {
        $this->request = $request;
        
        $this->router = Router::create(
            $this->request->getUri(), 
            $this->request->getMethod(), 
            $this->request->server->get('HTTP_HOST'), 
            $this->request->isSecure() ? 'https' : 'http'
        );
        $this->router->setRoutesDirPath(Config::get('kernel.router', 'routes_dir_path'));
        $this->router->setLanguages(Config::get('kernel.router', 'languages'));
        
        $result = $this->router->startRouting();

        if ($result->status) {
            $request->setAttributes($result->attributes);
            $request->setHandler($result->handler);
            $request->setAction($result->action);
            $request->attributes()->set('locale', $result->language);
        } else {
            $this->response->code(404);
        }
        
    }
} 
