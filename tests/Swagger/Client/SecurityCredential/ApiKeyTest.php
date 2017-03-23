<?php
namespace Swagger\Client\SecurityCredential;

use PHPUnit_Framework_TestCase as TestCase;
use \Swagger\Object\SecurityScheme as SecurityScheme;
use \Swagger\AbstractTest as AbstractTest;
use \Swagger\Document as Document;

class ApiKeyTest extends AbstractTest
{

    /**
     * Test setting api key scheme with apiKey type
     */
    public function testSetSchemeWithApiKeySchemeType()
    {
        $spec = file_get_contents(static::$swaggerUrlWithApiKeyAuth);
        $decodedSpec = json_decode($spec);
        $document = new Document($decodedSpec);
        $scheme = $document->getSecuritySchemeOfType(SecurityScheme::TYPE_APIKEY);
        $apiKey = new ApiKey('testApiKey', $scheme);

        $this->assertEquals($apiKey->getScheme()->getType(), SecurityScheme::TYPE_APIKEY);
    }

    /**
     * Test setting api key scheme with not apiKey type
     */
    public function testSetSchemeWithNotApiKeySchemeType()
    {
        $this->setExpectedException(\InvalidArgumentException::class);
        $spec = file_get_contents((static::$swaggerUrlWithBasicAuth));
        $decodedSpec = json_decode($spec);
        $document = new Document($decodedSpec);
        $scheme = $document->getSecuritySchemeOfType(SecurityScheme::TYPE_BASIC);
        new ApiKey('testApiKey', $scheme);
    }

}
