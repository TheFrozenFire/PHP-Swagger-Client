<?php
namespace Swagger\Client;

use Swagger\Document as SwaggerDocument;
use Swagger\Document\Object as SwaggerObject;

use Zend\Http\Client as HttpClient;
use Zend\Http\Request as HttpRequest;

class Request
{
    protected $document;

    protected $operationConfig;

    protected $securityCredential;

    public function __construct(
        SwaggerDocument $document,
        OperationConfig $operationConfig,
        SecurityCredential $securityCredential = null
    )
    {
        $this->setDocument($document);
        $this->setOperationConfig($operationConfig);
        $this->setSecurityCredential($securityCredential);
    }

    public function configureHttpClient(HttpClient $client)
    {
        $document = $this->getDocument();
        $operationConfig = $this->getOperationConfig();
        $credential = $this->getSecurityCredential();

        if($credential instanceof SecurityCredential) {
            $credential->configureHttpClient($client);
        }

        $operationReference = $operationConfig->getOperation();

        $path = $this->interpolatePathParameters(
            $operationReference->getPath(),
            $operationConfig->getPathParameters()
        );

        $client->setMethod($operationReference->getMethod());
        $mediaType = $operationConfig->getMediaType();
        if($mediaType && $mediaType !== 'multipart/form-data') {
            $client->getRequest()
                ->getHeaders()
                ->addHeaderLine('Content-Type', $mediaType);
        }
        $client->setUri("{$operationConfig->getScheme()}://{$document->getHost()}{$document->getBasePath()}{$path}");

        if($queryParams = $operationConfig->getQueryParameters()) {
            $client->getRequest()
                ->getQuery()
                ->fromArray($queryParams);
        }

        if($headerParams = $operationConfig->getHeaderParameters()) {
            $client->getRequest()
                ->getHeaders()
                ->addHeaders($headerParams);
        }

        if($bodyParam = $operationConfig->getBodyParameter()) {
            $client->setRawBody($bodyParam);
        }

        if($formParams = $operationConfig->getFormParameters()) {
            if (array_key_exists('filename', $formParams)) {
                $client->setFileUpload($formParams['filename'], $formParams['formname'], $formParams['data'], $formParams['ctype']);
                $client->setParameterPost(array('name' => $formParams['name']));
            } else {
                $client->setParameterPost($formParams);
            }
        }

        return $client;
    }

    protected function interpolatePathParameters(
        $path,
        $parameters
    )
    {
        $search = [];
        $replace = [];

        foreach($parameters as $key => $value) {
            $search[] = "{{$key}}";
            $replace[] = $value;
        }

        return str_replace($search, $replace, $path);
    }

    protected function getDocument()
    {
        return $this->document;
    }

    protected function setDocument(SwaggerDocument $document)
    {
        $this->document = $document;
        return $this;
    }

    protected function getPath()
    {
        return $this->path;
    }

    protected function setPath($path)
    {
        $this->path = $path;
        return $this;
    }

    public function getOperationConfig()
    {
        return $this->operationConfig;
    }

    public function setOperationConfig(OperationConfig $operationConfig)
    {
        $this->operationConfig = $operationConfig;
        return $this;
    }

    protected function getSecurityCredential()
    {
        return $this->securityCredential;
    }

    protected function setSecurityCredential(SecurityCredential $securityCredential = null)
    {
        $this->securityCredential = $securityCredential;
        return $this;
    }
}
