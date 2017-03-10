<?php
namespace Swagger;

use Swagger\Exception as SwaggerException;
use Zend\Http\Response as HttpResponse;
use PHPUnit_Framework_TestCase as TestCase;
use Swagger\Exception\UndefinedSecuritySchemeException as UndefinedSecuritySchemeException;

class ClientTest extends AbstractTest
{

    public function testCreate()
    {
        $client = Client::create(static::$swaggerUrlFull);

        $document = $client->getDocument();
        $this->assertEquals($document->getBasePath(), '/testBasePath');
        $this->assertEquals($document->getHost(), 'www.test.com');
        $this->assertNotNull($document->getDefinitions()->getItem('TestResponse'));
        $this->assertNotNull($document->getDefinitions()->getItem('InternalObject1'));
        $this->assertNotNull($document->getDefinitions()->getItem('InternalObject2'));

        $this->assertEquals($document->getSchemes()['0'], 'https');
        $this->assertEquals($document->getConsumes()['0'], 'application/json');
        $this->assertEquals($document->getProduces()['0'], 'application/json');

        $this->assertEquals($document->getInfo()->getTitle(), 'Test Title');
        $this->assertEquals($document->getInfo()->getVersion(), 'TestVersion');
        $this->assertEquals($document->getInfo()->getDescription(), 'Test');

        $this->assertNotNull($document->getPaths()->getItem('/testGet/{testPathParam}'));
        $this->assertNotNull($document->getSecurityDefinitions()->getItem('basicAuth'));
    }

    public function testCreateBasicCredentialsWithBasicScheme()
    {
        $client = Client::create(static::$swaggerUrlWithBasicAuth);
        $creds = $client->createBasicCredential("testLogin", "testPassword");

        $this->assertNotNull($creds);
        $this->assertEquals($creds->getPassword(), 'testPassword');
        $this->assertEquals($creds->getUsername(), 'testLogin');
    }

    public function testCreateBasicCredentialsWithoutBasicScheme()
    {
        $client = Client::create(static::$swaggerUrlWithApiKeyAuth);
        $creds = $client->createBasicCredential("testLogin", "testPassword");

        $this->assertNotNull($creds);
        $this->assertEquals($creds->getPassword(), 'testPassword');
        $this->assertEquals($creds->getUsername(), 'testLogin');
    }

    public function testCreateApiKeyCredentialWithoutApiKeyScheme()
    {
        $this->setExpectedException(UndefinedSecuritySchemeException::class);

        $client = Client::create(static::$swaggerUrlWithBasicAuth);
        $client->createApiKeyCredential("test");
    }

    public function testCreateApiKeyCredentialWithApiKeyScheme()
    {
        $client = Client::create(static::$swaggerUrlWithApiKeyAuth);
        $creds = $client->createApiKeyCredential("test");

        $this->assertNotNull($creds);
        $this->assertEquals($creds->getApiKey(), 'test');
    }

    public function testCreateRequestWithoutAuth()
    {
        $client = Client::create(static::$swaggerUrlFull);
        $request = $client->createRequest('testGet');

        $this->assertNotNull($request->getOperationConfig());
        $this->assertEquals($request->getOperationConfig()->getMediaType(), 'application/json');
        $this->assertEquals($request->getOperationConfig()->getScheme(), 'https');

        $this->assertNotNull($request->getOperationConfig()->getOperation());
        $this->assertEquals($request->getOperationConfig()->getOperation()->getOperationId(), 'testGet');
        $this->assertEquals($request->getOperationConfig()->getOperation()->getPath(), '/testGet/{testPathParam}');
        $this->assertEquals($request->getOperationConfig()->getOperation()->getMethod(), 'GET');

        $itemOperation = $request->getOperationConfig()->getOperation()->getPathItem()->getGet();
        $this->assertNotNull($itemOperation);
        $this->assertEquals($itemOperation->getSummary(), 'Test Get');
        $this->assertEquals($itemOperation->getDescription(), 'Test Get');
        $this->assertEquals($itemOperation->getOperationId(), 'testGet');
        $this->assertEquals($itemOperation->getConsumes()[0], 'application/json');
        $this->assertEquals($itemOperation->getProduces()[0], 'application/json');

        $this->assertNotNull($itemOperation->getResponses()->getItem(200));
        $this->assertEquals($itemOperation->getResponses()->getItem(200)->getDescription(), 'successful operation');

        //TO DO: uncomment after fix
        //Fatal error: Cannot instantiate abstract class Swagger\Object\Parameter in C:\workspace\php\forked\PHP-Swagger-Client\vendor\thefrozenfire\swagger\src\Swagger\Object\AbstractObject.php on line 70
        //$itemParameters = $itemOperation->getParameters();
        //$this->assertNotNull($itemParameters);

        //TO DO: uncomment after fix
        //Fatal error: Class 'Swagger\Object\Security' not found in C:\workspace\php\forked\PHP-Swagger-Client\vendor\thefrozenfire\swagger\src\Swagger\Object\AbstractObject.php on line 70
        //$itemSecurity = $itemOperation->getSecurity();
        //$this->assertNotNull($itemSecurity);

        $operation = $request->getOperationConfig()->getOperation()->getOperation();
        $this->assertEquals($operation->getSummary(), 'Test Get');
        $this->assertEquals($operation->getDescription(), 'Test Get');
        $this->assertEquals($operation->getOperationId(), 'testGet');
        $this->assertEquals($operation->getConsumes()[0], 'application/json');
        $this->assertEquals($operation->getProduces()[0], 'application/json');

        $this->assertNotNull($operation->getResponses()->getItem(200));
        $this->assertEquals($operation->getResponses()->getItem(200)->getDescription(), 'successful operation');

        //TO DO: uncomment after fix
        //Fatal error: Cannot instantiate abstract class Swagger\Object\Parameter in C:\workspace\php\forked\PHP-Swagger-Client\vendor\thefrozenfire\swagger\src\Swagger\Object\AbstractObject.php on line 70
        //$parameters = $operation->getParameters();
        //$this->assertNotNull($parameters);

        //TO DO: uncomment after fix
        //Fatal error: Class 'Swagger\Object\Security' not found in C:\workspace\php\forked\PHP-Swagger-Client\vendor\thefrozenfire\swagger\src\Swagger\Object\AbstractObject.php on line 70
        //$security = $itemOperation->getSecurity();
        //$this->assertNotNull($security);
    }

}
