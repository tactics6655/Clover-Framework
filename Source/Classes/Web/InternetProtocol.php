<?php

namespace Neko\Classes\Web;

use function gethostbyname;
use function gethostbyaddr;
use function gethostname;

class InternetProtocol
{
	public static function getHostName()
	{
		return gethostname();
	}

	public static function getHostByAddress($address)
	{
		return gethostbyaddr($address);
	}

	public static function getByHostname($hostname)
	{
		return gethostbyname($hostname);
	}

	public static function toReverseOctet($inputip)
	{
		$ipoc = explode(".", $inputip);

		return $ipoc[3] . "." . $ipoc[2] . "." . $ipoc[1] . "." . $ipoc[0];
	}
}
