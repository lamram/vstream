<?php

namespace source;

class request {
    public array $parameters = [];
    public string $current_page;

    public function __construct(string $default_page = '') {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            if (!csrf_check()) {
                http_response_code(500);
                exit;
            }
            $this->parameters = &$_POST;
            unset($this->parameters['token']);
        }
        
        $parameters = explode('/', $_GET['request'] ?? '');
        $this->current_page = array_pop($parameters) ?: $default_page ?: env('HOMEPAGE');
        $this->parameters = array_merge($parameters, $this->parameters);
    }
}