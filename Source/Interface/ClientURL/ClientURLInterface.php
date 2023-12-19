<?php

namespace Xanax\Implement;

interface ClientURLInterface
{

	public function reset();

	public function getLastErrorNumber();

	public function getLastErrorMessage();

	public function initialize($instance = '');

	public function getSession();

	public function option();

	public function information();

	public function setOption(int $option, $value);

	public function close();

	public function execute();
}
