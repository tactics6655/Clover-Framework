<?php

namespace Neko\Implement;

interface DirectoryHandlerInterface
{

	public function getFreeSpace($prefix = '/');

	public function renameInnerFiles(string $directoryPath, $replacement, $string = null);

	public function hasCurrentWorkingLocation();

	public function getCurrentWorkingLocation();

	public static function isDirectory(string $directoryPath);

	public function make(string $directoryPath);

	public function create(string $directoryPath);

	public function getFileCount(string $directoryPath);

	public function isEmpty(string $directoryPath);

	public function delete(string $directoryPath);

	public function copy(string $directoryPath, string $copyPath);

	public function getMaxDepth();

	public function setMaxDepth(int $depth);

	public function empty(string $directoryPath);

	public static function getSize(string $directoryPath);
}
