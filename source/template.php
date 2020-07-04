<?php

namespace library;

class file_buffer {
    public string $path;
    public string $body;
    public int $size;

    public function __construct(string $path) {
        $this->path = $path;
        $this->body = file_get_contents($path);
        $this->size = strlen($this->body);
    }
}

class template {
    private file_buffer $layout;

    public function __construct(array $parameters = [], string $template_path = 'templates/layout.html') {
        $this->layout = new file_buffer($template_path);
        
        if ($parameters) {
            $this->layout = $this->bind_parameters($this->layout, $parameters);
        }
    }

    public function bind_parameter(file_buffer $buffer, string $key, string $value) : file_buffer {
        if (strpos($buffer->body, "{{{$key}}}") === false) {
            LOG_INFO("Parameter {{{$key}}} does not exist");
            return $buffer;
        }
        
        $buffer->body = str_replace("{{{$key}}}", $value, $buffer->body);
        return $buffer;
    }

    public function bind_parameters(file_buffer $buffer, array $parameters) : file_buffer {
        foreach ($parameters as $key => $value) {
            $buffer = $this->bind_parameter($buffer, $key, $value);
        }

        return $buffer;
    }

    public function render(file_buffer $buffer) : string {
        return str_replace('{{{yield}}}', $buffer->body, $this->layout->body);
    }
}