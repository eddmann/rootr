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
        echo $this->body;
    }

}
