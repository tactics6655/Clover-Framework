<?php

declare(strict_types=1);

namespace Xanax\Classes\Socket;

class ServerObject
{

	private $arrayAcceptedSocketInfo  = [];
	private $arrayAcceptedSocket      = [];
	private $arrayAcceptedSocketCount = 0;
	private $arrayClientSocket        = [];
	private $clientBindHandler;
	private $SocketHandlerClass;
	private $SocketHandler;

	public function __construct($socketHandler)
	{
		$this->SocketHandlerClass = $socketHandler;
	}

	public function exceptSocketInArray($arrayClientSocket, $arrayAcceptedSocketInfo, $AcceptedSocketInArray)
	{
		if (isset($arrayClientSocket[$AcceptedSocketInArray])) {
			unset($arrayClientSocket[$AcceptedSocketInArray]);
		}

		if (isset($arrayAcceptedSocketInfo[$AcceptedSocketInArray])) {
			unset($arrayAcceptedSocketInfo[$AcceptedSocketInArray]);
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
		$this->arrayAcceptedSocketCount = $this->SocketHandlerClass->select($this->arrayAcceptedSocket, $timeout, $write, $except);
	}

	public function setArrayClient()
	{
		$this->arrayAcceptedSocket = array_merge([$this->SocketHandler], $this->arrayClientSocket);
	}

	public function close()
	{
		$this->SocketHandlerClass->close();
	}

	public function connect($address, $port, $domain = AF_INET, $type = SOCK_STREAM, $protocol = SOL_TCP)
	{
		$this->SocketHandler = $this->SocketHandlerClass->create($domain, $type, $protocol);

		if (!$this->SocketHandler) {
			return false;
		}

		$result = $this->SocketHandlerClass->bind($this->SocketHandler, $address, $port);
		if (!$result) {
			return false;
		}

		$result = $this->SocketHandlerClass->listen($this->SocketHandler);
		if (!$result) {
			return false;
		}

		return true;
	}

	public function acceptClient()
	{
		$bind = $this->SocketHandlerClass->acceptConnect($this->SocketHandler);

		if ($bind) {
			$this->clientBindHandler = $bind;

			return true;
		}

		return false;
	}
}
