<?php

namespace Clover\Enumeration;

enum HTTPStatusCode: int
{
    const CONTINUE = 100;

    const SWITCHING_PROTOCOLS = 101;

    const PROCESSING = 102;

    const EARLY_HINTS = 103;

    const OK = 200;

    const CREATED = 201;

    const ACCEPTED = 202;

    const NON_AUTHORITATIVE_INFORMATION = 203;

    const NO_CONTENT = 204;

    const RESET_CONTENT = 205;

    const PARTIAL_CONTENT = 206;

    const MULTI_STATUS = 207;

    const ALREADY_REPORTED = 208;

    const IM_USED = 226;

    const MULTIPLE_CHOICES = 300;

    const MOVED_PERMANENTLY = 301;

    const FOUND = 302;

    const SEE_OTHER = 303;

    const NOT_MODIFIED = 304;

    const USE_PROXY = 305;

    const UNUSED = 306;

    const TEMPORARY_REDIRECT = 307;

    const PERMANENT_REDIRECT = 308;

    const BAD_REQUEST = 400;

    const UNAUTHORIZED = 401;

    const PAYMENT_REQUIRED = 402;

    const FORBIDDEN = 403;

    const NOT_FOUND = 404;

    const METHOD_NOT_ALLOWED = 405;

    const NOT_ACCEPTABLE = 406;

    const PROXY_AUTHENTICATION_REQUIRED = 407;

    const REQUEST_TIMEOUT = 408;

    const CONFLICT = 409;

    const GONE = 410;

    const LENGTH_REQUIRED = 411;

    const PRECONDITION_FAILED = 412;

    const REQUEST_ENTITY_TOO_LARGE = 413;

    const REQUEST_URI_TOO_LONG = 414;

    const UNSUPPORTED_MEDIA_TYPE = 415;

    const REQUESTED_RANGE_NOT_SATISFIABLE = 416;

    const EXPECTATION_FAILED = 417;

    const IM_A_TEAPOT = 418;

    const MISDIRECTED_REQUEST = 421;

    const UNPROCESSABLE_ENTITY = 422;

    const LOCKED = 423;

    const FAILED_DEPENDENCY = 424;

    const TOO_EARLY = 425;

    const UPGRADE_REQUIRED = 426;

    const PRECONDITION_REQUIRED = 428;

    const TOO_MANY_REQUESTS = 429;

    const REQUEST_HEADER_FIELDS_TOO_LARGE = 431;

    const CONNECTION_CLOSED_WITHOUT_RESPONSE = 444;

    const UNAVAILABLE_FOR_LEGAL_REASONS = 451;

    const CLIENT_CLOSED_REQUEST = 499;

    const INTERNAL_SERVER_ERROR = 500;

    const NOT_IMPLEMENTED = 501;

    const BAD_GATEWAY = 502;

    const SERVICE_UNAVAILABLE = 503;

    const GATEWAY_TIMEOUT = 504;

    const HTTP_VERSION_NOT_SUPPORTED = 505;

    const VARIANT_ALSO_NEGOTIATES = 506;

    const INSUFFICIENT_STORAGE = 507;

    const LOOP_DETECTED = 508;

    const BANDWIDTH_LIMIT_EXCEEDED = 509;
    
    const NOT_EXTENDED = 510;

    const NETWORK_AUTHENTICATION_REQUIRED = 511;

    const NETWORK_CONNECT_TIMEOUT_ERROR = 599;
    
    const WEBSERVER_IS_RETURNING_AN_UNKNOWN_ERROR = 520;

    const CONNECTION_TIMEOUT = 522;

    const A_TIMEOUT_OCCURRED = 524;

    public function Message(): string {
        return match($this) {
            HTTPStatusCode::CONTINUE => 'Continue',
            HTTPStatusCode::SWITCHING_PROTOCOLS => 'Switching Protocols',
            HTTPStatusCode::PROCESSING => 'Processing',
            HTTPStatusCode::EARLY_HINTS => 'Early Hints',
            HTTPStatusCode::OK => 'OK',
            HTTPStatusCode::CREATED => 'Created',
            HTTPStatusCode::ACCEPTED => 'Accepted',
            HTTPStatusCode::NON_AUTHORITATIVE_INFORMATION => 'Non-Authoritative Information',
            HTTPStatusCode::NO_CONTENT => 'No Content',
            HTTPStatusCode::RESET_CONTENT => 'Reset Content',
            HTTPStatusCode::PARTIAL_CONTENT => 'Partial Content',
            HTTPStatusCode::MULTI_STATUS => 'Multi-Status',
            HTTPStatusCode::ALREADY_REPORTED => 'Already Reported',
            HTTPStatusCode::IM_USED => 'IM Used',
            HTTPStatusCode::MULTIPLE_CHOICES => 'Multiple Choices',
            HTTPStatusCode::MOVED_PERMANENTLY => 'Moved Permanently',
            HTTPStatusCode::FOUND => 'Found',
            HTTPStatusCode::SEE_OTHER => 'See Other',
            HTTPStatusCode::NOT_MODIFIED => 'Not Modified',
            HTTPStatusCode::USE_PROXY => 'Use Proxy',
            HTTPStatusCode::TEMPORARY_REDIRECT => 'Temporary Redirect',
            HTTPStatusCode::PERMANENT_REDIRECT => 'Permanent Redirect',
            HTTPStatusCode::BAD_REQUEST => 'Bad Request',
            HTTPStatusCode::UNAUTHORIZED => 'Unauthorized',
            HTTPStatusCode::PAYMENT_REQUIRED => 'Payment Required',
            HTTPStatusCode::FORBIDDEN => 'Forbidden',
            HTTPStatusCode::NOT_FOUND => 'Not Found',
            HTTPStatusCode::METHOD_NOT_ALLOWED => 'Method Not Allowed',
            HTTPStatusCode::NOT_ACCEPTABLE => 'Not Acceptable',
            HTTPStatusCode::PROXY_AUTHENTICATION_REQUIRED => 'Proxy Authentication Required',
            HTTPStatusCode::REQUEST_TIMEOUT => 'Request Timeout',
            HTTPStatusCode::CONFLICT => 'Conflict',
            HTTPStatusCode::GONE => 'Gone',
            HTTPStatusCode::LENGTH_REQUIRED => 'Length Required',
            HTTPStatusCode::PRECONDITION_FAILED => 'Precondition Failed',
            HTTPStatusCode::REQUEST_ENTITY_TOO_LARGE => 'Request Entity Too Large',
            HTTPStatusCode::REQUEST_URI_TOO_LONG => 'Request-URI Too Long',
            HTTPStatusCode::UNSUPPORTED_MEDIA_TYPE => 'Unsupported Media Type',
            HTTPStatusCode::REQUESTED_RANGE_NOT_SATISFIABLE => 'Requested Range Not Satisfiable',
            HTTPStatusCode::EXPECTATION_FAILED => 'Expectation Failed',
            HTTPStatusCode::IM_A_TEAPOT => 'I\'m a teapot',
            HTTPStatusCode::MISDIRECTED_REQUEST => 'Misdirected Request',
            HTTPStatusCode::UNPROCESSABLE_ENTITY => 'Unprocessable Entity',
            HTTPStatusCode::LOCKED => 'Locked',
            HTTPStatusCode::FAILED_DEPENDENCY => 'Failed Dependency',
            HTTPStatusCode::TOO_EARLY => 'Too Early',
            HTTPStatusCode::UPGRADE_REQUIRED => 'Upgrade Required',
            HTTPStatusCode::PRECONDITION_REQUIRED => 'Precondition Required',
            HTTPStatusCode::TOO_MANY_REQUESTS => 'Too Many Requests',
            HTTPStatusCode::REQUEST_HEADER_FIELDS_TOO_LARGE => 'Request Header Fields Too Large',
            HTTPStatusCode::CONNECTION_CLOSED_WITHOUT_RESPONSE => 'Connection Closed Without Response',
            HTTPStatusCode::UNAVAILABLE_FOR_LEGAL_REASONS => 'Unavailable For Legal Reasons',
            HTTPStatusCode::CLIENT_CLOSED_REQUEST => 'Client Closed Request',
            HTTPStatusCode::INTERNAL_SERVER_ERROR => 'Internal Server Error',
            HTTPStatusCode::NOT_IMPLEMENTED => 'Not Implemented',
            HTTPStatusCode::BAD_GATEWAY => 'Bad Gateway',
            HTTPStatusCode::SERVICE_UNAVAILABLE => 'Service Unavailable',
            HTTPStatusCode::GATEWAY_TIMEOUT => 'Gateway Timeout',
            HTTPStatusCode::HTTP_VERSION_NOT_SUPPORTED => 'HTTP Version Not Supported',
            HTTPStatusCode::VARIANT_ALSO_NEGOTIATES => 'Variant Also Negotiates',
            HTTPStatusCode::INSUFFICIENT_STORAGE => 'Insufficient Storage',
            HTTPStatusCode::LOOP_DETECTED => 'Loop Detected',
            HTTPStatusCode::BANDWIDTH_LIMIT_EXCEEDED => 'Bandwidth Limit Exceeded',
            HTTPStatusCode::NOT_EXTENDED => 'Not Extended',
            HTTPStatusCode::NETWORK_AUTHENTICATION_REQUIRED => 'Network Authentication Required',
            HTTPStatusCode::NETWORK_CONNECT_TIMEOUT_ERROR => 'Network Connect Timeout Error',
            HTTPStatusCode::WEBSERVER_IS_RETURNING_AN_UNKNOWN_ERROR => 'Webserver is Returning an Unknown Error',
            HTTPStatusCode::CONNECTION_TIMEOUT => 'Connection Timeout',
            HTTPStatusCode::A_TIMEOUT_OCCURRED => 'A Timeout Occurred',
        };
    }
}
