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
        $this->template = new Template;
    }
    
    public function prepare(string $view)
    {
        if ($this->code !== 200 && $this->template->exists((string) $this->code)) {
            $this->view = (string) $this->code;
        }
    
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
    
    public function json($data = null)
    {
        $this->content(json_encode($data, JSON_UNESCAPED_UNICODE));
        $this->header('Content-type: application/json; charset=utf-8');
    }
    
    public function template() : Template
    {
        return $this->template;
    }
}
