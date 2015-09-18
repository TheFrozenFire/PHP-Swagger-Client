<?php
namespace Swagger\Client;

use Swagger\Object\Response as SwaggerResponse;
use Swagger\DataObject;
use Zend\Http\Response as HttpResponse;

class Response
{
    protected $response;
    
    protected $mimeType;
    
    protected $responseHeaders;
    
    protected $result;

    public function __construct(
        SwaggerResponse $response,
        $mimeType,
        $responseHeaders,
        $result
    )
    {
        $this->setResponse($response);
        $this->setMimeType($mimeType);
        $this->setResponseHeaders($responseHeaders);
        $this->setResult($result);
    }
    
    public function getResponse()
    {
        return $this->response;
    }
    
    protected function setResponse(SwaggerResponse $response)
    {
        $this->response = $response;
        return $this;
    }
    
    public function getMimeType()
    {
        return $this->mimeType;
    }
    
    protected function setMimeType($mimeType)
    {
        $this->mimeType = $mimeType;
        return $this;
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
        if(empty($this->result)) {
            $this->populateResult();
        }
    
        return $this->result;
    }
    
    protected function setResult($result)
    {
        $this->result = $result;
        return $this;
    }
}
