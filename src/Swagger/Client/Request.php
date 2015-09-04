<?php
namespace Swagger\Client;

use Swagger\Document as SwaggerDocument;
use Swagger\Document\Object as SwaggerObject;

use Zend\Http\Client as HttpClient;
use Zend\Http\Request as HttpRequest;

class Request
{
    protected $document;
    
    protected $operationConfig;
    
    protected $securityCredential;

    public function __construct(
        SwaggerDocument $document,
        OperationConfig $operationConfig,
        SecurityCredential $securityCredential = null
    )
    {
        $this->setDocument($document);
        $this->setOperationConfig($operationConfig);
        
        if(!is_null($securityCredential)) {
            $this->setSecurityCredential($securityCredential);
        }
    }
    
    public function configureHttpClient(HttpClient $client)
    {
        $document = $this->getDocument();
        $operationConfig = $this->getOperationConfig();
        $credential = $this->getCredential();
        
        if($credential instanceof SecurityCredential) {
            $credential->configureHttpClient($client);
        }
        
        $path = $document->getPaths()
            ->getPath($operationConfig->getPath());
        
        switch($operationConfig->getMethod()) {
            case $operationConfig::OPERATION_GET:
                $operation = $path->getGet();
                
                $client->setMethod(HttpRequest::METHOD_GET);
                break;
            case OperationConfig::OPERATION_PUT:
                $operation = $path->getPut();
                
                $client->setMethod(HttpRequest::METHOD_PUT);
                break;
            case $operationConfig::OPERATION_POST:
                $operation = $path->getPost();
                
                $client->setMethod(HttpRequest::METHOD_POST);
                break;
            case $operationConfig::OPERATION_DELETE:
                $operation = $path->getDelete();
                
                $client->setMethod(HttpRequest::METHOD_DELETE);
                break;
            case $operationConfig::OPERATION_OPTIONS:
                $operation = $path->getOptions();
                
                $client->setMethod(HttpRequest::METHOD_OPTIONS);
                break;
            case $operationConfig::OPERATION_HEAD:
                $operation = $path->getHead();
                
                $client->setMethod(HttpRequest::METHOD_HEAD);
                break;
            case $operationConfig::OPERATION_PATCH:
                $operation = $path->getPatch();
                
                $client->setMethod(HttpRequest::METHOD_PATCH);
                break;
            default:
                throw new \InvalidArgumentException('Operation type is not supported');
        }
        
        $client->setUri("{$operationConfig->getScheme()}://{$document->getBasePath()}{$operationConfig->getPath()}");
        
        return $client;
    }
    
    protected function getDocument()
    {
        return $this->document;
    }
    
    protected function setDocument(SwaggerDocument $document)
    {
        $this->document = $document;
        return $this;
    }
    
    protected function getPath()
    {
        return $this->path;
    }
    
    protected function setPath($path)
    {
        $this->path = $path;
        return $this;
    }
    
    public function getOperationConfig()
    {
        return $this->operationConfig;
    }
    
    public function setOperationConfig(OperationConfig $operationConfig)
    {
        $this->operationConfig = $operationConfig;
        return $this;
    }
    
    protected function getSecurityCredential()
    {
        return $this->securityCredential;
    }
    
    protected function setSecurityCredential(SecurityCredential $securityCredential)
    {
        $this->securityCredential = $securityCredential;
        return $this;
    }
}
