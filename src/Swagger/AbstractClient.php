<?php
namespace Swagger;

use Zend\Http;
use Zend\Http\Client as HttpClient;
use Zend\Http\Response as HttpResponse;

abstract class AbstractClient
{
    protected $document;
    
    protected $httpClient;
    
    protected $schemaResolver;

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
    
    public function parseResponse($operationId, HttpResponse $response, $mimeType = null)
    {
        $dataType = $this->getDocument()
            ->getSchemaForOperationResponse(
                $operationId,
                $response->getStatusCode()
            );
        
        $responseObject = $this->convertResponseBodyToObject($response, $mimeType);
        
        $result = $this->getSchemaResolver()
            ->parseDataObject($dataType, $responseObject);
    
        return new Client\Response(
            $response->getHeaders()
                ->toArray(),
            $result
        );
    }
    
    protected function convertResponseBodyToObject(HttpResponse $response, $mimeType = null)
    {
        if(empty($mimeType)) {
            if(
                $response->getHeaders()
                    ->has('Content-Type')
            ) {
                $mimeType = $response->getHeaders()
                    ->get('Content-Type')
                    ->getFieldValue();
            } else {
                $mimeType = reset(
                    $this->getDocument()
                        ->getProduces()
                );
            }
        }
        
        switch($mimeType) {
            case 'application/json':
                return json_decode($response->getBody());
                break;
            case 'application/xml':
                return json_decode(json_encode(simplexml_load_string($response->getBody())), true);
                break;
            default:
                throw new \UnexpectedValueException('Response format not supported');
        }
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
    
    public function getSchemaResolver()
    {
        if(!$this->schemaResolver) {
            $this->schemaResolver = new SchemaResolver($this->getDocument());
        }
    
        return $this->schemaResolver;
    }
    
    public function setSchemaResolver($schemaResolver)
    {
        $this->schemaResolver = $schemaResolver;
        return $this;
    }
}
