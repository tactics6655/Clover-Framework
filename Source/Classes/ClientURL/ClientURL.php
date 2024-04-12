<?php

declare(strict_types=1);

namespace Clover\Classes;

use Clover\Classes\Data as DataObject;
use Clover\Classes\ClientURLOption as ClientURLOption;
use Clover\Classes\ClientURLLastTransferInformation as ClientURLLastTransferInformation;
use Clover\Classes\Data\StringObject as StringObject;
use Clover\Implement\ClientURLInterface;

use CurlHandle;
use resource;

class ClientURL implements ClientURLInterface
{

	private static $session;

	/** @var \Clover\Classes\ClientURLOption */
	public ClientURLOption $option;

	/** @var \Clover\Classes\ClientURLLastTransferInformation */
	public ClientURLLastTransferInformation $information;

	public function __construct(bool $useLocalMethod = true, string $url = '')
	{
		if (!$this->isSupported()) {
			throw new \ErrorException('The CURL library is not loaded');
		}

		self::$session = $this->getSession();

		if ($useLocalMethod) {
			$this->option      = new ClientURLOption(self::$session);
			$this->information = new ClientURLLastTransferInformation(self::$session);
		}
	}

	public function isSupported()
	{
		return extension_loaded('curl');
	}

	public function getSession()
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

	public function initialize($instance = ''): mixed
	{
		return curl_init($instance);
	}

	public function reset(): void
	{
		if (function_exists('curl_reset')) {
			curl_reset(self::$session);
		}
	}

	public function option(): ?ClientURLOption
	{
		if (!$this->option) {
			$this->option = new ClientURLOption(self::$session);
		}

		return $this->option;
	}

	public function information(): ?ClientURLLastTransferInformation
	{
		if (!$this->information) {
			$this->information = new ClientURLLastTransferInformation(self::$session);
		}

		return $this->information;
	}

	public function setOption(int $option, $value): bool
	{
		return curl_setopt(self::$session, $option, $value);
	}

	public function close(): void
	{
		curl_close(self::$session);
	}

	public function getOptions(): array
	{
		return $this->option::$curl_options;
	}

	public function getHeaderOptions(): array
	{
		return $this->option::$headers;
	}

	public function execute(): mixed
	{
		$result = curl_exec(self::$session);

		return (new DataObject($result))->toObject();
	}
}
