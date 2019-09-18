<?php

declare(strict_types=1);

namespace Marussia\ApplicationKernel;

use Marussia\Response\Response as RawResponse;
use Marussia\Template\Template;

class Response extends RawResponse
{
    private $template;
    
    private $view;

    public function __construct()
    {
        $this->template = new Template(Config::get('kernel.template', 'path_to_view'));
    }
    
    public function prepare(string $view)
    {
        if (!empty($this->view)) {
            $this->content($this->template->render($this->view));
        }
    }
    
    public function setView(string $view)
    {
        if (empty($this->view)) {
            $this->view = str_replace('.', '/', $view);
        }
    }
    
    public function setContent(array $content)
    {
        $this->template->content($content);
    }
}
