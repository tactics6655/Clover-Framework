<?php

namespace Xanax\Implement;

interface SocketHandlerInterface {
	
	public function create($domain = AF_INET, $type = SOCK_STREAM, $protocol = SOL_TCP) :resource;

	public function getPeerName($socketHandler) :array;

	public function close($socketHandler) :void;

	public function select(array $socketArray, $write = null, $except = null, $timeout = 10);

	public function acceptConnect($socketHandler);

	public function listen($socketHandler) :bool;

	public function bind($socketHandler, $address, $port) :bool;

	public function readPacket($socketHandler, $length, $type = PHP_BINARY_READ) :string;

	public function writeSocket($socketHandler, $buffer, $length = -1) :int;

	public function connect($socketHandler, $address, $port) :bool;

	public function getLastErrorMessage();
	
}
