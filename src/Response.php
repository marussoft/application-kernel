<?php

declare(strict_types=1);

namespace Marussia\ApplicationKernel;

use Marussia\Response\Response as RawResponse;
use Marussia\Template\Template;

class Response extends RawResponse
{
    private $template;

    private $view = '';
    
    private $session;

    const SESSION_MESSAGES_KEY = 'messages';
    
    const SESSION_DATA_KEY = 'data';
    
    public function __construct(Template $template, Session $session)
    {
        $this->template = $template;
        $this->session = $session;
    }

    public function prepare() : self
    {
        if ($this->code !== self::HTTP_OK && $this->template->exists((string) $this->code)) {
            $this->view = (string) $this->code;
        }

        if (!empty($this->view)) {
            $this->content($this->template->render($this->view));
        }
        return $this;
    }

    public function setView(string $view) : self
    {
        if (empty($this->view)) {
            $this->view = str_replace('.', '/', $view);
        }
        return $this;
    }

    public function setContent(array $content) : self
    {
        $this->template->content($content);
        return $this;
    }

    public function json($data = null) : self
    {
        $this->content(json_encode($data, JSON_UNESCAPED_UNICODE));
        $this->header('Content-type: application/json; charset=utf-8');
        return $this;
    }

    public function template() : Template
    {
        return $this->template;
    }
    
    public function redirect(string $url) : self
    {
        $this->header('Location: ' . $url);
        return $this;
    }
    
    public function message(array $newMessages) : self
    {
        $messages = [];
        
        if ($this->session->has(self::SESSION_MESSAGES_KEY)) {
            $messages = $this->session->get(self::SESSION_MESSAGES_KEY);
        }
        
        $newMessages = array_merge_recursive($newMessages, $messages);
        
        $this->session->set(self::SESSION_MESSAGES_KEY, $newMessages);
        return $this;
    }
    
    public function data(array $dataSet) : self
    {
        $data = [];
        
        if ($this->session->has(self::SESSION_DATA_KEY)) {
            $dataSet = $this->session->get(self::SESSION_DATA_KEY);
        }
        
        $dataSet = array_merge_recursive($dataSet, $data);
        
        $this->session->set(self::SESSION_DATA_KEY, $dataSet);
        return $this;
    }
}
