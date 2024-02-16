<?php

declare(strict_types=1);

namespace Neko\Classes;

use Neko\Classes\Data as DataObject;
use Neko\Classes\ClientURLOption as ClientURLOption;
use Neko\Classes\ClientURLLastTransferInformation as ClientURLLastTransferInformation;
use Neko\Classes\Data\StringObject as StringObject;
use Neko\Implement\ClientURLInterface;

use CurlHandle;
use resource;

class ClientURL implements ClientURLInterface
{

	private static $session;

	/** @var \Neko\Classes\ClientURLOption */
	public ClientURLOption $option;

	/** @var \Neko\Classes\ClientURLLastTransferInformation */
	public ClientURLLastTransferInformation $information;

	public function __construct(bool $useLocalMethod = true, string $url = '')
	{
		if (!extension_loaded('curl')) {
			throw new \ErrorException('The CURL library is NOT loaded');
		}

		self::$session = $this->getSession();

		if ($useLocalMethod) {
			$this->option      = new ClientURLOption(self::$session);
			$this->information = new ClientURLLastTransferInformation(self::$session);
		}
	}

	public function getSession() :?ClientURL
	{
		if (self::$session == null) {
			self::$session = $this->initialize();
		}

		return self::$session;
	}

	public function getLastErrorMessage(): string
	{
		return curl_error(self::$session);
	}

	public function getLastErrorNumber(): int
	{
		return curl_errno(self::$session);
	}

	public function initialize($instance = '') :mixed
	{
		return curl_init($instance);
	}

	public function reset() :void
	{
		if (function_exists('curl_reset')) {
			curl_reset(self::$session);
		}
	}

	public function option() :?ClientURLOption
	{
		if (!$this->option) {
			$this->option = new ClientURLOption(self::$session);
		}

		return $this->option;
	}

	public function information() :?ClientURLLastTransferInformation
	{
		if (!$this->information) {
			$this->information = new ClientURLLastTransferInformation(self::$session);
		}

		return $this->information;
	}

	public function setOption(int $option, $value) :bool
	{
		return curl_setopt(self::$session, $option, $value);
	}

	public function close(): void
	{
		curl_close(self::$session);
	}

	public function getHeaderOptions() :array
	{
		return $this->option::$headers;
	}

	public function execute() :mixed
	{
		$result = curl_exec(self::$session);

		return (new DataObject($result))->toObject();
	}
}
