<?php

declare(strict_types=1);

namespace Marussia\ApplicationKernel;

use Marussia\ApplicationKernel\Exceptions\KernelExtensionException;
use Marussia\ApplicationKernel\Exceptions\HandlerProcessException;

class HttpKernel extends AbstractKernel
{
    private $request;

    public function handle(Request $request) : Response
    {
        $this->routeBuilder->resolve($request);

        $this->serviceManager->set($request);

        $extensions = $this->config->get('kernel.extensions', 'extensions');
        
        if (!empty($extensions)) {
            $this->runExtensions($request, $extensions);
        }

        if ($this->response->isOk()) {
            $this->runHandler($request);
        }

        $this->response->prepare();

        return $this->response;
    }

    private function runExtensions(Request $request, array $extensions)
    {
        foreach($extensions as $extensionName => $extensionClass) {

            $extension = $this->serviceManager->instance($extensionClass);

            try {
                $extension->handle($request);
            } catch (\Throwable $exception) {
                $extensionException = new KernelExtensionException($extensionName, $exception);
                $this->log->write($extensionException->getMessage());
                throw $exception;
            }
        }
    }

    private function runHandler(Request $request)
    {

        $handlerClass = $this->config->get('handlers.' . strtolower($request->getHandler()), $request->getAction());

        if ($handlerClass === null) {
            $this->response->code(Response::HTTP_INTERNAL_SERVER_ERROR);
        } else {
            try {
                $handler = $this->serviceManager->instance($handlerClass);
                $handler->run($request);
            } catch (\Throwable $exception) {
                $handlerException = new HandlerProcessException($request, $exception);
                $this->log->write($handlerException->getMessage());
                throw $exception;
            }
        }
    }
}
