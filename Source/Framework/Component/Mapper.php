<?php

namespace Clover\Framework\Component;

use Clover\Classes\HTTP\Request as HTTPRequest;
use Clover\Classes\OperationSystem as OperationSystem;
use Clover\Classes\DependencyInjection\Container;
use Clover\Classes\Event\Dispatcher as EventDispatcher;
use Clover\Classes\XML\SimpleXML;

class Mapper
{
    private $method;

    private $acceptEncoding;

    private $remoteIPAddress;

    private $httpConnection;

    private $acceptLanguage;

    private $hasReferer;

    private $isMobile;

    private $isCrawler;

    private $requestUri;

    private $scheme;

    private $domain;

    private $userAgent;

    private $isCommnadLineInterface;

    private $isXMLHttpRequest;

    private $queryString;

    private $requestUrl;

    private $urlPath;

    private $container;

    private $options;

    private $environment;

    private EventDispatcher $eventDispatcher;

    public function __construct($options, $environment)
    {
        $this->options = $options ?? [];
        $this->environment = $environment ?? [];

        $this->setMethod(HTTPRequest::getMethod());
        $this->setAcceptEncoding(HTTPRequest::getAcceptEncoding());
        $this->remoteIPAddress = HTTPRequest::getRemoteIPAddress()->__toString();
        $this->httpConnection = HTTPRequest::getHTTPConnection();
        $this->acceptLanguage = HTTPRequest::parseAcceptLanguage(HTTPRequest::getAcceptLanguage());
        $this->hasReferer = HTTPRequest::hasReferer();
        $this->isMobile = HTTPRequest::isMobile();
        $this->isCrawler = HTTPRequest::isCrawler();
        $this->requestUri = HTTPRequest::getRequestUri()->__toString();
        $this->scheme = HTTPRequest::getScheme();
        $this->userAgent = HTTPRequest::getUserAgent();
        $this->isXMLHttpRequest = HTTPRequest::isAjax();
        $this->queryString = HTTPRequest::getQueryString()->__toString();
        $this->requestUrl = HTTPRequest::getRequestURL()->__toString();
        $this->urlPath = HTTPRequest::getUrlPath();
        $this->isCommnadLineInterface = OperationSystem::isCommandLineInterface();

        $this->eventDispatcher = new EventDispatcher();
    }

    public function setAcceptEncoding($accept_encoding)
    {
        $this->acceptEncoding = $accept_encoding;
    }

    public function setMethod($method)
    {
        $this->method = $method;
    }

    private function getEnvironment()
    {
        return [
            'method' => $this->method,
            'acceptEncoding' => $this->acceptEncoding,
            'remoteIPAddress' => $this->remoteIPAddress,
            'httpConnection' => $this->httpConnection,
            'acceptLanguage' => $this->acceptLanguage,
            'hasReferer' => $this->hasReferer,
            'isMobile' => $this->isMobile,
            'isCrawler' => $this->isCrawler,
            'requestUri' => $this->requestUri,
            'scheme' => $this->scheme,
            'userAgent' => $this->userAgent,
            'isXMLHttpRequest' => $this->isXMLHttpRequest,
            'queryString' => $this->queryString,
            'requestUrl' => $this->requestUrl,
            'urlPath' => $this->urlPath,
        ];
    }

    public function isCommentLineInterface()
    {
        return $this->isCommnadLineInterface;
    }

    private function setContainer()
    {
        $this->container = new Container();

        $parser = new SimpleXML();
        $parser->fromFile(dirname(__ROOT__) . "/Source/Framework/Configure/default_services.xml");
        $services = $parser->getChildren('container')->getChildren('services')->getData();

        /** @var \SimpleXMLElement[] $services */
        foreach ($services as $service) {
            $key = $service->attributes()->key->__toString();
            $class = $service->attributes()->class->__toString();
            $instance = (new $class);
            $this->container->set($key, $instance);
        }
    }

    public function getOptions()
    {
        return $this->options;
    }

    public function boot()
    {
    }

    public function terminate()
    {
    }

    public function matchRunner()
    {
        $this->setContainer();

        $this->boot();

        if ($this->isCommentLineInterface()) {
            return new CliKernel($this->container);
        }

        $response = new HttpKernel($this->eventDispatcher, $this->container, $this->getEnvironment(), $this->getOptions());

        $this->terminate();

        return $response;
    }
}
