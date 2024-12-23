<?php

declare(strict_types=1);

namespace Clover\Classes\HTTP;

use Clover\Enumeration\HTTPRequestMethod;
use Clover\Classes\HTTP\Request as RequestHandler;
use Clover\Classes\HTTP\Request as HTTPRequest;

class RequestDependency
{
    private $queryParameters;

    private $postParameters;

    private $requestMethod;

    private $acceptLanguage;

    public function __construct()
    {
        $this->queryParameters = RequestHandler::getExtractedQueryParameters();
        $this->postParameters = RequestHandler::getExtractedPostParameters();
        $this->requestMethod = HTTPRequest::getMethod();
        $this->acceptLanguage = HTTPRequest::parseAcceptLanguage(HTTPRequest::getAcceptLanguage());
    }

    public function queryParameter($key)
    {
        return $this->queryParameters[$key];
    }

    public function postParameter($key)
    {
        return $this->postParameters[$key];
    }
}
