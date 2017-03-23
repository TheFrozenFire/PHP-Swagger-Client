<?php
namespace Swagger;

use PHPUnit_Framework_TestCase as TestCase;

abstract class AbstractTest extends TestCase
{
    public static $swaggerUrlFull = 'tests/resources/swagger_full.json';
    public static $swaggerUrlWithApiKeyAuth = 'tests/resources/swagger_api_key_scheme.json';
    public static $swaggerUrlWithBasicAuth = 'tests/resources/swagger_basic_scheme.json';

    public function accessProtected($obj, $prop) {
        $reflection = new \ReflectionClass($obj);
        $property = $reflection->getProperty($prop);
        $property->setAccessible(true);
        return $property->getValue($obj);
    }

    public function getDocument()
    {
        $spec = file_get_contents(static::$swaggerUrlFull);
        $decodedSpec = json_decode($spec);
        return new Document($decodedSpec);
    }

    public function getRequest()
    {
        $document = $this->getDocument();
        $operationReference = $document->getOperationById('testGet');

        $operationConfig = (new Client\OperationConfig)
            ->setOperation($operationReference)
            ->setScheme($document->getSchemes()['0']);

        $testQueryParameters = array('testKey' => 'testValue');
        $operationConfig->setQueryParameters($testQueryParameters);

        $testBody = '{"testKey": "testValue"}';
        $operationConfig->setBodyParameter($testBody);

        $testPathParameters = array('testPathParam' => 'testValue');
        $operationConfig->setPathParameters($testPathParameters);

        $testHeaderParameters = array("Content-Type" => "testContentType");
        $operationConfig->setHeaderParameters($testHeaderParameters);

        $request = new Client\Request(
            $document,
            $operationConfig,
            new Client\SecurityCredential\Basic('testUserName', 'testPassword')
        );

        return $request;
    }
}
