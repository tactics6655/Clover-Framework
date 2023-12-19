<?php

declare(strict_types=1);

namespace Xanax\Classes\HTTP;

use Xanax\Enumeration\HTTPRequestMethod;
use Xanax\Classes\HTTP\Request as RequestHandler;
use Xanax\Classes\HTTP\Request as HTTPRequest;

class RequestDependency
{
    public function __construct()
    {
        $this->getParameters = RequestHandler::getExtractedGetParameters();
        $this->postParameters = RequestHandler::getExtractedPostParameters();
        $this->requestMethod = HTTPRequest::getRequestMethod();
        $this->acceptLanguage = HTTPRequest::getAcceptLanguage();
    }
 
    public function get($key)
    {
        return $this->getParameters[$key];
    }
    
    public function post($key)
    {
        return $this->postParameters[$key];
    }
    
}