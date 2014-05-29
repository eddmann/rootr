<?php namespace Rootr;


class Response
{

    protected $status;

    protected $body;

    protected $headers;

    public function __construct($status, $body = '', array $headers = [])
    {
        $this->status = $status;
        $this->body = $body;
        $this->headers = $headers;
    }

    public function setHeader($name, $value)
    {
        $this->headers[trim($name)] = trim($value);

        return $this;
    }

    public function sendHeaders()
    {
        if (headers_sent()) {
            return $this;
        }

        http_response_code($this->status);

        foreach ($this->headers as $name => $value) {
            header("$name: $value", false, $this->status);
        }

        return $this;
    }

    public function sendBody()
    {
        echo $this->body;

        return $this;
    }

    public function render()
    {
        $this->sendHeaders();
        $this->sendBody();
    }

    public function asJson()
    {
        $this->headers['Content-type'] = 'application/json';

        return $this;
    }

}
