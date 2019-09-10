<?php

declare(strict_types=1);

namespace Marussia\ApplicationKernel;

use Marussia\Response\Response as RawResponse;
use Marussia\Template\Template;

class Response extends RawResponse
{
    private $template;

    public function __construct(Template $template)
    {
        $this->template = $template;
    }
    
    public function prepare(string $view)
    {
        $this->content($this->template->render($view));
    }
}
