<?php

namespace Clover\Classes\Web;

use Clover\Classes\Web\InternetProtocol as InternetProtocol;
use Clover\Classes\HTTP\Request as RequestHandler;

class TheOnionRouting
{

	public function __construct()
	{
	}

	public static function isExitNode()
	{
		$ipAddress = RequestHandler::getRemoteIPAddress();
		$serverPort = RequestHandler::getPort();
		$reverseIP = InternetProtocol::toReverseOctet($ipAddress);

		$torExitNodeHostName = sprintf("%s.%s.%s.ip-port.exitlist.torproject.org", $reverseIP, $serverPort, $reverseIP);
		$hostName = InternetProtocol::getByHostname($torExitNodeHostName);

		return $hostName === '127.0.0.2';
	}
}
