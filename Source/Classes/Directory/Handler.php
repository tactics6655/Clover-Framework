<?php

declare(strict_types=1);

namespace Clover\Classes\Directory;

use Clover\Classes\Data\ArrayObject;
use RecursiveIteratorIterator;
use RecursiveDirectoryIterator;
use FilesystemIterator;
use Clover\Implement\DirectoryHandlerInterface;
use Clover\Implement\FileHandlerInterface;
use Clover\Exception\DirectoryHandler\DirectoryIsNotExistsException as DirectoryIsNotExistsException;
use Clover\Exception\DirectoryHandler\DirectoryIsExistsException as DirectoryIsExistsException;
use Clover\Classes\File\Handler as FileHandler;

class Handler implements DirectoryHandlerInterface
{
	private $fileHandler;
	private $directoryDepth;

	public function __construct(FileHandlerInterface $fileHandler = null)
	{
		if ($fileHandler instanceof FileHandlerInterface) {
			$this->fileHandler = $fileHandler;
		} else {
			$this->fileHandler = new FileHandler();
		}

		$this->directoryDepth = -1;
	}

	public static function remove($directory, $context = NULL)
	{
		rmdir($directory, $context);
	}

	/**
	 * Get free space of root directory
	 *
	 * @param string $prefix
	 *
	 * @return int
	 */
	public static function getFreeSpace($prefix = '/')
	{
		$diskFreeSpaces = -1;

		if (function_exists('disk_free_space')) {
			$diskFreeSpaces = disk_free_space($prefix);
		}

		return $diskFreeSpaces;
	}

	/**
	 * Get total space of root directory
	 *
	 * @param string $prefix
	 *
	 * @return int
	 */
	public function getTotalSpace($prefix = '/')
	{
		$diskFreeSpaces = -1;

		if (function_exists('disk_total_space')) {
			$diskFreeSpaces = disk_total_space($prefix);
		}

		return $diskFreeSpaces;
	}

	public function hasCurrentWorkingLocation()
	{
		return $this->getCurrentWorkingLocation();
	}

	public function getCurrentWorkingLocation()
	{
		return getcwd();
	}

	/**
	 * Check that path is directory
	 *
	 * @param string $directoryPath
	 *
	 * @return boolean
	 */
	public static function isDirectory(string $directoryPath)
	{
		$return = is_dir($directoryPath);

		return $return;
	}

	public static function makeMultiple(array $pathList, int $mode = 644)
	{
		/** @var string $path */
		foreach ($pathList as $path) {
			if (self::isDirectory($path)) {
				continue;
			}

			if (!mkdir($path, $mode, true)) {
				return false;
			}
		}

		return true;
	}

	/**
	 * Make directory with permissions
	 *
	 * @param string $directoryPath
	 * @param int    $permission
	 *
	 * @return boolean
	 */
	public function make(string $directoryPath, int $permission = 644)
	{
		if (self::isDirectory($directoryPath)) {
			throw new DirectoryIsExistsException('');
		}

		return $this->create($directoryPath);
	}

	public function create(string $directoryPath, int $permission = 644)
	{
		if (self::isDirectory($directoryPath)) {
			throw new DirectoryIsExistsException('');
		}

		$return = mkdir($directoryPath, $permission, true);

		return $return;
	}

	/**
	 * Get file counts of directory
	 *
	 * @param string $directoryPath
	 *
	 * @return int
	 */
	public function getFileCount(string $directoryPath): int
	{
		if (!self::isDirectory($directoryPath)) {
			throw new DirectoryIsNotExistsException(sprintf('Directory %s is empty', $directoryPath));
		}

		$iterator = new RecursiveDirectoryIterator($directoryPath, FilesystemIterator::SKIP_DOTS);
		$return   = iterator_count($iterator);

		return $return;
	}

	/**
	 * Check that directory is empty
	 *
	 * @param string $directoryPath
	 *
	 * @return boolean
	 */
	public function isEmpty(string $directoryPath): bool
	{
		if (!self::isDirectory($directoryPath)) {
			throw new DirectoryIsNotExistsException();
		}

		return ($this->getFileCount($directoryPath) === 0);
	}

	/**
	 * Delete directory
	 *
	 * @param string $directoryPath
	 *
	 * @return boolean
	 */
	public function delete(string $directoryPath)
	{
		if (!self::isDirectory($directoryPath)) {
			throw new DirectoryIsNotExistsException();
		}

		if (!$this->isEmpty($directoryPath) || !$this->empty($directoryPath)) {
			return false;
		}

		$iterator = new RecursiveIteratorIterator(
			new RecursiveDirectoryIterator($directoryPath, RecursiveDirectoryIterator::SKIP_DOTS),
			RecursiveIteratorIterator::CHILD_FIRST
		);

		$iterator->setMaxDepth(-1); // Absolutely delete folders

		/** @var RecursiveDirectoryIterator $fileInformation */
		foreach ($iterator as $fileInformation) {
			if (!$fileInformation->isDir()) {
				continue;
			}

			if (unlink($fileInformation->getRealPath()) === false) {
				return false;
			}
		}

		return true;
	}

	/**
	 * Copy directory to specific path
	 *
	 * @param string $directoryPath
	 * @param string $copyPath
	 *
	 * @return void
	 */
	public function copy(string $directoryPath, string $copyPath)
	{
		if (!self::isDirectory($directoryPath)) {
			throw new DirectoryIsNotExistsException();
		}

		$directoryIterator = new RecursiveDirectoryIterator($directoryPath, RecursiveDirectoryIterator::SKIP_DOTS);
		$iterator          = new RecursiveIteratorIterator($directoryIterator, RecursiveIteratorIterator::SELF_FIRST);

		/** @var RecursiveDirectoryIterator[] $iterator */
		foreach ($iterator as $item) {
			$destination = sprintf("%s%s%S", $copyPath, DIRECTORY_SEPARATOR, $item->getSubPathName());

			if ($item->isDir()) {
				$this->create($destination);
				continue;
			}

			$this->fileHandler->copy($item->getRealPath(), $destination);
		}
	}

	/**
	 * Get file size of directory
	 *
	 * @param string $directoryPath
	 *
	 * @return int
	 */
	public static function getSize(string $directoryPath)
	{
		if (!self::isDirectory($directoryPath)) {
			throw new DirectoryIsNotExistsException();
		}

		$size = 0;

		$skipDots = new RecursiveDirectoryIterator($directoryPath, RecursiveDirectoryIterator::SKIP_DOTS);

		/** @var RecursiveDirectoryIterator $file */
		foreach (new RecursiveIteratorIterator($skipDots) as $file) {
			$size += $file->getSize();
		}

		return $size;
	}

	/**
	 * Get configure max depth of recursive
	 *
	 * @return int
	 */
	public function getMaxDepth()
	{
		return $this->directoryDepth;
	}

	/**
	 * Set max depth of recursive
	 *
	 * @param string $directoryPath
	 *
	 * @return bool
	 */
	public function setMaxDepth(int $depth): bool
	{
		if ($this->getMaxDepth() === $this->directoryDepth) {
			return false;
		}

		$this->directoryDepth = intval($depth);

		return true;
	}

	/**
	 * Rename directory
	 *
	 * @param string $directoryPath
	 * @param string $replacement
	 *
	 * @return boolean
	 */
	public function rename(string $directoryPath, string $string, string $replacement)
	{
		if (!self::isDirectory($directoryPath)) {
			throw new DirectoryIsNotExistsException();
		}

		$iterator = new RecursiveIteratorIterator(
			new RecursiveDirectoryIterator($directoryPath, RecursiveDirectoryIterator::SKIP_DOTS),
			RecursiveIteratorIterator::SELF_FIRST
		);

		/** @var RecursiveDirectoryIterator[] $iterator */
		foreach ($iterator as $folderPath => $fileInformation) {
			if (!$fileInformation->isDir()) {
				continue;
			}

			$folderPath       = $fileInformation->getPathName();
			$newDirectoryName = preg_replace($replacement, $string, $folderPath);

			if ($folderPath === $newDirectoryName) {
				continue;
			}

			if (!self::isDirectory($folderPath)) {
				return false;
			}

			if (self::isDirectory($newDirectoryName)) {
				return false;
			}

			rename($folderPath, $newDirectoryName);
		}

		return true;
	}

	/**
	 * Rename inner files
	 *
	 * @param string $directoryPath
	 * @param string $replacement
	 * @param string $string
	 *
	 * @return boolean
	 */
	public function renameInnerFiles(string $directoryPath, $replacement, $string = null)
	{
		if (!self::isDirectory($directoryPath)) {
			throw new DirectoryIsNotExistsException();
		}

		$iterator = new RecursiveIteratorIterator(
			new RecursiveDirectoryIterator($directoryPath, RecursiveDirectoryIterator::SKIP_DOTS),
			RecursiveIteratorIterator::SELF_FIRST
		);

		foreach ($iterator as $path => $fileInformation) {
			if (!$fileInformation->isDir()) {
				continue;
			}

			$rootDirectory = $fileInformation->getPathName();

			foreach (scandir($rootDirectory) as $targetFilename) {
				$filePath = sprintf('%s/%s', $rootDirectory, $targetFilename);

				$newFileName = $targetFilename;

				if (@preg_match($replacement, null) === true) {
					$newFileName = preg_replace($replacement, $string, $targetFilename);
				}

				$newFileName = sprintf('%s/%s', $rootDirectory, $newFileName);

				if ($filePath === $newFileName) {
					continue;
				}

				if (!FileHandler::isExists($filePath)) {
					return false;
				}

				if (!FileHandler::isExists($newFileName)) {
					return false;
				}

				rename($filePath, $newFileName);
			}
		}

		return true;
	}

	public static function getList($path = './', $type = null, $includePath = false, $includeSubDirectory = false)
	{
		$handle = opendir($path);
		if (!$handle) {
			return false;
		}

		$directoryList = new ArrayObject();

		if ($includeSubDirectory) {
			$di = new RecursiveDirectoryIterator($path);

			/** @var RecursiveDirectoryIterator $file */
			foreach (new RecursiveIteratorIterator($di) as $filename => $file) {
				if (strtoupper($type) == 'FILE') {
					$passed = is_file($filename);
				} else if (strtoupper($type) == 'PATH') {
					$passed = is_dir($filename);
				}

				if (!$passed) {
					continue;
				}

				if ($includePath) {
					$directoryList[] = $filename;
				} else {
					$directoryList[] = $file->getFilename();
				}
			}

			return $directoryList;
		}

		while (false !== ($file = readdir($handle))) {
			$passed = true;

			$filePath = $path . '/' . $file;

			$type = $type == null ? $type : strtoupper($type);

			if ($type == 'FILE') {
				$passed = is_file($filePath);
			} else if ($type == 'PATH') {
				$passed = is_dir($filePath);
			}

			if (!$passed) {
				continue;
			}

			if ($includePath) {
				$directoryList[] = $filePath;
			} else {
				$directoryList[] = $file;
			}
		}

		return $directoryList;
	}

	/**
	 * Get file list in directory
	 *
	 * @param string  $directoryPath
	 * @param boolean $sort
	 *
	 * @return array
	 */
	public function getFileList($directoryPath = './', $sort = false)
	{
		if (!self::isDirectory($directoryPath)) {
			throw new DirectoryIsNotExistsException();
		}

		$fileList = [];

		$iterator = new RecursiveIteratorIterator(
			new RecursiveDirectoryIterator($directoryPath, RecursiveDirectoryIterator::SKIP_DOTS),
			RecursiveIteratorIterator::CHILD_FIRST
		);

		if ($this->getMaxDepth() !== -1) {
			$iterator->setMaxDepth($this->getMaxDepth());
		}

		/** @var RecursiveDirectoryIterator[] $iterator */
		foreach ($iterator as $fileInformation) {
			if (!$fileInformation->isFile()) {
				continue;
			}

			$fileList[] = $fileInformation->getRealPath();
		}

		if ($sort) {
			sort($fileList);
		}

		return $fileList;
	}

	public function empty(string $directoryPath)
	{
		if (!self::isDirectory($directoryPath)) {
			throw new DirectoryIsNotExistsException();
		}

		$iterator = new RecursiveIteratorIterator(
			new RecursiveDirectoryIterator($directoryPath, RecursiveDirectoryIterator::SKIP_DOTS),
			RecursiveIteratorIterator::CHILD_FIRST
		);

		if ($this->getMaxDepth() !== -1) {
			$iterator->setMaxDepth($this->getMaxDepth());
		}

		/** @var RecursiveDirectoryIterator[] $iterator */
		foreach ($iterator as $fileInformation) {
			if ($fileInformation->isDir()) {
				continue;
			}

			if (unlink($fileInformation->getRealPath()) === false) {
				return false;
			}
		}

		return true;
	}
}
