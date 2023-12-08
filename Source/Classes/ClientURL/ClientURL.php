<?php

declare(strict_types=1);

namespace Xanax\Classes;

use Xanax\Classes\Data as DataObject;
use Xanax\Classes\ClientURLOption as ClientURLOption;
use Xanax\Classes\ClientURLLastTransferInformation as ClientURLLastTransferInformation;
use Xanax\Classes\Data\StringObject as StringObject;
use Xanax\Implement\ClientURLInterface;

class ClientURL implements ClientURLInterface {
	private static $session;

	/** @var Xanax\Classes\ClientURLOption */
	public ClientURLOption $Option;

	/** @var Xanax\Classes\ClientURLLastTransferInformation */
	public ClientURLLastTransferInformation $Information;

	public function __construct(bool $useLocalMethod = true, string $url = '')
	{
		if (!extension_loaded('curl'))
		{
            throw new \ErrorException('The CURL library is NOT loaded');
        }
		
		self::$session = $this->getSession();

		if ($useLocalMethod)
		{
			$this->Option      = new ClientURLOption(self::$session);
			$this->Information = new ClientURLLastTransferInformation(self::$session);
		}
	}

	public function getSession()
	{
		if (self::$session == null)
		{
			self::$session = $this->Initialize();
		}

		return self::$session;
	}

	public function getLastErrorMessage() :string {
		return curl_error(self::$session);
	}

	public function getLastErrorNumber() :int {
		return curl_errno(self::$session);
	}

	public function Initialize($instance = '')
	{
		return curl_init($instance);
	}

	public function Reset()
	{
		if (function_exists('curl_reset'))
		{
			curl_reset(self::$session);
		}
	}

	public function Option()
	{
		if (!$this->Option)
		{
			$this->Option = new ClientURLOption(self::$session);
		}

		return $this->Option;
	}

	public function Information()
	{
		if (!$this->Information)
		{
			$this->Information = new ClientURLLastTransferInformation(self::$session);
		}

		return $this->Information;
	}

	public function setOption(int $option, $value)
	{
		curl_setopt(self::$session, $option, $value);
	}

	public function Close() :void {
		curl_close(self::$session);
	}

	public function getHeaderOptions()
	{
		return $this->Option::$headerArrayData;
	}

	public function Execute()
	{
		$result = curl_exec(self::$session);

		return (new DataObject($result))->toObject();
	}
}
