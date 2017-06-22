<?php
namespace Swagger\Client;

use PHPUnit_Framework_TestCase as TestCase;
use Zend\Http\Client as HttpClient;
use \Swagger\AbstractTest as AbstractTest;

class RequestTest extends AbstractTest
{

    public function testConfigureHttpClientWithAllParametersExceptFileToUpload()
    {
        $request = $this->getRequest();
        $client = $request->configureHttpClient(new HttpClient());

        $this->assertNotNull($client->getRequest());
        $this->assertEquals($client->getRequest()->getMethod(), 'GET');
        $this->assertEquals($client->getRequest()->getUriString(), 'https://www.test.com/testBasePath/testGet/testValue');

        $this->assertNotNull($client->getRequest()->getQuery());
        $clientQueryParameter = $client->getRequest()->getQuery()->toArray();
        $this->assertEquals($clientQueryParameter['testKey'], 'testValue');

        $clientHeader = $client->getRequest()->getHeaders()->get('contenttype');
        $this->assertNotNull($clientHeader);
        $this->assertEquals($clientHeader->toString(), 'Content-Type: testContentType');

        $this->assertEquals($client->getRequest()->getContent(), '{"testKey": "testValue"}');

        $clientAuth = $this->accessProtected($client, 'auth');
        $this->assertEquals($clientAuth['user'], 'testUserName');
        $this->assertEquals($clientAuth['password'], 'testPassword');
        $this->assertEquals($clientAuth['type'], 'basic');
    }

    public function testConfigureHttpClientWithFileToUploadParams()
    {
        $request = $this->getRequest();

        $request->getOperationConfig()->setFileUpload('tests\resources\testFile.mp3', 'testFormName', null, null, 'testName');
        $client = $request->configureHttpClient(new HttpClient());

        $clientFileParam = $client->getRequest()->getFiles()->toArray()['tests\resources\testFile.mp3'];
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
        $client = $request->configureHttpClient(new HttpClient());

        $clientHeader = $client->getRequest()->getHeaders()->get('contenttype');
        $this->assertNotNull($clientHeader);
        $this->assertEquals($clientHeader->toString(), 'Content-Type: testMediaType');
    }

}
