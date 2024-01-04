<?php

namespace Xanax\Enumeration;

abstract class ServerIndices
{
    /**
     * The filename of the currently executing script, relative to the document root
     */
    const CURRENTLY_EXECUTING_SCRIPT_FILENAME = 'PHP_SELF';

    /**
     * Array of arguments passed to the script
     */
    const PASSED_ARRAY_ARGUMENTS = 'argv';

    /**
     * Contains the number of command line parameters passed to the script
     */
    const PASSED_ARGUMENTS_COUNT = 'argc';

    /**
     * What revision of the CGI specification the server is using
     */
    const CGI_REVISION = 'GATEWAY_INTERFACE';

    /**
     * The IP address of the server under which the current script is executing
     */
    const SERVER_IP_ADDRESS = 'SERVER_ADDR';

    /**
     * The name of the server host under which the current script is executing
     */
    const SERVER_HOST_NAME = 'SERVER_NAME';

    /**
     * Server identification string, given in the headers when responding to requests.
     */
    const SERVER_SOFTWARE_NAME = 'SERVER_SOFTWARE';

    /**
     * Name and revision of the information protocol via which the page was requested
     */
    const SERVER_PROTOCOL = 'SERVER_PROTOCOL';

    /**
     * Which request method was used to access the page
     */
    const REQUEST_METHOD = 'REQUEST_METHOD';

    /**
     * The timestamp of the start of the request
     */
    const REQUEST_TIMESTAMP = 'REQUEST_TIME';

    /**
     * The query string, if any, via which the page was accessed.
     */
    const QUERY_STRING = 'QUERY_STRING';

    /**
     * The document root directory under which the current script is executing
     */
    const DOCUMENT_ROOT_DIRECTORY = 'DOCUMENT_ROOT';

    /** 
     * Set to a non-empty value if the script was queried through the HTTPS protocol.
     **/
    const HTTPS = 'HTTPS';

    /**
     * The IP address from which the user is viewing the current page.
     */
    const REMOTE_IP_ADDRESS = 'REMOTE_ADDR';

    /**
     * The Host name from which the user is viewing the current page
     */
    const REMOTE_HOST_NAME = 'REMOTE_HOST';

    /**
     * The port being used on the user's machine to communicate with the web server.
     */
    const REMOTE_PORT = 'REMOTE_PORT';

    /**
     * The authenticated user.
     */
    const REMOTE_USER = 'REMOTE_USER';

    /**
     * The absolute pathname of the currently executing script.
     */
    const SCRIPT_ABSOLUTE_PATHNAME = 'SCRIPT_FILENAME';

    /**
     * The port on the server machine being used by the web server for communication
     */
    const SERVER_PORT = 'SERVER_PORT';

    const REQUEST_URI = 'REQUEST_URI';

    const HEADER_X_FORWARDED_AWS_ELB = 'HEADER_X_FORWARDED_AWS_ELB';

    const HEADER_X_FORWARDED_PROTO = 'HEADER_X_FORWARDED_PROTO';

    const HEADER_X_FORWARDED_PORT = 'HEADER_X_FORWARDED_PORT';

    const HEADER_X_FORWARDED_HOST = 'HEADER_X_FORWARDED_HOST';

    const HEADER_X_FORWARDED_FOR = 'HEADER_X_FORWARDED_FOR';

    const X_HTTP_METHOD_OVERRIDE = 'X-HTTP-METHOD-OVERRIDE';

    const CONTENT_LENGTH = 'CONTENT_LENGTH';

    const REQUEST_SCHEME = 'REQUEST_SCHEME';

    const APP_POOL_CONFIG = 'APP_POOL_CONFIG';

    const APPL_MD_PATH = 'APPL_MD_PATH';

    const HTTP_X_REWRITE_URL = 'HTTP_X_REWRITE_URL';

    const HTTP_CONNECTION = 'HTTP_CONNECTION';

    const HTTP_REFERER = 'HTTP_REFERER';

    const HTTP_ACCEPT = 'HTTP_ACCEPT';

    const HTTP_CONTENT_TYPE = 'HTTP_CONTENT_TYPE';

    const CONTENT_TYPE = 'CONTENT_TYPE';

    const SERVER_SIGNATURE = 'SERVER_SIGNATURE';

    const HTTP_USER_AGENT = 'HTTP_USER_AGENT';

    const HTTP_ACCEPT_ENCODING = 'HTTP_ACCEPT_ENCODING';

    const HTTP_X_REQUESTED_WITH = 'HTTP_X_REQUESTED_WITH';

    const HTTP_ACCEPT_LANGUAGE = 'HTTP_ACCEPT_LANGUAGE';

    const HTTP_X_WAP_PROFILE = 'HTTP_X_WAP_PROFILE';

    const HTTP_DEVICE_STOCK_UA = 'HTTP_DEVICE_STOCK_UA';

    const REQUEST_TIME_FLOAT = 'REQUEST_TIME_FLOAT';

    const HTTP_X_UCBROWSER_DEVICE_UA = 'HTTP_X_UCBROWSER_DEVICE_UA';

    const HTTP_X_BOLT_PHONE_UA = 'HTTP_X_BOLT_PHONE_UA';

    const HTTP_X_SKYFIRE_PHONE = 'HTTP_X_SKYFIRE_PHONE';

    const HTTP_X_OPERAMINI_PHONE_UA = 'HTTP_X_OPERAMINI_PHONE_UA';

    const HTTP_CF_CONNECTING_IP = 'HTTP-CF-CONNECTING-IP';

    const HTTP_CLIENT_IP = 'HTTP_CLIENT_IP';

    const APPL_PHYSICAL_PATH = 'APPL_PHYSICAL_PATH';

    const APP_POOL_ID = 'APP_POOL_ID';

    const HTTP_ACCEPT_CHARSET = 'HTTP_ACCEPT_CHARSET';

    const HTTP_X_FORWARDED_PROTO = 'HTTP_X_FORWARDED_PROTO';
    const HTTP_X_FORWARDED_FOR = 'HTTP_X_FORWARDED_FOR';
}
