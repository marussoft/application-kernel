<?php

declare(strict_types=1);

namespace Marussia\ApplicationKernel;

class HttpKernel extends AbstractKernel
{
    public function handle(Request $request) : Response
    {
        $result = $this->routeBuilder->resolve($request);
    
        if (!$result->status) {
            $this->response->code(404)->send();
            $this->terminate();
        }
    
        if ($this->extensionCollector->extensionsIsExists()) {
            $extensions = $this->extensionCollector->getExtensions();
            foreach($extensions as $extension) {
                $extension->handle($request);
            }
        }
    
        $this->bus->run($request);
        
        return $this->response();
    }
    
    public function terminate()
    {
        // Terminate applications
    }
}
