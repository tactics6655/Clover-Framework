<?php

declare(strict_types=1);

namespace Neko\Classes;

use Neko\Implement\ClientURLOptionInterface;

use Neko\Classes\Format\MultiPurposeInternetMailExtensions as MIME;

use Neko\Enumeration\HTTPRequestMethod;

class ClientURLOption implements ClientURLOptionInterface
{

	private static $session;

	public static $options = [];

	public function __construct($session)
	{
		self::$session = $session;
	}

	public function returnContext()
	{
		return $this;
	}

	private function setOption($key, $value)
	{
		curl_setopt(self::$session, $key, $value);
	}

	public function disableCache(bool $bool)
	{
		$this->setOption(\CURLOPT_FRESH_CONNECT, $bool);

		return $this->returnContext();
	}

	/**
	 * Provide the URL to use in the request
	 *
	 * @return \Neko\Classes\ClientURLOption
	 */
	public function setURL(string $url)
	{
		$this->setOption(\CURLOPT_URL, $url);

		return $this->returnContext();
	}

	public function setForbidenReuse(bool $bool = true)
	{
		$this->setOption(\CURLOPT_FORBID_REUSE, $bool);

		return $this->returnContext();
	}

	/**
	 * Ready to Upload
	 *
	 * @return \Neko\Classes\ClientURLOption
	 */
	public function setUploadReady(bool $bool = true)
	{
		$this->setOption(\CURLOPT_UPLOAD, $bool);

		return $this->returnContext();
	}

	/**
	 * Verify the peer's SSL certificate
	 *
	 * @return \Neko\Classes\ClientURLOption
	 */
	public function setSSLVerifypeer(bool $bool = true)
	{
		$this->setOption(\CURLOPT_SSL_VERIFYPEER, $bool);

		return $this->returnContext();
	}

	/**
	 * Set maximum time the request is allowed to take
	 *
	 * @return \Neko\Classes\ClientURLOption
	 */
	public function setTimeout(bool $timeout = true)
	{
		$this->setOption(\CURLOPT_TIMEOUT, $timeout);

		return $this->returnContext();
	}

	public function setCRLF(bool $timeout = true)
	{
		$this->setOption(\CURLOPT_CRLF, $timeout);

		return $this->returnContext();
	}

	/**
	 * Specify data to POST to server
	 *
	 * @return \Neko\Classes\ClientURLOption
	 */
	public function setPostField($fields)
	{
		$this->setOption(\CURLOPT_POSTFIELDS, $fields);

		return $this->returnContext();
	}

	/**
	 * Size of POST data pointed to
	 *
	 * @return \Neko\Classes\ClientURLOption
	 */
	public function setPostFieldSize(int $size = 0)
	{
		$this->setOption(\CURLOPT_POSTFIELDSIZE, $size);

		return $this->returnContext();
	}

	/**
	 * Redirects that a HTTP server sends in a 30x response
	 *
	 * @return \Neko\Classes\ClientURLOption
	 */
	public function setFollowRedirects(bool $bool = true)
	{
		$this->setOption(\CURLOPT_FOLLOWLOCATION, $bool);

		return $this->returnContext();
	}

	/**
	 * Follow HTTP 3xx redirects
	 *
	 * @return \Neko\Classes\ClientURLOption
	 */
	public function setFollowLocationHeader(int $size = 0)
	{
		$this->setOption(\CURLOPT_FOLLOWLOCATION, $size);

		return $this->returnContext();
	}

	/**
	 * Enable/Disable use of EPSV
	 *
	 * @return \Neko\Classes\ClientURLOption
	 */
	public function setFTPUseEPSV(int $size = 0)
	{
		$this->setOption(\CURLOPT_FTP_USE_EPSV, $size);

		return $this->returnContext();
	}

	public function setInterface($interface)
	{
		$this->setOption(\CURLOPT_INTERFACE, $interface);

		return $this->returnContext();
	}

	public function setRange($range)
	{
		$this->setOption(\CURLOPT_RANGE, $range);

		return $this->returnContext();
	}

	public function setProxyAuthentication($authentication)
	{
		$this->setOption(\CURLOPT_PROXYAUTH, $authentication);

		return $this->returnContext();
	}

	public function setProxy($proxy)
	{
		$this->setOption(\CURLOPT_PROXY, $proxy);

		return $this->returnContext();
	}

	public function setProxyUserPassword($password)
	{
		$this->setOption(\CURLOPT_PROXYUSERPWD, $password);

		return $this->returnContext();
	}

	public function setProxyPort($port)
	{
		$this->setOption(\CURLOPT_PROXYPORT, $port);

		return $this->returnContext();
	}

	public function setCookieFile($file)
	{
		$this->setOption(\CURLOPT_COOKIEFILE, $file);

		return $this->returnContext();
	}

	public function setBufferSize($size)
	{
		$this->setOption(\CURLOPT_BUFFERSIZE, $size);

		return $this->returnContext();
	}

	public function enableTCPFastOpen($enable)
	{
		$this->setOption(\CURLOPT_TCP_FASTOPEN, $enable);

		return $this->returnContext();
	}

	public function setNoProgress($number)
	{
		$this->setOption(\CURLOPT_NOPROGRESS, $number);

		return $this->returnContext();
	}

	public function setProgressCallback($name)
	{
		$this->setOption(\CURLOPT_PROGRESSFUNCTION, $name);

		return $this->returnContext();
	}

	public function setMaxRedirects(int $number)
	{
		$this->setOption(\CURLOPT_MAXREDIRS, $number);

		return $this->returnContext();
	}

	public function setCookieJar(string $jar)
	{
		$this->setOption(\CURLOPT_COOKIEJAR, $jar);

		return $this->returnContext();
	}

	public function setProxyType($type)
	{
		$this->setOption(\CURLOPT_PROXYTYPE, $type);

		return $this->returnContext();
	}

	public function enableTCPKeepAlive(bool $enable)
	{
		$this->setOption(\CURLOPT_TCP_KEEPALIVE, $enable);

		return $this->returnContext();
	}

	public function setHeaderOut(bool $enable)
	{
		$this->setOption(\CURLINFO_HEADER_OUT, $enable);

		return $this->returnContext();
	}

	public function setFileHandler($filePointer)
	{
		$this->setOption(\CURLOPT_FILE, $filePointer);

		return $this->returnContext();
	}

	public function setProxyTunnel(bool $bool = true)
	{
		$this->setOption(\CURLOPT_HTTPPROXYTUNNEL, $bool);

		return $this->returnContext();
	}

	/**
	 * Verbose
	 *
	 * @return \Neko\Classes\ClientURLOption
	 */
	public function setVerbose(bool $bool = true)
	{
		$this->setOption(\CURLOPT_VERBOSE, $bool);

		return $this->returnContext();
	}

	/**
	 * Enable/Disable Global DNS cache
	 *
	 * @return \Neko\Classes\ClientURLOption
	 */
	public function setDnsUseGlobalCache(bool $bool = true)
	{
		$this->setOption(\CURLOPT_DNS_USE_GLOBAL_CACHE, $bool);

		return $this->returnContext();
	}

	/**
	 * Set HTTP user-agent header
	 *
	 * @return \Neko\Classes\ClientURLOption
	 */
	public function setUserAgent($userAgent = '')
	{
		$this->setOption(\CURLOPT_USERAGENT, $userAgent);

		return $this->returnContext();
	}

	public function setAcceptEncoding($encoding = '')
	{
		$this->setOption(\CURLOPT_ENCODING, $encoding);

		return $this->returnContext();
	}

	/**
	 * Set contents of HTTP Cookie header
	 *
	 * @return \Neko\Classes\ClientURLOption
	 */
	public function setCookieHeader($cookieData = '')
	{
		$this->setOption(\CURLOPT_COOKIE, $cookieData);

		return $this->returnContext();
	}

	/**
	 * Start a new cookie session
	 *
	 * @return \Neko\Classes\ClientURLOption
	 */
	public function useCookieSession(bool $bool = true)
	{
		$this->setOption(\CURLOPT_COOKIESESSION, $bool);

		return $this->returnContext();
	}

	/**
	 * Maximum connection cache size
	 *
	 * @return \Neko\Classes\ClientURLOption
	 */
	public function setMaximumConnectionCount(bool $maximumConnection = true)
	{
		$this->setOption(\CURLOPT_MAXCONNECTS, $maximumConnection);

		return $this->returnContext();
	}

	/**
	 * Automatically update the referer header
	 *
	 * @return \Neko\Classes\ClientURLOption
	 */
	public function setAutoReferer(bool $bool = true)
	{
		$this->setOption(\CURLOPT_AUTOREFERER, $bool);

		return $this->returnContext();
	}

	/**
	 * Do the download request without getting the body
	 *
	 * @return \Neko\Classes\ClientURLOption
	 */
	public function setBodyEmpty(bool $bool = true)
	{
		$this->setOption(\CURLOPT_NOBODY, $bool);

		return $this->returnContext();
	}

	public function setConnectionTimeout(bool $timeout = true, bool $useMilliseconds = false)
	{
		if ($useMilliseconds) {
			return $this->setConnectionTimeoutMilliseconds($timeout);
		} else {
			$this->setOption(\CURLOPT_CONNECTTIMEOUT, $timeout);

			return $this->returnContext();
		}
	}

	/**
	 * Timeout for the connect phase
	 *
	 * @return \Neko\Classes\ClientURLOption
	 */
	public function setConnectionTimeoutMilliseconds(bool $timeout = true)
	{
		$this->setOption(\CURLOPT_CONNECTTIMEOUT_MS, $timeout);

		return $this->returnContext();
	}

	public function setNobody(bool $bool = true)
	{
		$this->setBodyEmpty($bool);

		return $this->returnContext();
	}

	public function setTransferText(bool $bool = true)
	{
		$this->setOption(\CURLOPT_TRANSFERTEXT, $bool);

		return $this->returnContext();
	}

	public function setBinaryTransfer(bool $bool = true)
	{
		$this->setOption(\CURLOPT_BINARYTRANSFER, $bool);

		return $this->returnContext();
	}

	public function setMaximumUploadSpeed(int $bytePerSeconds = 1000)
	{
		$this->setMaximumSendSpeed($bytePerSeconds);

		return $this->returnContext();
	}

	/**
	 * Rate limit data upload speed
	 *
	 * @return \Neko\Classes\ClientURLOption
	 */
	public function setMaximumSendSpeed(int $bytePerSeconds = 1000)
	{
		$this->setOption(\CURLOPT_MAX_SEND_SPEED_LARGE, $bytePerSeconds);

		return $this->returnContext();
	}

	public function setMaximumDownloadSpeed(int $bytePerSeconds = 1000)
	{
		$this->setMaximumReceiveSpeed($bytePerSeconds);

		return $this->returnContext();
	}

	/**
	 * Rate limit data download speed
	 *
	 * @return \Neko\Classes\ClientURLOption
	 */
	public function setMaximumReceiveSpeed(int $bytePerSeconds = 1000)
	{
		$this->setOption(\CURLOPT_MAX_RECV_SPEED_LARGE, $bytePerSeconds);

		return $this->returnContext();
	}

	public function setHeader(string $key, string $value, bool $overwrite = false)
	{
		$headerData = [$key, $value];

		if (!$overwrite) {
			array_push(self::$options, $headerData);

			$headers = array_map(function ($header) {
				return implode(': ', $header);
			}, self::$options);

			$this->setOption(\CURLOPT_HTTPHEADER, $headers);
		} else {
			$this->setOption(\CURLOPT_HTTPHEADER, $headerData);
		}

		return $this->returnContext();
	}

	public function setContentType(string $applicationType)
	{
		$mimeType = MIME::getContentTypeFromExtension($applicationType);

		return $this->setHeader('Content-Type', $mimeType);
	}

	public function setCharset($charset)
	{
		return $this->setHeader('Charset', $charset);
	}

	public function setAccept($contentType)
	{
		return $this->setHeader('Accept', $contentType);
	}

	public function setAcceptXml()
	{
		return $this->setAccept('xml');
	}

	public function setAcceptJson()
	{
		return $this->setAccept('json');
	}

	public function setContentTypeJson()
	{
		return $this->setContentType('json');
	}

	/**
	 * Set custom HTTP headers
	 *
	 * @return \Neko\Classes\ClientURLOption
	 */
	public function setHeaders($headers = [])
	{
		$this->setOption(\CURLOPT_HTTPHEADER, $headers);

		return $this->returnContext();
	}

	/**
	 * Set remote port number to work with
	 *
	 * @return \Neko\Classes\ClientURLOption
	 */
	public function setPort(bool $port = true)
	{
		$this->setOption(\CURLOPT_PORT, $port);

		return $this->returnContext();
	}

	/**
	 * Request an HTTP POST Method
	 *
	 * @return \Neko\Classes\ClientURLOption
	 */
	public function setPostMethod(bool $bool = true)
	{
		$this->setOption(\CURLOPT_POST, $bool);

		return $this->returnContext();
	}

	/**
	 * Request an HTTP GET Method
	 *
	 * @return \Neko\Classes\ClientURLOption
	 */
	public function setGetMethod(bool $bool = true)
	{
		$this->setOption(\CURLOPT_HTTPGET, $bool);

		return $this->returnContext();
	}

	public function setHeaderCallback($method)
	{
		curl_setopt(self::$session, \CURLOPT_HEADERFUNCTION, $method);
	}

	private function receiveResponseHeader()
	{
		$this->setHeaderCallback(function ($ch, $header) use (&$headers) {
			$matches = array();

			if (preg_match('/^([^:]+)\s*:\s*([^\x0D\x0A]*)\x0D?\x0A?$/', $header, $matches)) {
				$headers[$matches[1]][] = $matches[2];
			}

			return strlen($header);
		});
	}

	private function setAnySafeAuthentication()
	{
		return $this->setAuthentication(\CURLAUTH_ANYSAFE);
	}

	private function setAnyAuthentication()
	{
		return $this->setAuthentication(\CURLAUTH_ANY);
	}

	private function setNTLMAuthentication()
	{
		return $this->setAuthentication(\CURLAUTH_NTLM);
	}

	private function setGSSNegotiateAuthentication()
	{
		return $this->setAuthentication(\CURLAUTH_GSSNEGOTIATE);
	}

	private function setDigestAuthentication()
	{
		return $this->setAuthentication(\CURLAUTH_DIGEST);
	}

	private function setNoneHTTPVersion()
	{
		return $this->setHTTPVersion(\CURL_HTTP_VERSION_NONE);
	}

	private function setHTTPVersion_1_0()
	{
		return $this->setHTTPVersion(\CURL_HTTP_VERSION_1_0);
	}

	private function setHTTPVersion_1_1()
	{
		return $this->setHTTPVersion(\CURL_HTTP_VERSION_1_1);
	}

	private function setHTTPVersion_2_0()
	{
		return $this->setHTTPVersion(\CURL_HTTP_VERSION_2_0);
	}

	private function setHTTPVersion_2_TLS()
	{
		return $this->setHTTPVersion(\CURL_HTTP_VERSION_2TLS);
	}

	private function setLowSpeedLimitTime($value)
	{
		return $this->setOption(\CURLOPT_LOW_SPEED_TIME, $value);
	}

	private function setHTTPPriorKnowledge()
	{
		return $this->setHTTPVersion(\CURL_HTTP_VERSION_2_PRIOR_KNOWLEDGE);
	}

	private function setHTTPVersion($version)
	{
		$this->setOption(\CURLOPT_HTTP_VERSION, $version);

		return $this->returnContext();
	}

	private function setAuthentication($authentication)
	{
		$this->setOption(\CURLOPT_HTTPAUTH, $authentication);

		return $this->returnContext();
	}

	private function setCustomMethod($method)
	{
		$this->setOption(\CURLOPT_CUSTOMREQUEST, $method);

		return $this->returnContext();
	}

	/**
	 * Request an HTTP Options Method
	 *
	 * @return \Neko\Classes\ClientURLOption
	 */
	private function setOptionsMethod()
	{
		return $this->setCustomMethod(HTTPRequestMethod::OPTIONS);
	}

	/**
	 * Request an HTTP Patch Method
	 *
	 * @return \Neko\Classes\ClientURLOption
	 */
	private function setPatchMethod()
	{
		return $this->setCustomMethod(HTTPRequestMethod::PATCH);
	}

	/**
	 * Request an HTTP Head Method
	 *
	 * @return \Neko\Classes\ClientURLOption
	 */
	private function setHeadMethod()
	{
		return $this->setCustomMethod(HTTPRequestMethod::HEAD);
	}

	/**
	 * Request an HTTP Put Method
	 *
	 * @return \Neko\Classes\ClientURLOption
	 */
	private function setPutMethod()
	{
		return $this->setCustomMethod(HTTPRequestMethod::PUT);
	}

	/**
	 * Request an HTTP Delete Method
	 *
	 * @return \Neko\Classes\ClientURLOption
	 */
	private function setDeleteMethod()
	{
		return $this->setCustomMethod(HTTPRequestMethod::DELETE);
	}

	public function setReturnTransfer(bool $hasResponse = true)
	{
		$this->setOption(\CURLOPT_RETURNTRANSFER, $hasResponse);

		return $this->returnContext();
	}

	/**
	 * Pass headers to the data stream
	 *
	 * @return \Neko\Classes\ClientURLOption
	 */
	public function setReturnHeader(bool $hasResponse = true)
	{
		$this->setOption(\CURLOPT_HEADER, $hasResponse);

		return $this->returnContext();
	}
}
