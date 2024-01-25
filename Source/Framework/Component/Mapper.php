<?php

namespace Neko\Framework\Component;

use Neko\Framework\Component\Renderer;
use Neko\Framework\Component\Resource;

use Neko\Classes\HTTP\Request as HTTPRequest;
use Neko\Classes\OperationSystem as OperationSystem;
use Neko\Classes\DependencyInjection\Container;

class Mapper
{
    private $method;

    private $accept_encoding;

    private $remote_ip_address;

    private $http_connection;

    private $x_forwared_for;

    private $accept_language;

    private $has_referer;

    private $is_mobile;

    private $is_crawler;

    private $request_uri;

    private $scheme;

    private $domain;

    private $user_agent;

    private $is_commnadline_interface;

    private $is_xml_http_request;

    private $query_string;

    private $request_url;

    private $url_path;

    private $container;

    private $options;

    private $environment;

    public function __construct($options, $environment)
    {
        $this->options = $options ?? [];
        $this->environment = $environment ?? [];

        $this->setMethod(HTTPRequest::getRequestMethod());
        $this->setAcceptEncoding(HTTPRequest::getAcceptEncoding());
        $this->remote_ip_address = HTTPRequest::getRemoteIPAddress()->__toString();
        $this->http_connection = HTTPRequest::getHTTPConnection();
        $this->x_forwared_for = HTTPRequest::getHTTPXForwardedFor();
        $this->accept_language = HTTPRequest::getAcceptLanguage();
        $this->has_referer = HTTPRequest::hasReferer();
        $this->is_mobile = HTTPRequest::isMobile();
        $this->is_crawler = HTTPRequest::isCrawler();
        $this->request_uri = HTTPRequest::getRequestUri()->__toString();
        $this->scheme = HTTPRequest::getScheme();
        $this->user_agent = HTTPRequest::getUserAgent();
        $this->is_xml_http_request = HTTPRequest::isAjax();
        $this->query_string = HTTPRequest::getQueryString()->__toString();
        $this->request_url = HTTPRequest::getRequestURL()->__toString();
        $this->url_path = HTTPRequest::getUrlPath();

        $this->is_commnadline_interface = OperationSystem::isCommandLineInterface();
    }

    public function setAcceptEncoding($accept_encoding)
    {
        $this->accept_encoding = $accept_encoding;
    }

    public function setMethod($method)
    {
        $this->method = $method;
    }

    private function getEnvironment()
    {
        return [
            'method' => $this->method,
            'accept_encoding' => $this->accept_encoding,
            'remote_ip_address' => $this->remote_ip_address,
            'http_connection' => $this->http_connection,
            'x_forwared_for' => $this->x_forwared_for,
            'accept_language' => $this->accept_language,
            'has_referer' => $this->has_referer,
            'is_mobile' => $this->is_mobile,
            'is_crawler' => $this->is_crawler,
            'request_uri' => $this->request_uri,
            'scheme' => $this->scheme,
            'user_agent' => $this->user_agent,
            'is_xml_http_request' => $this->is_xml_http_request,
            'query_string' => $this->query_string,
            'request_url' => $this->request_url,
            'url_path' => $this->url_path,
        ];
    }

    public function isCommentLineInterface()
    {
        return $this->is_commnadline_interface;
    }

    private function setContainer()
    {
        $this->container = new Container();
        $this->container->set("Renderer",  new Renderer());
        $this->container->set("Resource",  new Resource());
    }

    public function getOptions()
    {
        return $this->options;
    }

    public function matchRunner()
    {
        $this->setContainer();

        if ($this->isCommentLineInterface()) {
        } else {
            return new HttpKernel($this->container, $this->getEnvironment(), $this->getOptions());
        }
    }
}
