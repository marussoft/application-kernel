<?php

declare(strict_types=1);

namespace Marussia\ApplicationKernel;

use Marussia\Config\Config;
use Marussia\Request\Request;
use Marussia\Router\Router;

class RouteBuilder
{
    private $request;

    private $router;

    private $response;

    private $config;

    public function __construct(Config $config, Response $response)
    {
        $this->response = $response;
        $this->config = $config;
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
        $this->router->setRoutesDirPath($this->config->get('kernel.router', 'routes_dir_path'));
        $this->router->setLanguages($this->config->get('app.locale', 'languages'));

        $result = $this->router->startRouting();

        if ($result->status) {
            $request->setAttributes($result->attributes);
            $request->setHandler($result->handler);
            $request->setAction($result->action);
            $request->attributes()->set('locale', $result->language);
        } else {
            $this->response->code(Response::HTTP_NOT_FOUND);
        }
    }
}
