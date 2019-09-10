<?php

declare(strict_types=1);

namespace Marussia\ApplicationKernel;

class HttpKernel extends AbstractKernel
{
    private $request;

    public function handle(Request $request) : Response
    {
        $this->routeBuilder->resolve($request);
    
        if ($this->extensionCollector->extensionsIsExists()) {
            $extensions = $this->extensionCollector->getExtensions();
            foreach($extensions as $extension) {
                $extension->handle($request);
            }
        }
    
        $this->handler->run($request);
        
        $this->response->prepare($this->view);
        
        return $this->response();
    }
}
