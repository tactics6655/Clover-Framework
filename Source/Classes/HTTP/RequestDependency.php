<?php

declare(strict_types=1);

namespace Xanax\Classes\HTTP;

use Xanax\Enumeration\HTTPRequestMethod;
use Xanax\Classes\HTTP\Request as RequestHandler;
use Xanax\Classes\HTTP\Request as HTTPRequest;

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
        $this->requestMethod = HTTPRequest::getRequestMethod();
        $this->acceptLanguage = HTTPRequest::getAcceptLanguage();
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
