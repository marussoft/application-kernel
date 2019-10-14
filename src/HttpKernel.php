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
        
        if ($this->extensionCollector->extensionsIsExists()) {
            $extensions = $this->extensionCollector->getExtensions();
            foreach($extensions as $extensionName => $extensionClass) {
            
                $extension = $this->serviceManager->instance($extensionClass);
                
                try {
                    $extension->handle($request);
                } catch (\Throwable $exception) {
                    $extensionException = new KernelExtensionException($extensionName, $exception);
                    $this->log->write($extensionException->getMessage());
                    throw $extensionException;
                }
            }
        }

        if ($this->response->isOk()) {
            try {
                $this->handler->run($request);
            } catch (\Throwable $exception) {
                $handlerException = new HandlerProcessException($request, $exception);
                $this->log->write($handlerException->getMessage());
                throw $handlerException;
            }
        }

        $this->response->prepare($this->view);

        return $this->response;
    }
}
