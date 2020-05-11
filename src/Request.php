<?php

namespace BuildX\PHPServer;

class Request
{
    protected $method = null;
    protected $uri = null;
    protected $parameters = [];
    protected $headers = [];

    function __construct($method, $uri, $headers = [])
    {
        $this->method = strtoupper($method);
        $this->headers = $headers;

        // Split uri and parameters string
        // [$this->uri, $parameters] = explode('?', $uri);
        @list($this->uri, $parameters) = explode('?', $uri);

        // Parse the parameters
        parse_str($parameters, $this->parameters);
    }

    /**
     * Example Header
     *
     * GET / HTTP/1.1
     * Host: 127.0.0.1:8008
     * Connection: keep-alive
     * Accept: text/html
     * User-Agent: Chrome/41.0.2272.104
     * Accept-Encoding: gzip, deflate, sdch
     * Accept-Language: en-US,en;q=0.8,de;q=0.6
     *
     * @param string $header
     *
     * @return static
     */
    public static function withHeaderString($header)
    {
        $lines = explode("\n", $header);

        // Extract the method and uri
        [$method, $uri] = explode(' ', array_shift($lines));

        $headers = [];

        foreach ($lines as $line) {
            if ( strpos( $line, ': ' ) !== false ) {
                [$key, $value] = explode(': ', $line);

                $headers[$key] = $value;
            }
        }

        return new static($method, $uri, $headers);
    }

    /**
     * Request Method i.e. GET
     *
     * @return string
     */
    public function method()
    {
        return $this->method;
    }

    /**
     * Request URL i.e. /foo
     *
     * @return string
     */
    public function uri()
    {
        return $this->uri;
    }

    /**
     * Allow accessing parsed headers
     *
     * @param string $key
     * @param string $default
     *
     * @return strung
     */
    public function header($key, $default = null)
    {
        return isset($this->headers[$key]) ? $this->headers[$key] : null;
    }

    /**
     * Allow accessing parsed params
     *
     * @param string $key
     * @param string $default
     *
     * @return strung
     */
    public function param($key, $default = null)
    {
        return isset($this->parameters[$key]) ? $this->parameters[$key] : null;
    }
}
