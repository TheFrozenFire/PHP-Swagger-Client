<?php
namespace Swagger;

use Swagger\Exception as SwaggerException;
use Zend\Http\Response as HttpResponse;
use PHPUnit_Framework_TestCase as TestCase;

class AbstractClientTest extends AbstractTest
{

    public function testConfigureHttpClientWithAllParametersExceptFileToUpload()
    {
        $request = $this->getRequest();
        $client = new TestableClient($this->getDocument());

        $configuredClient = $client->configureHttpClient($request);

        $this->assertNotNull($configuredClient->getRequest());
        $this->assertEquals($configuredClient->getRequest()->getMethod(), 'GET');
        $this->assertEquals($configuredClient->getRequest()->getUriString(), 'https://www.test.com/testBasePath/testGet/testValue');

        $this->assertNotNull($configuredClient->getRequest()->getQuery());
        $clientQueryParameter = $configuredClient->getRequest()->getQuery()->toArray();
        $this->assertEquals($clientQueryParameter['testKey'], 'testValue');

        $clientHeader = $configuredClient->getRequest()->getHeaders()->get('contenttype');
        $this->assertNotNull($clientHeader);
        $this->assertEquals($clientHeader->toString(), 'Content-Type: testContentType');

        $this->assertEquals($configuredClient->getRequest()->getContent(), '{"testKey": "testValue"}');

        $clientAuth = $this->accessProtected($configuredClient, 'auth');
        $this->assertEquals($clientAuth['user'], 'testUserName');
        $this->assertEquals($clientAuth['password'], 'testPassword');
        $this->assertEquals($clientAuth['type'], 'basic');
    }

    public function testConfigureHttpClientWithFileToUploadParams()
    {
        $request = $this->getRequest();
        $request->getOperationConfig()->setFileUpload('tests\resources\testFile.mp3', 'testFormName');
        $client = new TestableClient($this->getDocument());

        $configuredClient = $client->configureHttpClient($request);

        $clientFileParam = $configuredClient->getRequest()->getFiles()->toArray()['tests\resources\testFile.mp3'];
        $this->assertNotNull($clientFileParam);
        $this->assertEquals($clientFileParam['formname'], 'testFormName');
        $this->assertEquals($clientFileParam['filename'], 'testFile.mp3');
        $this->assertEquals($clientFileParam['ctype'], 'application/octet-stream');
        $this->assertNotNull($clientFileParam['data']);
    }

    public function testConfigureHttpClientWithMediaType()
    {
        $request = $this->getRequest();
        $request->getOperationConfig()->setMediaType("testMediaType");
        $client = new TestableClient($this->getDocument());

        $configuredClient = $client->configureHttpClient($request);

        $clientHeader = $configuredClient->getRequest()->getHeaders()->get('contenttype');
        $this->assertNotNull($clientHeader);
        $this->assertEquals($clientHeader->toString(), 'Content-Type: testMediaType');
    }

    //TO DO: uncomment and test when response parsing would be fixed, for now we have errors
    //Also add some more assertions to be sure json parsed correctly
    /*public function testParseResponse()
    {
        $client = new TestableClient($this->getDocument());
        $request = $this->getRequest();

        $httpResponse = $this->getHttpResponseExample();

        $response = $client->parseResponse(
            $request->getOperationConfig()
                ->getOperation()
                ->getOperationId(),
            $httpResponse
        );

        $this->assertNotNull($response);
    }*/

    public function getHttpResponseExample()
    {
        $httpResponse = new HttpResponse();
        $httpResponse->setStatusCode(200);
        $httpResponse->setReasonPhrase('OK');
        $httpResponse->setVersion('1.1');

        $content = file_get_contents(static::$testResponseJson);
        $httpResponse->setContent(base64_encode($content));

        return $httpResponse;
    }
}

class TestableClient extends AbstractClient
{
}
