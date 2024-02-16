<?php

declare(strict_types=1);

namespace Neko\Classes\HTTP;

use Neko\Implement\RequestInterface;
use Neko\Classes\Data\URLObject as URLObject;
use Neko\Classes\Data\StringObject as StringObject;
use Neko\Enumeration\HTTPRequestMethod;
use Neko\Enumeration\ServerIndices;

use litespeed_finish_request;

class Request implements RequestInterface
{

	protected $statusCode = 200;

	protected static $cacheableMethods = [
		HTTPRequestMethod::GET,
		HTTPRequestMethod::HEAD
	];

	protected static $safeMethods = [
		HTTPRequestMethod::GET,
		HTTPRequestMethod::HEAD,
		HTTPRequestMethod::OPTIONS,
		HTTPRequestMethod::TRACE
	];

	protected static $idempotentMethods = [
		HTTPRequestMethod::DELETE,
		HTTPRequestMethod::GET,
		HTTPRequestMethod::HEAD,
		HTTPRequestMethod::OPTIONS,
		HTTPRequestMethod::PUT,
		HTTPRequestMethod::TRACE,
	];

	protected static $statusMesssages = [
		100 => 'Continue',
		101 => 'Switching Protocols',
		102 => 'Processing',
		103 => 'Early Hints',
		200 => 'OK',
		201 => 'Created',
		202 => 'Accepted',
		203 => 'Non-Authoritative Information',
		204 => 'No Content',
		205 => 'Reset Content',
		206 => 'Partial Content',
		207 => 'Multi-Status',
		208 => 'Already Reported',
		226 => 'IM Used',
		300 => 'Multiple Choices',
		301 => 'Moved Permanently',
		302 => 'Found',
		303 => 'See Other',
		304 => 'Not Modified',
		305 => 'Use Proxy',
		307 => 'Temporary Redirect',
		308 => 'Permanent Redirect',
		400 => 'Bad Request',
		401 => 'Unauthorized',
		402 => 'Payment Required',
		403 => 'Forbidden',
		404 => 'Not Found',
		405 => 'Method Not Allowed',
		406 => 'Not Acceptable',
		407 => 'Proxy Authentication Required',
		408 => 'Request Timeout',
		409 => 'Conflict',
		410 => 'Gone',
		411 => 'Length Required',
		412 => 'Precondition Failed',
		413 => 'Request Entity Too Large',
		414 => 'Request-URI Too Long',
		415 => 'Unsupported Media Type',
		416 => 'Requested Range Not Satisfiable',
		417 => 'Expectation Failed',
		418 => "I'm a teapot",
		421 => 'Misdirected Request',
		422 => 'Unprocessable Entity',
		423 => 'Locked',
		424 => 'Failed Dependency',
		425 => 'Too Early',
		426 => 'Upgrade Required',
		428 => 'Precondition Required',
		429 => 'Too Many Requests',
		431 => 'Request Header Fields Too Large',
		444 => 'Connection Closed Without Response',
		451 => 'Unavailable For Legal Reasons',
		499 => 'Client Closed Request',
		500 => 'Internal Server Error',
		501 => 'Not Implemented',
		502 => 'Bad Gateway',
		503 => 'Service Unavailable',
		504 => 'Gateway Timeout',
		505 => 'HTTP Version Not Supported',
		506 => 'Variant Also Negotiates',
		507 => 'Insufficient Storage',
		508 => 'Loop Detected',
		509 => 'Bandwidth Limit Exceeded',
		510 => 'Not Extended',
		511 => 'Network Authentication Required',
		599 => 'Network Connect Timeout Error',
	];

	public static function flushLightSpeedResponseData()
	{
		if (function_exists('litespeed_finish_request')) {
			\litespeed_finish_request();
		}
	}

	/**
	 * Flushes all response data to the client
	 * 
	 * @return void
	 */
	public static function flushFastCgiResponseData()
	{
		if (function_exists('fastcgi_finish_request')) {
			fastcgi_finish_request();
		}
	}

	/**
	 * Get status messages
	 * 
	 * @return string[]
	 */
	public static function getStatusMesssages()
	{
		return self::$statusMesssages;
	}

	public static function getStatusMesssage($code): string
	{
		return self::$statusMesssages[$code] ?? "";
	}

	public static function getBrowserInformation(): array
	{
		$browserInformation = [];

		if (!empty(ini_get('browscap'))) {
			$browserInformation = print_r(get_browser(null, true));
		}

		return $browserInformation;
	}

	public static function isSecure(): bool
	{
		return self::isHttpsProtocol() || self::isForwardedSecure();
	}

	public static function isHttpsProtocol(): bool
	{
		$https = self::getServerArguments(ServerIndices::HTTPS);

		return empty($https) ? false : ($https === 'on' ? true : false);
	}

	public static function getServerArguments($argument)
	{
		if (isset($_SERVER[$argument])) {
			return $_SERVER[$argument];
		}

		return null;
	}

	/**
	 * Fetch all HTTP request headers
	 */
	public static function fetchAllResponseHeaders()
	{
		return getallheaders();
	}

	public static function getUrlPathSegments($preset = null)
	{
		return explode('/', trim($preset ?? self::getUrlPath(), '/'));
	}

	public static function getUrlPath(): string
	{
		return parse_url($_SERVER[ServerIndices::REQUEST_URI] ?? "", PHP_URL_PATH);
	}

	public static function getForwarededPorto()
	{
		return self::getServerArguments(ServerIndices::HEADER_X_FORWARDED_PROTO);
	}

	public static function getContentLength()
	{
		return self::getServerArguments(ServerIndices::CONTENT_LENGTH);
	}

	public static function getOverrideMethod()
	{
		return self::getServerArguments(ServerIndices::X_HTTP_METHOD_OVERRIDE);
	}

	/**
	 * Get timestamp of the start of the request
	 */
	public static function getServerTime()
	{
		return self::getServerArguments(ServerIndices::REQUEST_TIMESTAMP);
	}

	public static function getServerFloatTime()
	{
		return self::getServerArguments(ServerIndices::REQUEST_TIME_FLOAT);
	}

	public static function getScheme()
	{
		return self::getServerArguments(ServerIndices::REQUEST_SCHEME);
	}

	public static function getProtocol(): string
	{
		$protocol = self::getServerArguments(ServerIndices::SERVER_PROTOCOL);
		$lowercase = strtolower($protocol);

		return substr($lowercase, 0, strpos($lowercase, '/'));
	}

	/**
	 * Get Request Uniform Resource Identifier
	 *
	 * @return string
	 */
	public static function getURI()
	{
		return self::getServerArguments(ServerIndices::REQUEST_URI);
	}

	/**
	 * The physical path of the temporary IIS application pool configuration.
	 *
	 * @return string
	 */
	public static function getTemporaryIISApplicationPhysicalPathOfPoolConfiguration()
	{
		return self::getServerArguments(ServerIndices::APP_POOL_CONFIG);
	}

	/**
	 * The metabase path of the application.
	 *
	 * @return string
	 */
	public static function getIISApplicationMetabasePath()
	{
		return self::getServerArguments(ServerIndices::APPL_MD_PATH);
	}

	public static function getHttpHost()
	{
		return self::getServerArguments(ServerIndices::HTTP_HOST);
	}

	/**
	 * The authentication method that the server uses to validate users.
	 * It does not mean that the user was authenticated if AUTH_TYPE contains a value and the authentication scheme is not Basic or integrated Windows authentication. 
	 * The server allows authentication schemes it does not natively support because an ISAPI filter may be able to handle that particular scheme.
	 *
	 * @return string
	 */
	public static function getIISAuthenticateType()
	{
		return self::getServerArguments(ServerIndices::AUTH_TYPE);
	}

	/**
	 * The password provided by the client to authenticate using Basic Authentication.
	 *
	 * @return string
	 */
	public static function getIISAuthenticatePassword()
	{
		return self::getServerArguments(ServerIndices::AUTH_PASSWORD);
	}

	/**
	 * The name of the application pool that is running the IIS worker process handling the request.
	 *
	 * @return string
	 */
	public static function getIISApplicationPoolID()
	{
		return self::getServerArguments(ServerIndices::APP_POOL_ID);
	}

	/**
	 * The physical path of the application.
	 *
	 * @return string
	 */
	public static function getIISApplicationPhysicalPath()
	{
		return self::getServerArguments(ServerIndices::APPL_PHYSICAL_PATH);
	}

	/**
	 * Get server identification string
	 */
	public static function getServerSoftwareName()
	{
		return self::getServerArguments(ServerIndices::SERVER_SOFTWARE_NAME);
	}

	/**
	 * Get document root directory under which the current script is executing
	 */
	public static function getAbsolutePathOfDocumentRoot()
	{
		return self::getServerArguments(ServerIndices::DOCUMENT_ROOT_DIRECTORY);
	}

	public static function getIISIsapiRewriteURL()
	{
		return self::getServerArguments(ServerIndices::HTTP_X_REWRITE_URL);
	}

	public static function getHTTPConnection(): string
	{
		return self::getServerArguments(ServerIndices::HTTP_CONNECTION) ?? "";
	}

	public static function getPort(): string
	{
		return self::getServerArguments(ServerIndices::SERVER_PORT);
	}

	public static function getReferer()
	{
		return self::getServerArguments(ServerIndices::HTTP_REFERER);
	}

	public static function getHTTPAccept()
	{
		return self::getServerArguments(ServerIndices::HTTP_ACCEPT);
	}

	public static function getHTTPContentType()
	{
		return self::getServerArguments(ServerIndices::HTTP_CONTENT_TYPE);
	}

	public static function getServerProtocol()
	{
		return self::getServerArguments(ServerIndices::SERVER_PROTOCOL);
	}

	/**
	 * Returns true if the current request is forwarded from a request that is secure.
	 *
	 * @return boolean
	 */
	public static function isForwardedSecure()
	{
		$forwarededProtocol = self::getHTTPXForwardedProtocol();

		return isset($forwarededProtocol) && strtolower($forwarededProtocol) == 'https';
	}

	public static function getHTTPXForwardedProtocol()
	{
		return self::getServerArguments(ServerIndices::HTTP_X_FORWARDED_PROTO);
	}

	public static function getContentType()
	{
		return self::getServerArguments(ServerIndices::CONTENT_TYPE);
	}

	public static function getSignature()
	{
		return self::getServerArguments(ServerIndices::SERVER_SIGNATURE);
	}

	public static function getUserAgent()
	{
		return self::getServerArguments(ServerIndices::HTTP_USER_AGENT) ?? "";
	}

	public static function getMethod()
	{
		return self::getServerArguments(ServerIndices::REQUEST_METHOD);
	}

	public static function isGzipAcceptEncoding()
	{
		return strpos(self::getAcceptEncoding(), 'gzip');
	}

	public static function getAcceptEncoding()
	{
		return self::getServerArguments(ServerIndices::HTTP_ACCEPT_ENCODING);
	}

	public static function getDocumentUrl()
	{
		return self::getServerArguments(ServerIndices::DOCUMENT_URI);
	}

	public static function isXmlHttpRequest()
	{
		$httpXRequestedWith = self::getServerArguments(ServerIndices::HTTP_X_REQUESTED_WITH);

		return (strtolower($httpXRequestedWith) == 'xmlhttprequest');
	}

	public static function isAjax()
	{
		return (!empty(self::getServerArguments(ServerIndices::HTTP_X_REQUESTED_WITH)) && self::isXmlHttpRequest()) ? true : false;
	}

	public static function getHTTPXForwardedFor()
	{
		if (isset($_SERVER[ServerIndices::HTTP_X_FORWARDED_FOR])) {
			return $_SERVER[ServerIndices::HTTP_X_FORWARDED_FOR];
		}

		return null;
	}

	public static function getCloudFlareProxyIP()
	{
		if (isset($_SERVER[ServerIndices::HTTP_CF_CONNECTING_IP])) {
			return self::getClientIP();
		}

		return null;
	}

	public static function getClientIP()
	{
		if (isset($_SERVER[ServerIndices::HTTP_CLIENT_IP])) {
			return $_SERVER[ServerIndices::HTTP_CLIENT_IP];
		}

		return null;
	}

	public static function getFullUri()
	{
		$uri = rtrim(dirname($_SERVER["SCRIPT_NAME"]), '/');
		$uri = '/' . trim(str_replace($uri, '', $_SERVER['REQUEST_URI']), '/');
		$uri = urldecode($uri);

		return new URLObject($uri);
	}

	/**
	 * Get query string
	 */
	public static function getQueryString()
	{
		$queryString = "";

		if (isset($_SERVER[ServerIndices::QUERY_STRING])) {
			$queryString = $_SERVER[ServerIndices::QUERY_STRING];
		}

		return new URLObject($queryString);
	}

	/**
	 * Get ip address from which the user is viewing the current page
	 * 
	 * @return URLObject|null
	 */
	public static function getRemoteIPAddress()
	{
		$remoteAddress = "";

		if (isset($_SERVER[ServerIndices::REMOTE_IP_ADDRESS])) {
			$remoteAddress = $_SERVER[ServerIndices::REMOTE_IP_ADDRESS];
		}

		return new URLObject($remoteAddress);
	}

	public static function parseAcceptLanguage($field)
	{
		$fields = explode(",", $field ?? "");

		return array_reduce($fields, function ($res, $el) {
			list($l, $q) = array_merge(explode(';q=', $el), [1]);
			$res[$l] = (float) $q;
			return $res;
		}, []);
	}

	public static function getAcceptLanguage()
	{
		if (isset($_SERVER[ServerIndices::HTTP_ACCEPT_LANGUAGE])) {
			return $_SERVER[ServerIndices::HTTP_ACCEPT_LANGUAGE];
		}

		return null;
	}

	public static function isIdempotentMethod(?HTTPRequestMethod $method = null)
	{
		return in_array(strtoupper($method ?? self::getMethod()), self::$idempotentMethods);
	}

	public static function isCacheableMethod(?HTTPRequestMethod $method = null)
	{
		return in_array(strtoupper($method ?? self::getMethod()), self::$cacheableMethods);
	}

	public static function isSafeMethod(?HTTPRequestMethod $method = null)
	{
		return in_array(strtoupper($method ?? self::getMethod()), self::$safeMethods);
	}

	public static function isHeadMethod()
	{
		return (strtoupper(self::getMethod()) === HTTPRequestMethod::HEAD) ? true : false;
	}

	public static function isPatchMethod()
	{
		return (strtoupper(self::getMethod()) === HTTPRequestMethod::PATCH) ? true : false;
	}

	public static function isPutMethod()
	{
		return (strtoupper(self::getMethod()) === HTTPRequestMethod::PUT) ? true : false;
	}

	public static function isOptionsMethod()
	{
		return (strtoupper(self::getMethod()) === HTTPRequestMethod::OPTIONS) ? true : false;
	}

	public static function isDeleteMethod()
	{
		return (strtoupper(self::getMethod()) === HTTPRequestMethod::DELETE) ? true : false;
	}

	public static function isGetMethod()
	{
		return (strtoupper(self::getMethod()) === HTTPRequestMethod::GET) ? true : false;
	}

	public static function isPostMethod()
	{
		return (strtoupper(self::getMethod()) === HTTPRequestMethod::POST) ? true : false;
	}

	public static function getPostParameter($parameter)
	{
		$string = null;

		if (self::isPostMethod()) {
			$string = isset($_POST[$parameter]) ? $_POST[$parameter] : null;
		}

		return $string;
	}

	public static function getQueryParamter($parameter)
	{
		$string = null;

		if (self::isGetMethod()) {
			$string = isset($_GET[$parameter]) ? $_GET[$parameter] : null;
		}

		return $string;
	}

	public static function getExtractedPostParameters()
	{
		if (self::getMethod() === HTTPRequestMethod::POST) {
			$extracted = array();

			foreach ($_POST as $key => $val) {
				$extracted[$key] = $val;
			}

			return $extracted;
		}

		return null;
	}

	public static function getExtractedQueryParameters()
	{
		if (self::getMethod() === HTTPRequestMethod::GET) {
			$extracted = array();

			foreach ($_GET as $key => $val) {
				$extracted[$key] = $val;
			}

			return $extracted;
		}

		return null;
	}

	public static function getRequestUri()
	{
		$port = empty($_SERVER['HTTPS']) ? 'http://' : 'https://';
		$host = sprintf('%s%s%s', $port, self::getHttpHost(), dirname(self::getDocumentUrl() ?: $_SERVER["SCRIPT_NAME"]));

		return new URLObject($host);
	}

	public static function getRequestURL()
	{
		$port = empty($_SERVER['HTTPS']) ? 'http://' : 'https://';
		$host = sprintf('%s%s%s', $port, self::getHttpHost(), $_SERVER['REQUEST_URI'] ?? "");

		return new URLObject($host);
	}

	public static function isMobile()
	{
		$useragent = strtolower(self::getUserAgent());

		if (isset($_SERVER[ServerIndices::HTTP_X_WAP_PROFILE])) {
			return true;
		}

		if (isset($_SERVER[ServerIndices::HTTP_DEVICE_STOCK_UA])) {
			return true;
		}

		if (isset($_SERVER[ServerIndices::HTTP_X_UCBROWSER_DEVICE_UA])) {
			return true;
		}

		if (isset($_SERVER[ServerIndices::HTTP_X_BOLT_PHONE_UA])) {
			return true;
		}

		if (isset($_SERVER[ServerIndices::HTTP_X_SKYFIRE_PHONE])) {
			return true;
		}

		if (isset($_SERVER[ServerIndices::HTTP_X_OPERAMINI_PHONE_UA])) {
			return true;
		}

		if (preg_match('/(android|bb\d+|meego).+mobile/i', $useragent)) {
			return true;
		}

		// Gaming Consoles
		if (preg_match('/nintendo|psp|playstation|xbox/i', $useragent)) {
			return true;
		}

		// Operation System
		if (preg_match('/symbian|webos\//i', $useragent)) {
			return true;
		}

		// Korea
		if (preg_match('/samsung|lgtelecom|lg;/i', $useragent)) {
			return true;
		}

		// Japan
		if (preg_match('/sonyericsson|docomo|panasonic|sharp|nec/i', $useragent)) {
			return true;
		}

		// Chinese
		if (preg_match('/lenovo/i', $useragent)) {
			return true;
		}

		// Canada
		if (preg_match('/blackberry/i', $useragent)) {
			return true;
		}

		// American
		if (preg_match('/novarra|appletv|motorola|ip(hone|od|ad)|nexus|windows (ce|phone)/i', $useragent)) {
			return true;
		}

		// Finland
		if (preg_match('/alcatel|nokia/i', $useragent)) {
			return true;
		}

		// PDA
		if (preg_match('/palmos|palm( os)?|pda;/i', $useragent)) {
			return true;
		}

		// Wearable
		if (preg_match('/itouch/i', $useragent)) {
			return true;
		}

		// Browser
		if (preg_match('/eudoraweb|dillo|opera m(ob|in)i|netfront|iemobile|puffin|ucbrowser|fennec/i', $useragent)) {
			return true;
		}

		$mobileRegex = '/avantgo|htc(_|-)|bada\/|brew|blazer|tablet|compal|teleca|minimo|wap;|elaine|hiptop|iris|kindle|lge |maemo|midp|mmp|phone|p(ixi|re)\/|plucker|pocket|series(4|6)0|treo|up\.(browser|link)|vodafone|wap|xda|xiino/i';

		$mobileRegex2 = '/1207|6310|6590|3gso|4thp|50[1-6]i|770s|802s|a wa|abac|ac(er|oo|s\-)|ai(ko|rn)|al(av|ca|co)|amoi|an(ex|ny|yw)|aptu|ar(ch|go)|as(te|us)|attw|au(di|\-m|r |s )|avan|be(ck|ll|nq)|bi(lb|rd)|bl(ac|az)|br(e|v)w|bumb|bw\-(n|u)|c55\/|capi|ccwa|cdm\-|cell|chtm|cldc|cmd\-|co(mp|nd)|craw|da(it|ll|ng)|dbte|dc\-s|devi|dica|dmob|do(c|p)o|ds(12|\-d)|el(49|ai)|em(l2|ul)|er(ic|k0)|esl8|ez([4-7]0|os|wa|ze)|fetc|fly(\-|_)|g1 u|g560|gene|gf\-5|g\-mo|go(\.w|od)|gr(ad|un)|haie|hcit|hd\-(m|p|t)|hei\-|hi(pt|ta)|hp( i|ip)|hs\-c|ht(c(\-| |_|a|g|p|s|t)|tp)|hu(aw|tc)|i\-(20|go|ma)|i230|iac( |\-|\/)|ibro|idea|ig01|ikom|im1k|inno|ipaq|iris|ja(t|v)a|jbro|jemu|jigs|kddi|keji|kgt( |\/)|klon|kpt |kwc\-|kyo(c|k)|le(no|xi)|lg( g|\/(k|l|u)|50|54|\-[a-w])|libw|lynx|m1\-w|m3ga|m50\/|ma(te|ui|xo)|mc(01|21|ca)|m\-cr|me(rc|ri)|mi(o8|oa|ts)|mmef|mo(01|02|bi|de|do|t(\-| |o|v)|zz)|mt(50|p1|v )|mwbp|mywa|n10[0-2]|n20[2-3]|n30(0|2)|n50(0|2|5)|n7(0(0|1)|10)|ne((c|m)\-|on|tf|wf|wg|wt)|nok(6|i)|nzph|o2im|op(ti|wv)|oran|owg1|p800|pan(a|d|t)|pdxg|pg(13|\-([1-8]|c))|phil|pire|pl(ay|uc)|pn\-2|po(ck|rt|se)|prox|psio|pt\-g|qa\-a|qc(07|12|21|32|60|\-[2-7]|i\-)|qtek|r380|r600|raks|rim9|ro(ve|zo)|s55\/|sa(ge|ma|mm|ms|ny|va)|sc(01|h\-|oo|p\-)|sdk\/|se(c(\-|0|1)|47|mc|nd|ri)|sgh\-|shar|sie(\-|m)|sk\-0|sl(45|id)|sm(al|ar|b3|it|t5)|so(ft|ny)|sp(01|h\-|v\-|v )|sy(01|mb)|t2(18|50)|t6(00|10|18)|ta(gt|lk)|tcl\-|tdg\-|tel(i|m)|tim\-|t\-mo|to(pl|sh)|ts(70|m\-|m3|m5)|tx\-9|up(\.b|g1|si)|utst|v400|v750|veri|vi(rg|te)|vk(40|5[0-3]|\-v)|vm40|voda|vulc|vx(52|53|60|61|70|80|81|83|85|98)|w3c(\-| )|webc|whit|wi(g |nc|nw)|wmlb|wonu|x700|yas\-|your|zeto|zte\-/i';

		if (preg_match($mobileRegex, $useragent) || preg_match($mobileRegex2, substr($useragent, 0, 4))) {
			return true;
		}

		return false;
	}

	public static function isMatchHost($hosts)
	{
		$host = $_SERVER[ServerIndices::REMOTE_HOST_NAME];

		$regexp = preg_quote($hosts, '#');
		$regexp = str_replace(',', '|', $regexp);
		$regexp = str_replace('\*', '.+', $regexp);

		return preg_match("#$regexp#", $host);
	}

	public static function isMatchUserAgent($agents)
	{
		$agent = $_SERVER[ServerIndices::HTTP_USER_AGENT];
		$agent = preg_replace('/[\r\n]/', '', $agent);

		$regexp = preg_quote($agents, '#');
		$regexp = str_replace(',', '|', $regexp);
		$regexp = str_replace('\*', '.+', $regexp);

		return preg_match("#$regexp#", $agent);
	}


	public static function getCrawlerUserAgent()
	{
		$useragent = strtolower(self::getUserAgent());

		$crawlerRegex = "/bot|archiver|apachebench|wget|curl|crawl|google|yahoo|slurp|wordpress|spider|yeti|daum|teoma|fish|hanrss|facebook|yandex|infoseek|askjeeves|stackrambler|spyder|watchmouse|pingdom\.com|feedfetcher-google/";

		if (preg_match($crawlerRegex, $useragent)) {
			return $useragent;
		}

		return false;
	}

	public static function isCrawler()
	{
		if (self::getCrawlerUserAgent()) {
			return true;
		}

		return false;
	}

	public static function isConnectionKeepAlive()
	{
		$connection = self::getHTTPConnection();

		return strtolower($connection) === 'keep-alive';
	}

	public static function hasReferer()
	{
		if (!isset($_SERVER[ServerIndices::HTTP_REFERER]) || !isset($_SERVER['SCRIPT_URL'])) {
			return false;
		}

		$referer = self::getReferer();
		$url     = $_SERVER['SCRIPT_URL'];

		return strpos($referer, $url) === 0;
	}
}
