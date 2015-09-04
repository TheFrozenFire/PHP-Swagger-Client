<?php
namespace Swagger\Client\SecurityCredential;

use Swagger\Client\SecurityCredential as SecurityCredentialInterface;
use Swagger\Object\SecurityScheme;

use Zend\Http\Client as HttpClient;

abstract class AbstractSecurityCredential implements SecurityCredentialInterface
{
    abstract public function configureHttpClient(HttpClient $client);
}
