<?php

declare(strict_types=1);

namespace Clover\Classes;

use Clover\Implement\ClientURLOptionInterface;
use Clover\Classes\Format\MultiPurposeInternetMailExtensions as MIME;
use Clover\Enumeration\HTTPRequestMethod;

use const CURLAUTH_DIGEST;
use const CURL_HTTP_VERSION_NONE;
use const CURL_HTTP_VERSION_1_0;
use const CURL_HTTP_VERSION_1_1;
use const CURLOPT_CUSTOMREQUEST;
use const CURLOPT_HTTPAUTH;
use const CURLOPT_TIMEOUT;
use const CURLOPT_FRESH_CONNECT;
use const CURLOPT_POSTFIELDSIZE;
use const CURLOPT_RETURNTRANSFER;
use const CURLOPT_MAX_SEND_SPEED_LARGE;
use const CURLOPT_SSL_VERIFYPEER;
use const CURLOPT_HEADER;
use const CURLOPT_COOKIEJAR;
use const CURLOPT_FORBID_REUSE;
use const CURLOPT_UPLOAD;
use const CURL_HTTP_VERSION_2_0;
use const CURLOPT_URL;

class ClientURLOption implements ClientURLOptionInterface
{

	public static $headers = [];

	private static $session;

	public static $options = [];

	public static $curl_options = [];

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
		$constants = get_defined_constants(true);
		$constants = array_filter($constants['curl'] ?? [], function ($id) use ($key) {
			return $id == $key;
		});

		self::$curl_options[array_keys($constants)[0] ?? $key] = $value;

		curl_setopt(self::$session, $key, $value);
	}

	/**
	 * {@see \CURLOPT_FRESH_CONNECT}
	 *
	 * @return \Clover\Classes\ClientURLOption
	 */
	public function disableCache(bool $bool)
	{
		$this->setOption(CURLOPT_FRESH_CONNECT, $bool);

		return $this->returnContext();
	}

	/**
	 * {@see \CURLOPT_URL}
	 *
	 * @return \Clover\Classes\ClientURLOption
	 */
	public function setURL(string $url)
	{
		$this->setOption(CURLOPT_URL, $url);

		return $this->returnContext();
	}

	/**
	 * {@see \CURLOPT_FORBID_REUSE}
	 *
	 * @return \Clover\Classes\ClientURLOption
	 */
	public function setForbidenReuse(bool $bool = true)
	{
		$this->setOption(CURLOPT_FORBID_REUSE, $bool);

		return $this->returnContext();
	}

	/**
	 * {@see \CURLOPT_UPLOAD}
	 *
	 * @return \Clover\Classes\ClientURLOption
	 */
	public function setUploadReady(bool $bool = true)
	{
		$this->setOption(CURLOPT_UPLOAD, $bool);

		return $this->returnContext();
	}

	/**
	 * {@see \CURLOPT_SSL_VERIFYPEER}
	 *
	 * @return \Clover\Classes\ClientURLOption
	 */
	public function setSSLVerifypeer(bool $bool = true)
	{
		$this->setOption(CURLOPT_SSL_VERIFYPEER, $bool);

		return $this->returnContext();
	}
	
	/**
	 * {@see \CURLOPT_SSLKEY}
	 *
	 * @return \Clover\Classes\ClientURLOption
	 */
	public function setSSLKey($key)
	{
		$this->setOption(CURLOPT_SSLKEY, $key);

		return $this->returnContext();
	}
	
	/**
	 * {@see \CURLOPT_SSLCERT}
	 *
	 * @return \Clover\Classes\ClientURLOption
	 */
	public function setSSLcertificate($certificate)
	{
		$this->setOption(CURLOPT_SSLCERT, $certificate);

		return $this->returnContext();
	}
	
	/**
	 * {@see \CURLOPT_SSL_CIPHER_LIST}
	 *
	 * @return \Clover\Classes\ClientURLOption
	 */
	public function setSSLCipherList($cipherList)
	{
		$this->setOption(CURLOPT_SSL_CIPHER_LIST, $cipherList);

		return $this->returnContext();
	}
	
	/**
	 * {@see \CURLOPT_CAPATH}
	 *
	 * @return \Clover\Classes\ClientURLOption
	 */
	public function setCAPath($path)
	{
		$this->setOption(CURLOPT_CAPATH, $path);

		return $this->returnContext();
	}
	
	/**
	 * {@see \CURLOPT_CAINFO}
	 *
	 * @return \Clover\Classes\ClientURLOption
	 */
	public function setCAInformation($verifyHost)
	{
		$this->setOption(CURLOPT_CAINFO, $verifyHost);

		return $this->returnContext();
	}
	
	/**
	 * {@see \CURLOPT_SSL_VERIFYHOST}
	 *
	 * @return \Clover\Classes\ClientURLOption
	 */
	public function setSSLVerifyHost($verifyHost)
	{
		$this->setOption(CURLOPT_SSL_VERIFYHOST, $verifyHost);

		return $this->returnContext();
	}

	/**
	 * {@see \CURLOPT_TIMEOUT}
	 *
	 * @return \Clover\Classes\ClientURLOption
	 */
	public function setTimeout(bool $timeout = true)
	{
		$this->setOption(CURLOPT_TIMEOUT, $timeout);

		return $this->returnContext();
	}

	/**
	 * {@see \CURLOPT_CRLF}
	 *
	 * @return \Clover\Classes\ClientURLOption
	 */
	public function setCRLF(bool $timeout = true)
	{
		$this->setOption(CURLOPT_CRLF, $timeout);

		return $this->returnContext();
	}

	/**
	 * {@see \CURLOPT_POSTFIELDS}
	 *
	 * @return \Clover\Classes\ClientURLOption
	 */
	public function setPostField($fields)
	{
		$this->setOption(CURLOPT_POSTFIELDS, $fields);

		return $this->returnContext();
	}

	/**
	 * {@see \CURLOPT_SSLVERSION}
	 *
	 * @return \Clover\Classes\ClientURLOption
	 */
	public function setSSLVersion(int $version = CURL_SSLVERSION_TLSv1_0)
	{
		$this->setOption(CURLOPT_SSLVERSION, $version);

		return $this->returnContext();
	}
	
	/**
	 * {@see \CURLOPT_POSTFIELDSIZE}
	 *
	 * @return \Clover\Classes\ClientURLOption
	 */
	public function setPostFieldSize(int $size = 0)
	{
		$this->setOption(\CURLOPT_POSTFIELDSIZE, $size);

		return $this->returnContext();
	}

	/**
	 * {@see \CURLOPT_FOLLOWLOCATION}
	 *
	 * @return \Clover\Classes\ClientURLOption
	 */
	public function setFollowRedirects(bool $bool = true)
	{
		$this->setOption(CURLOPT_FOLLOWLOCATION, $bool);

		return $this->returnContext();
	}

	/**
	 * {@see \CURLOPT_FOLLOWLOCATION}
	 *
	 * @return \Clover\Classes\ClientURLOption
	 */
	public function setFollowLocationHeader(int $size = 0)
	{
		$this->setOption(CURLOPT_FOLLOWLOCATION, $size);

		return $this->returnContext();
	}

	/**
	 * {@see \CURLOPT_FTP_USE_EPSV}
	 *
	 * @return \Clover\Classes\ClientURLOption
	 */
	public function setFTPUseEPSV(int $size = 0)
	{
		$this->setOption(CURLOPT_FTP_USE_EPSV, $size);

		return $this->returnContext();
	}

	/**
	 * {@see \CURLOPT_LOCALPORT}
	 *
	 * @return \Clover\Classes\ClientURLOption
	 */
	public function setLocalPort(int $port)
	{
		$this->setOption(CURLOPT_LOCALPORT, $port);

		return $this->returnContext();
	}

	/**
	 * {@see \CURLOPT_INTERFACE}
	 *
	 * @return \Clover\Classes\ClientURLOption
	 */
	public function setInterface($interface)
	{
		$this->setOption(CURLOPT_INTERFACE, $interface);

		return $this->returnContext();
	}

	/**
	 * {@see \CURLOPT_RANGE}
	 *
	 * @return \Clover\Classes\ClientURLOption
	 */
	public function setRange($range)
	{
		$this->setOption(CURLOPT_RANGE, $range);

		return $this->returnContext();
	}

	/**
	 * {@see \CURLOPT_NOPROXY}
	 *
	 * @return \Clover\Classes\ClientURLOption
	 */
	public function setNoProxy($noproxy)
	{
		$this->setOption(CURLOPT_NOPROXY, $noproxy);

		return $this->returnContext();
	}

	/**
	 * {@see \CURLOPT_PROXYAUTH}
	 *
	 * @return \Clover\Classes\ClientURLOption
	 */
	public function setProxyAuthentication($authentication)
	{
		$this->setOption(CURLOPT_PROXYAUTH, $authentication);

		return $this->returnContext();
	}

	/**
	 * {@see \CURLOPT_PROXY}
	 *
	 * @return \Clover\Classes\ClientURLOption
	 */
	public function setProxy($proxy)
	{
		$this->setOption(CURLOPT_PROXY, $proxy);

		return $this->returnContext();
	}

	/**
	 * {@see \CURLOPT_PROXYUSERPWD}
	 *
	 * @return \Clover\Classes\ClientURLOption
	 */
	public function setProxyUserPassword($password)
	{
		$this->setOption(CURLOPT_PROXYUSERPWD, $password);

		return $this->returnContext();
	}

	/**
	 * {@see \CURLOPT_PROXYPORT}
	 *
	 * @return \Clover\Classes\ClientURLOption
	 */
	public function setProxyPort($port)
	{
		$this->setOption(CURLOPT_PROXYPORT, $port);

		return $this->returnContext();
	}

	/**
	 * {@see \CURLOPT_COOKIEFILE}
	 *
	 * @return \Clover\Classes\ClientURLOption
	 */
	public function setCookieFile($file)
	{
		$this->setOption(CURLOPT_COOKIEFILE, $file);

		return $this->returnContext();
	}

	/**
	 * {@see \CURLOPT_BUFFERSIZE}
	 *
	 * @return \Clover\Classes\ClientURLOption
	 */
	public function setBufferSize($size)
	{
		$this->setOption(CURLOPT_BUFFERSIZE, $size);

		return $this->returnContext();
	}

	/**
	 * {@see \CURLOPT_TCP_FASTOPEN}
	 *
	 * @return \Clover\Classes\ClientURLOption
	 */
	public function enableTCPFastOpen($enable)
	{
		$this->setOption(CURLOPT_TCP_FASTOPEN, $enable);

		return $this->returnContext();
	}

	/**
	 * {@see \CURLOPT_NOPROGRESS}
	 *
	 * @return \Clover\Classes\ClientURLOption
	 */
	public function setNoProgress($number)
	{
		$this->setOption(CURLOPT_NOPROGRESS, $number);

		return $this->returnContext();
	}

	/**
	 * {@see \CURLOPT_PROGRESSFUNCTION}
	 *
	 * @return \Clover\Classes\ClientURLOption
	 */
	public function setProgressCallback($name)
	{
		$this->setOption(CURLOPT_PROGRESSFUNCTION, $name);

		return $this->returnContext();
	}

	/**
	 * {@see \CURLOPT_MAXREDIRS}
	 *
	 * @return \Clover\Classes\ClientURLOption
	 */
	public function setMaxRedirects(int $number)
	{
		$this->setOption(CURLOPT_MAXREDIRS, $number);

		return $this->returnContext();
	}

	/**
	 * {@see \CURLOPT_COOKIEJAR}
	 *
	 * @return \Clover\Classes\ClientURLOption
	 */
	public function setCookieJar(string $jar)
	{
		$this->setOption(CURLOPT_COOKIEJAR, $jar);

		return $this->returnContext();
	}

	/**
	 * {@see \CURLOPT_PROXYTYPE}
	 *
	 * @return \Clover\Classes\ClientURLOption
	 */
	public function setProxyType($type)
	{
		$this->setOption(CURLOPT_PROXYTYPE, $type);

		return $this->returnContext();
	}

	/**
	 * {@see \CURLOPT_TCP_KEEPALIVE}
	 *
	 * @return \Clover\Classes\ClientURLOption
	 */
	public function enableTCPKeepAlive(bool $enable)
	{
		$this->setOption(CURLOPT_TCP_KEEPALIVE, $enable);

		return $this->returnContext();
	}

	/**
	 * {@see \CURLINFO_HEADER_OUT}
	 *
	 * @return \Clover\Classes\ClientURLOption
	 */
	public function setHeaderOut(bool $enable)
	{
		$this->setOption(CURLINFO_HEADER_OUT, $enable);

		return $this->returnContext();
	}

	/**
	 * {@see \CURLOPT_FILE}
	 *
	 * @return \Clover\Classes\ClientURLOption
	 */
	public function setFileHandler($filePointer)
	{
		$this->setOption(CURLOPT_FILE, $filePointer);

		return $this->returnContext();
	}

	/**
	 * {@see \CURLOPT_HTTPPROXYTUNNEL}
	 *
	 * @return \Clover\Classes\ClientURLOption
	 */
	public function setProxyTunnel(bool $bool = true)
	{
		$this->setOption(CURLOPT_HTTPPROXYTUNNEL, $bool);

		return $this->returnContext();
	}

	/**
	 * {@see \CURLOPT_VERBOSE}
	 *
	 * @return \Clover\Classes\ClientURLOption
	 */
	public function setVerbose(bool $bool = true)
	{
		$this->setOption(CURLOPT_VERBOSE, $bool);

		return $this->returnContext();
	}

	/**
	 * {@see \CURLOPT_HEADEROPT}
	 *
	 * @return \Clover\Classes\ClientURLOption
	 */
	public function setHeaderOption(int $option = CURLHEADER_SEPARATE)
	{
		$this->setOption(CURLOPT_HEADEROPT, $option);

		return $this->returnContext();
	}

	/**
	 * {@see \CURLOPT_DNS_USE_GLOBAL_CACHE}
	 *
	 * @return \Clover\Classes\ClientURLOption
	 */
	public function setDnsUseGlobalCache(bool $bool = true)
	{
		$this->setOption(CURLOPT_DNS_USE_GLOBAL_CACHE, $bool);

		return $this->returnContext();
	}

	/**
	 * {@see \CURLOPT_USERAGENT}
	 *
	 * @return \Clover\Classes\ClientURLOption
	 */
	public function setUserAgent($userAgent = '')
	{
		$this->setOption(CURLOPT_USERAGENT, $userAgent);

		return $this->returnContext();
	}

	/**
	 * {@see \CURLOPT_ENCODING}
	 *
	 * @return \Clover\Classes\ClientURLOption
	 */
	public function setAcceptEncoding($encoding = '')
	{
		$this->setOption(CURLOPT_ENCODING, $encoding);

		return $this->returnContext();
	}

	/**
	 * {@see \CURLOPT_COOKIE}
	 *
	 * @return \Clover\Classes\ClientURLOption
	 */
	public function setCookieHeader($cookieData = '')
	{
		$this->setOption(CURLOPT_COOKIE, $cookieData);

		return $this->returnContext();
	}

	/**
	 * {@see \CURLOPT_COOKIESESSION}
	 *
	 * @return \Clover\Classes\ClientURLOption
	 */
	public function useCookieSession(bool $bool = true)
	{
		$this->setOption(CURLOPT_COOKIESESSION, $bool);

		return $this->returnContext();
	}

	/**
	 * {@see \CURLOPT_MAXCONNECTS}
	 *
	 * @return \Clover\Classes\ClientURLOption
	 */
	public function setMaximumConnectionCount(bool $maximumConnection = true)
	{
		$this->setOption(CURLOPT_MAXCONNECTS, $maximumConnection);

		return $this->returnContext();
	}

	/**
	 * {@see \CURLOPT_AUTOREFERER}
	 *
	 * @return \Clover\Classes\ClientURLOption
	 */
	public function setAutoReferer(bool $bool = true)
	{
		$this->setOption(CURLOPT_AUTOREFERER, $bool);

		return $this->returnContext();
	}

	/**
	 * {@see \CURLOPT_NOBODY}
	 *
	 * @return \Clover\Classes\ClientURLOption
	 */
	public function setBodyEmpty(bool $bool = true)
	{
		$this->setOption(CURLOPT_NOBODY, $bool);

		return $this->returnContext();
	}

	public function setConnectionTimeout(bool $timeout = true, bool $useMilliseconds = false)
	{
		if ($useMilliseconds) {
			return $this->setConnectionTimeoutMilliseconds($timeout);
		} else {
			$this->setOption(CURLOPT_CONNECTTIMEOUT, $timeout);

			return $this->returnContext();
		}
	}

	/**
	 * {@see \CURLOPT_CONNECTTIMEOUT_MS}
	 *
	 * @return \Clover\Classes\ClientURLOption
	 */
	public function setConnectionTimeoutMilliseconds(bool $timeout = true)
	{
		$this->setOption(CURLOPT_CONNECTTIMEOUT_MS, $timeout);

		return $this->returnContext();
	}

	public function setNobody(bool $bool = true)
	{
		$this->setBodyEmpty($bool);

		return $this->returnContext();
	}

	/**
	 * {@see \CURLOPT_TRANSFERTEXT}
	 *
	 * @return \Clover\Classes\ClientURLOption
	 */
	public function setTransferText(bool $bool = true)
	{
		$this->setOption(CURLOPT_TRANSFERTEXT, $bool);

		return $this->returnContext();
	}
	
	/**
	 * {@see \CURLOPT_TCP_NODELAY}
	 *
	 * @return \Clover\Classes\ClientURLOption
	 */
	public function setTcpNodelay(bool $nodelay = true)
	{
		$this->setOption(CURLOPT_TCP_NODELAY, $nodelay);

		return $this->returnContext();
	}

	/**
	 * {@see \CURLOPT_REDIR_PROTOCOLS}
	 *
	 * @return \Clover\Classes\ClientURLOption
	 */
	public function setRedisProtocol(int $protocol = CURLPROTO_HTTP)
	{
		$this->setOption(CURLOPT_REDIR_PROTOCOLS, $protocol);

		return $this->returnContext();
	}

	/**
	 * {@see \CURLOPT_PROTOCOLS}
	 *
	 * @return \Clover\Classes\ClientURLOption
	 */
	public function setProtocol(int $protocol = CURLPROTO_HTTP)
	{
		$this->setOption(CURLOPT_PROTOCOLS, $protocol);

		return $this->returnContext();
	}

	/**
	 * {@see \CURLOPT_BINARYTRANSFER}
	 *
	 * @return \Clover\Classes\ClientURLOption
	 */
	public function setBinaryTransfer(bool $bool = true)
	{
		$this->setOption(CURLOPT_BINARYTRANSFER, $bool);

		return $this->returnContext();
	}

	public function setMaximumUploadSpeed(int $bytePerSeconds = 1000)
	{
		$this->setMaximumSendSpeed($bytePerSeconds);

		return $this->returnContext();
	}

	/**
	 * {@see \CURLOPT_MAX_SEND_SPEED_LARGE}
	 *
	 * @return \Clover\Classes\ClientURLOption
	 */
	public function setMaximumSendSpeed(int $bytePerSeconds = 1000)
	{
		$this->setOption(CURLOPT_MAX_SEND_SPEED_LARGE, $bytePerSeconds);

		return $this->returnContext();
	}

	public function setMaximumDownloadSpeed(int $bytePerSeconds = 1000)
	{
		$this->setMaximumReceiveSpeed($bytePerSeconds);

		return $this->returnContext();
	}

	/**
	 * {@see \CURLOPT_MAX_RECV_SPEED_LARGE}
	 *
	 * @return \Clover\Classes\ClientURLOption
	 */
	public function setMaximumReceiveSpeed(int $bytePerSeconds = 1000)
	{
		$this->setOption(CURLOPT_MAX_RECV_SPEED_LARGE, $bytePerSeconds);

		return $this->returnContext();
	}	
	
	/**
	 * {@see \CURLOPT_USERPWD}
	 *
	 * @return \Clover\Classes\ClientURLOption
	 */
	public function setUserPassword($password)
	{
		$this->setOption(CURLOPT_USERPWD, $password);

		return $this->returnContext();
	}

	/**
	 * {@see \CURLOPT_RESOLVE}
	 *
	 * @return \Clover\Classes\ClientURLOption
	 */
	public function setResolve($resolve)
	{
		$this->setOption(CURLOPT_RESOLVE, $resolve);

		return $this->returnContext();
	}

	/**
	 * {@see \CURLOPT_READFUNCTION}
	 *
	 * @return \Clover\Classes\ClientURLOption
	 */
	public function setReadFunction(\Closure $function)
	{
		$this->setOption(CURLOPT_READFUNCTION, $function);

		return $this->returnContext();
	}
	
	/**
	 * {@see \CURLOPT_INFILESIZE}
	 *
	 * @return \Clover\Classes\ClientURLOption
	 */
	public function setInFileSize(int $size)
	{
		$this->setOption(CURLOPT_INFILESIZE, $size);

		return $this->returnContext();
	}
	
	/**
	 * {@see \CURLOPT_INFILE}
	 *
	 * @return \Clover\Classes\ClientURLOption
	 */
	public function setInFile($file)
	{
		$this->setOption(CURLOPT_INFILE, $file);

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

			$this->setHeaders($headers);
		} else {
			$this->setHeaders($headerData);
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
	 * {@see \CURLOPT_HTTPHEADER}
	 *
	 * @return \Clover\Classes\ClientURLOption
	 */
	public function setHeaders($headers = [])
	{
		$this->setOption(CURLOPT_HTTPHEADER, $headers);

		return $this->returnContext();
	}

	/**
	 * {@see \CURLOPT_PORT}
	 *
	 * @return \Clover\Classes\ClientURLOption
	 */
	public function setPort(bool $port = true)
	{
		$this->setOption(CURLOPT_PORT, $port);

		return $this->returnContext();
	}

	/**
	 * {@see \CURLOPT_POST}
	 *
	 * @return \Clover\Classes\ClientURLOption
	 */
	public function setPostMethod(bool $bool = true)
	{
		$this->setOption(CURLOPT_POST, $bool);

		return $this->returnContext();
	}

	/**
	 * {@see \CURLOPT_HTTPGET}
	 *
	 * @return \Clover\Classes\ClientURLOption
	 */
	public function setGetMethod(bool $bool = true)
	{
		$this->setOption(CURLOPT_HTTPGET, $bool);

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
		return $this->setAuthentication(CURLAUTH_DIGEST);
	}

	private function setNoneHTTPVersion()
	{
		return $this->setHTTPVersion(CURL_HTTP_VERSION_NONE);
	}

	private function setHTTPVersion_1_0()
	{
		return $this->setHTTPVersion(CURL_HTTP_VERSION_1_0);
	}

	private function setHTTPVersion_1_1()
	{
		return $this->setHTTPVersion(CURL_HTTP_VERSION_1_1);
	}

	private function setHTTPVersion_2_0()
	{
		return $this->setHTTPVersion(CURL_HTTP_VERSION_2_0);
	}

	private function setHTTPVersion_2_TLS()
	{
		return $this->setHTTPVersion(\CURL_HTTP_VERSION_2TLS);
	}

	private function setLowSpeedLimitTime($value)
	{
		return $this->setOption(CURLOPT_LOW_SPEED_TIME, $value);
	}

	private function setHTTPPriorKnowledge()
	{
		return $this->setHTTPVersion(\CURL_HTTP_VERSION_2_PRIOR_KNOWLEDGE);
	}

	/**
	 * {@see \CURLOPT_HTTP_VERSION}
	 *
	 * @return \Clover\Classes\ClientURLOption
	 */
	private function setHTTPVersion(int $version = CURL_HTTP_VERSION_1_0)
	{
		$this->setOption(CURLOPT_HTTP_VERSION, $version);

		return $this->returnContext();
	}

	/**
	 * {@see \CURLOPT_HTTPAUTH}
	 *
	 * @return \Clover\Classes\ClientURLOption
	 */
	private function setAuthentication(int $authentication = CURLAUTH_ANY)
	{
		$this->setOption(CURLOPT_HTTPAUTH, $authentication);

		return $this->returnContext();
	}
	
	/**
	 * {@see \CURLOPT_NOSIGNAL}
	 *
	 * @return \Clover\Classes\ClientURLOption
	 */
	private function setNoSignal(bool $noSignal = false)
	{
		$this->setOption(CURLOPT_NOSIGNAL, $noSignal);

		return $this->returnContext();
	}

	/**
	 * {@see \CURLOPT_CUSTOMREQUEST}
	 *
	 * @return \Clover\Classes\ClientURLOption
	 */
	private function setCustomMethod($method)
	{
		$this->setOption(CURLOPT_CUSTOMREQUEST, $method);

		return $this->returnContext();
	}

	/**
	 * Request an HTTP Options Method
	 *
	 * @return \Clover\Classes\ClientURLOption
	 */
	private function setOptionsMethod()
	{
		return $this->setCustomMethod(HTTPRequestMethod::OPTIONS);
	}

	/**
	 * Request an HTTP Patch Method
	 *
	 * @return \Clover\Classes\ClientURLOption
	 */
	private function setPatchMethod()
	{
		return $this->setCustomMethod(HTTPRequestMethod::PATCH);
	}

	/**
	 * Request an HTTP Head Method
	 *
	 * @return \Clover\Classes\ClientURLOption
	 */
	private function setHeadMethod()
	{
		return $this->setCustomMethod(HTTPRequestMethod::HEAD);
	}

	/**
	 * Request an HTTP Put Method
	 *
	 * @return \Clover\Classes\ClientURLOption
	 */
	private function setPutMethod()
	{
		return $this->setCustomMethod(HTTPRequestMethod::PUT);
	}

	/**
	 * Request an HTTP Delete Method
	 *
	 * @return \Clover\Classes\ClientURLOption
	 */
	private function setDeleteMethod()
	{
		return $this->setCustomMethod(HTTPRequestMethod::DELETE);
	}

	/**
	 * {@see \CURLOPT_RETURNTRANSFER}
	 *
	 * @return \Clover\Classes\ClientURLOption
	 */
	public function setReturnTransfer(bool $hasResponse = true)
	{
		$this->setOption(CURLOPT_RETURNTRANSFER, $hasResponse);

		return $this->returnContext();
	}
	
	/**
	 * {@see \CURLOPT_TIMEOUT_MS}
	 */
	public function setTimeoutMilliseconds(int $milliseconds)
	{
		$this->setOption(CURLOPT_TIMEOUT_MS, $milliseconds);

		return $this->returnContext();
	}
	
	/**
	 * {@see \CURLOPT_SHARE}
	 */
	public function setShare(string $share)
	{
		$this->setOption(CURLOPT_SHARE, $share);

		return $this->returnContext();
	}
	
	/**
	 * {@see \CURLOPT_PINNEDPUBLICKEY}
	 */
	public function setPinnedPublicKey(string $key)
	{
		$this->setOption(CURLOPT_PINNEDPUBLICKEY, $key);

		return $this->returnContext();
	}
	
	/**
	 * {@see \CURLOPT_UNIX_SOCKET_PATH}
	 */
	public function setUnixSocketPath(string $path)
	{
		$this->setOption(CURLOPT_UNIX_SOCKET_PATH, $path);

		return $this->returnContext();
	}
	

	/**
	 * {@see \CURLOPT_HEADER}
	 *
	 * @return \Clover\Classes\ClientURLOption
	 */
	public function setReturnHeader(bool $hasResponse = true)
	{
		$this->setOption(CURLOPT_HEADER, $hasResponse);

		return $this->returnContext();
	}
}
