<?php
namespace Swagger\Client;

use Swagger\OperationReference;

class OperationConfig
{
    protected $operation;

    protected $scheme;

    protected $mediaType;

    protected $pathParameters = [];

    protected $queryParameters = [];

    protected $headerParameters = [];

    protected $bodyParameter;

    protected $formParameters = [];

    public function getOperation()
    {
        return $this->operation;
    }

    public function setOperation(OperationReference $operation)
    {
        $this->operation = $operation;
        return $this;
    }

    public function getScheme()
    {
        return $this->scheme;
    }

    public function setScheme($scheme)
    {
        $this->scheme = $scheme;
        return $this;
    }

    public function getMediaType()
    {
        return $this->mediaType;
    }

    public function setMediaType($mediaType)
    {
        $this->mediaType = $mediaType;
        return $this;
    }

    public function getPathParameters()
    {
        return $this->pathParameters;
    }

    public function setPathParameters($pathParameters)
    {
        $this->pathParameters = $pathParameters;
        return $this;
    }

    public function addPathParameter($pathParameter)
    {
        $this->pathParameters[] = $pathParameter;
        return $this;
    }

    public function getQueryParameters()
    {
        return $this->queryParameters;
    }

    public function setQueryParameters($queryParameters)
    {
        $this->queryParameters = $queryParameters;
        return $this;
    }

    public function addQueryParameter($queryParameter)
    {
        $this->queryParameters[] = $queryParameter;
        return $this;
    }

    public function getHeaderParameters()
    {
        return $this->headerParameters;
    }

    public function setHeaderParameters($headerParameters)
    {
        $this->headerParameters = $headerParameters;
        return $this;
    }

    public function addHeaderParameter($headerParameter)
    {
        $this->headerParameters[] = $headerParameter;
        return $this;
    }

    public function getBodyParameter()
    {
        return $this->bodyParameter;
    }

    public function setBodyParameter($bodyParameter)
    {
        $this->bodyParameter = $bodyParameter;
        return $this;
    }

    public function getFormParameters()
    {
        return $this->formParameters;
    }

    public function setFormParameters($formParameters)
    {
        $this->formParameters = $formParameters;
        return $this;
    }

    public function addFormParameter($formParameter)
    {
        $this->formParameters[] = $formParameter;
        return $this;
    }

    public function setFileUpload($filename, $formname, $data = null, $ctype = null, $name = null)
    {
        $this->formParameters['filename'] = $filename;
        $this->formParameters['formname'] = $formname;
        $this->formParameters['ctype'] = $ctype;
        $this->formParameters['data'] = $data;
        $this->formParameters['name'] = $name;
    }
}

