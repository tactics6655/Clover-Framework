<?php

namespace Clover\Implement;

interface SocketHandlerInterface
{

	/**
	 *
	 * Domain = [AF_INET, AF_INET6, AF_UNIX]
	 * Type = [SOCK_STREAM, SOCK_DGRAM, SOCK_SEQPACKET, SOCK_RAW, SOCK_RDM]
	 * Protocol = [SOL_TCP, SOL_UDP]
	 * 
	 * @return \Socket|bool|resource
	 */
	public function create($domain = AF_INET, $type = SOCK_STREAM, $protocol = SOL_TCP);

	public function getPeerName($socketHandler): array;

	public function close($socketHandler): void;

	public function select(array $socketArray, $write = null, $except = null, $timeout = 10);

	public function acceptConnect($socketHandler);

	public function listen($socketHandler): bool;

	public function bind($socketHandler, $address, $port): bool;

	public function readPacket($socketHandler, $length, $type = PHP_BINARY_READ): bool|string;

	public function writeSocket($socketHandler, $buffer, $length = -1): int;

	public function connect($socketHandler, $address, $port): bool;

	public function getLastErrorMessage();
}
