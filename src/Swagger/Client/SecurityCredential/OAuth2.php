<?php
namespace Swagger\Client\SecurityCredential;

use Zend\Http\Client as HttpClient;

class OAuth2 extends AbstractSecurityCredential
{
    public function configureHttpClient(HttpClient $client)
    {
        
    }
}
