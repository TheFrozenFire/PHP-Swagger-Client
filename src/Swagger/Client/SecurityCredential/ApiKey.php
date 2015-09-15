<?php
namespace Swagger\Client\SecurityCredential;

use Zend\Http\Client as HttpClient;
use Swagger\Object\SecurityScheme;

class ApiKey extends AbstractSecurityCredential
{
    protected $apiKey;
    
    protected $scheme;
    
    public function __construct(
        $apiKey,
        SecurityScheme $scheme
    )
    {
        $this->setApiKey($apiKey);
        $this->setScheme($scheme);
    }

    public function configureHttpClient(HttpClient $client)
    {
        $scheme = $this->getScheme();
        $name = $scheme->getName();
        
        switch($scheme->getIn()) {
            case SecurityScheme::IN_HEADER:
                $client->getRequest()
                    ->getHeaders()
                    ->addHeaderLine($name, $this->getApiKey());
                break;
            case SecurityScheme::IN_QUERY:
                $client->getRequest()
                    ->getQuery()
                    ->set($name, $this->getApiKey());
                break;
            default:
                throw new \UnexpectedValueException("'in' parameter of security scheme is not recognized");
        }
    }
    
    public function getApiKey()
    {
        return $this->apiKey;
    }
    
    public function setApiKey($apiKey)
    {
        $this->apiKey = $apiKey;
        return $this;
    }
    
    public function getScheme()
    {
        return $this->scheme;
    }
    
    public function setScheme(SecurityScheme $scheme)
    {
        if($scheme->getType() !== 'apiKey') {
            throw new \InvalidArgumentException("Scheme must be of type 'apiKey', scheme of type '{$scheme->getType()}' provided");
        }
    
        $this->scheme = $scheme;
        return $this;
    }
}
