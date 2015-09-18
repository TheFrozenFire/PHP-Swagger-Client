<?php
namespace Swagger;

use Swagger\Exception as SwaggerException;
use Zend\Http\Response as HttpResponse;

class Client extends AbstractClient
{
    protected $defaultSecurityCredential;

    public static function create(
        $swaggerUrl
    )
    {
        $spec = file_get_contents($swaggerUrl);
        $decodedSpec = json_decode($spec);
        
        $document = new Document($decodedSpec);
        
        $document->setDefaultScheme(parse_url($swaggerUrl, PHP_URL_SCHEME));
        
        return new static($document);
    }
    
    public function createBasicCredential(
        $username,
        $password
    )
    {
        return new Client\SecurityCredential\Basic($username, $password);
    }
    
    public function createApiKeyCredential(
        $apiKey
    )
    {
        $scheme = $this->getDocument()
            ->getSecuritySchemeOfType(Object\SecurityScheme::TYPE_APIKEY);
    
        return new Client\SecurityCredential\ApiKey($apiKey, $scheme);
    }
    
    public function createOAuth2Credential()
    {
        throw new \BadMethodCallException('Not implemented');
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
    
    public function execute(Client\Request $request)
    {
        $httpResponse = $this->request($request);
        
        $response = $this->parseResponse(
            $request->getOperationConfig()
                ->getOperation()
                ->getOperationId(),
            $httpResponse
        );
        
        return $response;
    }
    
    public function request(Client\Request $request, $reset = true)
    {
        $this->configureHttpClient($request, $reset);
        
        $client = $this->getHttpClient();
        
        $response = $client->send();
        
        return $response;
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
