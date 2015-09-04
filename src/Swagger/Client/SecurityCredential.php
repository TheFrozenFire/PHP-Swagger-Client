<?php
namespace Swagger\Client;

use Zend\Http\Client as HttpClient;

use Swagger\Object\SecurityScheme;

interface SecurityCredential
{
    public function configureHttpClient(HttpClient $client);
}
