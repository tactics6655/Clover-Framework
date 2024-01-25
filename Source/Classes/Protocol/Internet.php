<?php

namespace Neko\Classes\Protocol;

class Internet
{

	public static function isV6Protocol($address)
	{
		return preg_match("/(?:(?:(?:[0-9a-f]{1,4}:){6}|::(?:[0-9a-f]{1,4}:){5}|(?:[0-9a-f]{1,4})?::(?:[0-9a-f]{1,4}:){4}|(?:(?:[0-9a-f]{1,4}:){0,1}[0-9a-f]{1,4})?::(?:[0-9a-f]{1,4}:){3}|(?:(?:[0-9a-f]{1,4}:){0,2}[0-9a-f]{1,4})?::(?:[0-9a-f]{1,4}:){2}|(?:(?:[0-9a-f]{1,4}:){0,3}[0-9a-f]{1,4})?::(?:[0-9a-f]{1,4}:)|(?:(?:[0-9a-f]{1,4}:){0,4}[0-9a-f]{1,4})?::)(?:[0-9a-f]{1,4}:[0-9a-f]{1,4}|(?:(?:25[0-5]|2[0-4][0-9]|[01]?[0-9]?[0-9])\\.){3}(?:(?:25[0-5]|2[0-4][0-9]|[01]?[0-9]?[0-9])))|(?:(?:[0-9a-f]{1,4}:){0,5}[0-9a-f]{1,4})?::[0-9a-f]{1,4}|(?:(?:[0-9a-f]{1,4}:){0,6}[0-9a-f]{1,4})?::)/", $address, $matches);
	}

	public function isValid(string $internetProtocol, string $type = ''): bool
	{
		switch (strtolower($type)) {
			case 'ipv4':
				$filter = FILTER_FLAG_IPV4;
				break;
			case 'ipv6':
				$filter = FILTER_FLAG_IPV6;
				break;
			default:
				$filter = null;
				break;
		}

		return boolval(filter_var($internetProtocol, FILTER_VALIDATE_IP, $filter));
	}
}
