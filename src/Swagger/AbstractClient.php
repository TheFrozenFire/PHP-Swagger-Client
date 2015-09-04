<?php
namespace Swagger;

use Zend\Http;
use Zend\Http\Client as HttpClient;

abstract class AbstractClient
{
    protected $document;
    
    protected $httpClient;

    public function __construct(Document $document)
    {
        $this->setDocument($document);
    }
    
    public function configureHttpClient(Client\Request $request, $reset = true)
    {
        $httpClient = $this->getHttpClient();
        
        if($request) {
            $httpClient->resetParameters();
        }
        
        $request->configureHttpClient($httpClient);
        
        return $httpClient;
    }
    
    public function getDocument()
    {
        return $this->document;
    }
    
    public function setDocument(Document $document)
    {
        $this->document = $document;
        return $this;
    }
    
    public function getHttpClient()
    {
        if(!$this->httpClient) {
            $this->httpClient = new HttpClient;
        }
    
        return $this->httpClient;
    }
    
    public function setHttpClient($httpClient)
    {
        $this->httpClient = $httpClient;
        return $this;
    }
}
