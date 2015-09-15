<?php
namespace Swagger;

use Swagger\Exception as SwaggerException;

class Client extends AbstractClient
{
    protected $defaultSecurityCredential;

    public static function create(
        $swaggerUrl,
        Client\SecurityCredential $defaultSecurityCredential = null
    )
    {
        $spec = file_get_contents($swaggerUrl);
        $decodedSpec = json_decode($spec);
        
        $document = new Document($decodedSpec);
        
        $document->setDefaultScheme(parse_url($swaggerUrl, PHP_URL_SCHEME));
        
        $client = new static($document);
        
        $client->setDefaultSecurityCredential($defaultSecurityCredential);
        
        return $client;
    }

    public function createRequest(
        $operationId,
        Client\SecurityCredential $swaggerCredential = null
    )
    {
        $document = $this->getDocument();
        
        $operationReference = $document->getOperationById($operationId);
        
        $defaultScheme = $document->getDefaultScheme();
        
        $operationConfig = (new Client\OperationConfig)
            ->setOperation($operationReference)
            ->setScheme($defaultScheme);
            
        $request = new Client\Request(
            $document,
            $operationConfig,
            $swaggerCredential
        );
        
        return $request;
    }

    public function __call($name, $arguments)
    {
        $securityCredential = empty($arguments[0])?$this->getDefaultSecurityCredential():$arguments[0];
    
        return $this->createRequest($name, $securityCredential);
    }
    
    public function execute(Client\Request $request, $reset = true)
    {
        $this->configureHttpClient($request, $reset);
        
        $client = $this->getHttpClient();
        
        return $client->send();
    }
    
    public function getDefaultSecurityCredential()
    {
        return $this->defaultSecurityCredential;
    }
    
    public function setDefaultSecurityCredential(Client\SecurityCredential $defaultSecurityCredential = null)
    {
        $this->defaultSecurityCredential = $defaultSecurityCredential;
        return $this;
    }
}
