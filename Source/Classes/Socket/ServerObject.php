<?php

declare(strict_types=1);

namespace Clover\Classes\Socket;

class ServerObject
{

	private $arrayAcceptedSocketInfo  = [];
	private $arrayAcceptedSocket      = [];
	private $arrayAcceptedSocketCount = 0;
	private $arrayClientSocket        = [];
	private $clientBindHandler;
	private $socketHandlerClass;
	private $socketHandler;

	public function __construct($socketHandler)
	{
		$this->socketHandlerClass = $socketHandler;
	}

	public function exceptSocketInArray($arrayClientSocket, $arrayAcceptedSocketInfo, $acceptedSocketInArray)
	{
		if (isset($arrayClientSocket[$acceptedSocketInArray])) {
			unset($arrayClientSocket[$acceptedSocketInArray]);
		}

		if (isset($arrayAcceptedSocketInfo[$acceptedSocketInArray])) {
			unset($arrayAcceptedSocketInfo[$acceptedSocketInArray]);
		}

		if (!$this->getAcceptedClientInArray()) {
			$this->arrayAcceptedSocketInfo = [];
			$this->arrayAcceptedSocket     = [];
		}
	}

	public function getAcceptedClientInArray()
	{
		if (count($this->arrayAcceptedSocketInfo) === 0) {
			return false;
		}

		return true;
	}

	public function hasAcceptedClientInArray()
	{
		if ($this->getAcceptedClientInArray() > 0) {
			return true;
		}

		return false;
	}

	public function selectArrayClient($timeout = 10, $write = null, $except = null)
	{
		$this->arrayAcceptedSocketCount = $this->socketHandlerClass->select($this->arrayAcceptedSocket, $timeout, $write, $except);
	}

	public function setArrayClient()
	{
		$this->arrayAcceptedSocket = array_merge([$this->socketHandler], $this->arrayClientSocket);
	}

	public function close()
	{
		$this->socketHandlerClass->close();
	}

	public function connect($address, $port, $domain = AF_INET, $type = SOCK_STREAM, $protocol = SOL_TCP)
	{
		$this->socketHandler = $this->socketHandlerClass->create($domain, $type, $protocol);

		if (!$this->socketHandler) {
			return false;
		}

		$result = $this->socketHandlerClass->bind($this->socketHandler, $address, $port);
		if (!$result) {
			return false;
		}

		$result = $this->socketHandlerClass->listen($this->socketHandler);
		if (!$result) {
			return false;
		}

		return true;
	}

	public function acceptClient()
	{
		$bind = $this->socketHandlerClass->acceptConnect($this->socketHandler);

		if ($bind) {
			$this->clientBindHandler = $bind;

			return true;
		}

		return false;
	}
}
