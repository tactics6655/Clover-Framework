<?php

namespace Xanax\Framework\Component;

use Xanax\Framework\Component\Renderer;
use Xanax\Framework\Component\Resource;
use Xanax\Classes\HTTP\Request as HTTPRequest;
use Xanax\Classes\OperationSystem as OperationSystem;

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

        $this->method = HTTPRequest::getRequestMethod();
        $this->accept_encoding = HTTPRequest::getAcceptEncoding();
        $this->remote_ip_address = HTTPRequest::getRemoteIP()->__toString();
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

    public function isCommentLineInterface()
    {
        return $this->is_commnadline_interface;
    }

    private function setContainer()
    {
        $this->container = new Container();
        $this->container->Set("Renderer",  new Renderer());
        $this->container->Set("Resource",  new Resource());
    }

    public function matchRunner()
    {
        $this->setContainer();
        
        if ($this->isCommentLineInterface())
        {

        }
        else
        {
            return new HttpKernel($this->container);
        }
    }
}