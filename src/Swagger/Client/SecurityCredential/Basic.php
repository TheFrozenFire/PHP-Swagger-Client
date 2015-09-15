<?php
namespace Swagger\Client\SecurityCredential;

use Zend\Http\Client as HttpClient;

class Basic extends AbstractSecurityCredential
{
    protected $username;
    
    protected $password;

    public function __construct(
        $username,
        $password
    )
    {
        $this->setUsername($username);
        $this->setPassword($password);
    }

    public function configureHttpClient(HttpClient $client)
    {
        $client->setAuth($this->getUsername(), $this->getPassword());
    }
    
    public function getUsername()
    {
        return $this->username;
    }
    
    protected function setUsername($username)
    {
        $this->username = $username;
        return $this;
    }
    
    public function getPassword()
    {
        return $this->password;
    }
    
    protected function setPassword($password)
    {
        $this->password = $password;
        return $this;
    }
}
