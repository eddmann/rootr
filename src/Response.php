<?php namespace Rootr;


class Response
{

    protected $status, $body, $headers;

    public function __construct($status, $body = '', array $headers = [])
    {
        $this->status = $status;
        $this->body = $body;
        $this->headers = $headers;
    }

    public function render()
    {
        http_response_code($this->status);

        foreach ($this->headers as $name => $value) {
            header($name, $value);
        }

        echo $this->body;
    }

}
