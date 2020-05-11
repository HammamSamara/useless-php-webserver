<?php

namespace BuildX\PHPServer;

class Response
{
    protected $status = 200;

    protected $body = '';

    protected $headers = [];

    function __construct($body, $status= null)
    {
        if ($status) {
            $this->status = $status;
        }

        $this->body = $body;

        // Set initial headers
        $this->headers = [
            'Date' => gmdate('D, d M Y H:i:s T'),
            'Server' => 'PHPServer/1.0.0 (Whatever)',
            'Content-Type' => 'text/html; charset=utf-8',
        ];

    }

    /**
     * To be able to add new header parameters
     *
     * @param string $key
     * @param string $value
     *
     * @return void
     */
    public function header($key, $value)
    {
        $this->headers[ucfirst($key)] = $value;
    }

    /**
     * Build response header string
     *
     * @return string
     */
    protected function buildHeaderString()
    {
        $lines = [];

        // Response status
        $lines[] = "HTTP/1.1 " . $this->status . " " . static::$statusCodes[$this->status];

        foreach ($this->headers as $key => $value) {
            $lines[] = "$key: $value";
        }

        return implode(" \r\n", $lines) . "\r\n\r\n";
    }

    /**
     * Magic method to convert response to transportable one
     *
     * Example valid response:
     * HTTP/1.1 200 OK
     * Date: 13.09.1959
     * Server: PHPServer
     *
     * @return string
     */
    public function __toString()
    {
        return $this->buildHeaderString() . $this->body;
    }

    /**
     * Build error response
     *
     * @param string $status
     *
     * @return Response
     */
    public static function error($status)
    {
        return new static("<h1>PHPServer: " . $status . " - " . static::$statusCodes[$status] . "</h1>", $status);
    }

    /**
     * This array pretty much covers the http status codes definitions. Taken from CCF.
     *
     * @var array
     */
    protected static $statusCodes = [
        // Informational 1xx
        100 => 'Continue',
        101 => 'Switching Protocols',

        // Success 2xx
        200 => 'OK',
        201 => 'Created',
        202 => 'Accepted',
        203 => 'Non-Authoritative Information',
        204 => 'No Content',
        205 => 'Reset Content',
        206 => 'Partial Content',

        // Redirection 3xx
        300 => 'Multiple Choices',
        301 => 'Moved Permanently',
        302 => 'Found', // 1.1
        303 => 'See Other',
        304 => 'Not Modified',
        305 => 'Use Proxy',
        // 306 is deprecated but reserved
        307 => 'Temporary Redirect',

        // Client Error 4xx
        400 => 'Bad Request',
        401 => 'Unauthorized',
        402 => 'Payment Required',
        403 => 'Forbidden',
        404 => 'Not Found',
        405 => 'Method Not Allowed',
        406 => 'Not Acceptable',
        407 => 'Proxy Authentication Required',
        408 => 'Request Timeout',
        409 => 'Conflict',
        410 => 'Gone',
        411 => 'Length Required',
        412 => 'Precondition Failed',
        413 => 'Request Entity Too Large',
        414 => 'Request-URI Too Long',
        415 => 'Unsupported Media Type',
        416 => 'Requested Range Not Satisfiable',
        417 => 'Expectation Failed',

        // Server Error 5xx
        500 => 'Internal Server Error',
        501 => 'Not Implemented',
        502 => 'Bad Gateway',
        503 => 'Service Unavailable',
        504 => 'Gateway Timeout',
        505 => 'HTTP Version Not Supported',
        509 => 'Bandwidth Limit Exceeded'
    ];
}