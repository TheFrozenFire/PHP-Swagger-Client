<?php
namespace Swagger\Client;

use Swagger\Object\Response as SwaggerResponse;
use Swagger\DataObject;
use Zend\Http\Response as HttpResponse;

class Response
{
    protected $responseHeaders;
    
    protected $result;

    public function __construct(
        $responseHeaders,
        $result
    )
    {
        $this->setResponseHeaders($responseHeaders);
        $this->setResult($result);
    }
    
    public function getResponseHeaders()
    {
        return $this->responseHeaders;
    }
    
    protected function setResponseHeaders($responseHeaders)
    {
        $this->responseHeaders = $responseHeaders;
        return $this;
    }
    
    public function getResult()
    {
        return $this->result;
    }
    
    protected function setResult($result)
    {
        $this->result = $result;
        return $this;
    }
}
