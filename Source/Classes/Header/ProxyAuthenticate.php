<?php


declare(strict_types=1);

namespace Xanax\Classes;

class ProxyAuthenticate extends Header
{

	public function response($value)
	{
		parent::responseWithKey('Proxy-Authenticate', $value);
	}

	public function basicRealm($value)
	{
		$key = "Basic realm";
		$data = "$key=\"$value\"";

		$this->response($data);
	}

}
