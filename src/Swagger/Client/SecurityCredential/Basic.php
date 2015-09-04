<?php
namespace Swagger\Client\SecurityCredential;

use Zend\Http\Client as HttpClient;

class Basic extends AbstractSecurityCredential
{
    public function configureHttpClient(HttpClient $client)
    {
        
    }
}
