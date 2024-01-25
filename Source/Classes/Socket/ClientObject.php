<?php

declare(strict_types=1);

namespace Neko\Classes;

use Neko\Classes\Socket\Handler as SocketHandler;

use Socket;

class ClientObject
{

	private SocketHandler $socketHandlerClass;
	private Socket $socketHandler;

	public function __construct($socketHandler)
	{
		if ($socketHandler instanceof SocketHandler) {
			$this->socketHandlerClass = $socketHandler;
		}
	}

	// Send packet to socket of server
	public function sendPacket($string = ''): bool
	{
		$result = $this->socketHandlerClass->writeSocket($this->socketHandler, $string, strlen($string));

		if ($result === 0) {
			return false;
		}

		return true;
	}

	// Close socket
	public function close($socket): void
	{
		$this->socketHandlerClass->close($this->socketHandler);
	}

	// Connect socket
	public function connect($address, $port, $domain = AF_INET, $type = SOCK_STREAM, $protocol = SOL_TCP): bool
	{
		$this->socketHandler = $this->socketHandlerClass->create($domain, $type, $protocol);

		if (!$this->socketHandler) {
			return false;
		}

		$result = $this->socketHandlerClass->connect($this->socketHandler, $address, $port);

		if (!$result) {
			return false;
		}

		return true;
	}

	// Connect TCP socket
	public function connectTCP($address, $port, $domain = AF_INET): bool
	{
		return $this->connect($address, $port, $domain, SOCK_STREAM, SOL_TCP);
	}

	// Connect UDP socket
	public function connectUDP($address, $port, $domain = AF_INET): bool
	{
		return $this->connect($address, $port, $domain, SOCK_DGRAM, SOL_UDP);
	}
}
