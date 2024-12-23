<?php

declare(strict_types=1);

namespace Clover\Classes\Socket;

use Clover\Implement\SocketHandlerInterface;

class Handler implements SocketHandlerInterface
{

	public function create($domain = AF_INET, $type = SOCK_STREAM, $protocol = SOL_TCP)
	{
		return \socket_create($domain, $type, $protocol);
	}

	public function getPeerName($socketHandler): array
	{
		$hasPeerInfo = socket_getpeername($socketHandler, $address, $port);

		if ($hasPeerInfo) {
			return [
				'IPAddress' => $address,
				'Port'      => $port
			];
		}

		return [];
	}

	public function close($socketHandler): void
	{
		socket_close($socketHandler);
	}

	public function select(array $socketArray, $write = null, $except = null, $timeout = 10)
	{
		return socket_select($socketArray, $write, $except, $timeout);
	}

	public function acceptConnect($socketHandler)
	{
		socket_accept($socketHandler);
	}

	public function listen($socketHandler): bool
	{
		return socket_listen($socketHandler);
	}

	public function bind($socketHandler, $address, $port): bool
	{
		return socket_bind($socketHandler, $address, $port);
	}

	public function readPacket($socketHandler, $length, $type = PHP_BINARY_READ): bool|string
	{
		return socket_read($socketHandler, $length, $type);
	}

	public function writeSocket($socketHandler, $buffer, $length = -1): int
	{
		if ($length === -1) {
			$length = strlen($buffer);
		}

		return socket_write($socketHandler, $buffer, $length);
	}

	public function connect($socketHandler, $address, $port): bool
	{
		return socket_connect($socketHandler, $address, $port);
	}

	public function getErrorMessage(int $message = 0)
	{
		return socket_strerror($message);
	}

	public function getLastErrorMessage()
	{
		return $this->getErrorMessage(socket_last_error());
	}
}
