<?php
namespace Swagger\Client\SecurityCredential;

use Zend\Http\Client as HttpClient;

class OAuth2 extends AbstractSecurityCredential
{
    public function __construct()
    {
        throw new \BadMethodCallException('Not implemented');
    }

    public function configureHttpClient(HttpClient $client)
    {
        
    }
}
