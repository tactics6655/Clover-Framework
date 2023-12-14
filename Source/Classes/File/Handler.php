<?php

declare(strict_types=1);

namespace Xanax\Classes\File;

use Xanax\Enumeration\FileMode;
use Xanax\Enumeration\FileSizeUnit;

// Classes

use Xanax\Classes\FileSystem\Handler as FileSystemHandler;
use Xanax\Classes\Directory\Handler as DirectoryHandler;

// Implements

use Xanax\Implement\FileHandlerInterface as FileHandlerInterface;
use Xanax\Implement\DirectoryHandlerInterface as DirectoryHandlerInterface;

// Message

use Xanax\Classes\File\Functions as FileFunctions;

class Handler implements FileHandlerInterface
{
	protected $useStatFunction = [
		'stat',
		'lstat',
		'file_exists',
		'is_writable',
		'is_readable',
		'is_executable',
		'is_file',
		'is_dir',
		'is_link',
		'filectime',
		'fileatime',
		'filemtime',
		'fileinode',
		'filegroup',
		'fileowner',
		'filesize',
		'filetype',
		'fileperms'
	];

	// Last catched error
	private static $lastError;

	// Handler of file system
	private $fileSystemHandler;

	// Handler of directory system
	private $directoryHandler;

	public function __construct(FileHandlerInterface $fileSystemHandler = null, DirectoryHandlerInterface $directoryHandler = null)
	{
		$this->fileSystemHandler = isset($fileSystemHandler) ? $fileSystemHandler : new FileSystemHandler();
		$this->directoryHandler = isset($directoryHandler) ? $directoryHandler : new DirectoryHandler($this);
	}

	public static function generateHashByContents($algorithm = 'md5', $filePath = '', $rawContents = ''): string
	{
		return FileFunctions::generateHashByContents($algorithm, $filePath, $rawContents);
	}

	public function getStandardErrorStream($mode = FileMode::READ_ONLY)
	{
		return FileFunctions::getStandardErrorStream($mode);
	}

	public static function getStandardOutputStream($mode = FileMode::READ_ONLY)
	{
		return FileFunctions::getStandardOutputStream($mode);
	}

	public static function getStandardInputStream($mode = FileMode::READ_ONLY)
	{
		return FileFunctions::getStandardInputStream($mode);
	}

	public static function getFilterStream($mode = 'rb')
	{
		return FileFunctions::getFilterStream($mode);
	}

	public static function getTemporaryStream($mode = 'rb')
	{
		return FileFunctions::getTemporaryStream($mode);
	}

	public static function getMemoryStream($mode = 'rb')
	{
		return FileFunctions::getMemoryStream($mode);
	}

	public static function getInputStream($mode = 'rb')
	{
		return FileFunctions::getInputStream($mode);
	}

	public static function getOutputStream($mode = FileMode::WRITE_ONLY)
	{
		return FileFunctions::getOutputStream($mode);
	}

	public static function isEqual($firstPath, $secondPath, $chunkSize = 500)
	{
		return FileFunctions::isEqual($firstPath, $secondPath, $chunkSize);
	}

	public static function Open($filePath, $mode, $use_include_path = false)
	{
		return FileFunctions::Open($filePath, $mode, $use_include_path);
	}

	public static function closeProcess($processResource)
	{
		return FileFunctions::closeProcess($processResource);
	}

	public static function openProcess($processPath, $mode = FileMode::WRITE_ONLY)
	{
		return FileFunctions::openProcess($processPath, $mode);
	}

	public static function clearStatusCache($filePath): void
	{
		FileFunctions::clearStatusCache($filePath);
	}

	public static function convertToNomalizePath($filePath): string
	{
		return FileFunctions::convertToNomalizePath($filePath);
	}

	public static function isValidHandler($fileHandler)
	{
		return FileFunctions::isValidHandler($fileHandler);
	}

	public static function getPointerLocation($fileHandler)
	{
		return FileFunctions::getPointerLocation($fileHandler);
	}

	public static function createCache(string $filePath, string $destination)
	{
		return FileFunctions::createCache($filePath, $destination);
	}

	public static function isReadable(string $filePath): bool
	{
		return FileFunctions::isReadable($filePath);
	}

	public static function parseINI(string $filePath)
	{
		return FileFunctions::parseINI($filePath);
	}

	public static function getMIMEContentTypeFromMagicMIME($filePath)
	{
		return FileFunctions::getMIMEContentTypeFromMagicMIME($filePath);
	}

	public static function getMIMEContentTypeFromAlaMimetypeExtension($filePath)
	{
		return FileFunctions::getMIMEContentTypeFromAlaMimetypeExtension($filePath);
	}

	public static function getMIMEContentType($filePath)
	{
		return FileFunctions::getMIMEContentType($filePath);
	}

	public static function isLocked($filePath): bool
	{
		return FileFunctions::isLocked($filePath);
	}

	public static function getLine($fileHandler, int $length): string
	{
		return FileFunctions::getLine($fileHandler, $length);
	}

	public static function getCharacter($fileHandler, int $length): string
	{
		return FileFunctions::getCharacter($fileHandler);
	}

	public static function getPermission($filePath): int
	{
		return fileperms($filePath);
	}

	public static function getPermissionCode($filePath): string
	{
		$permission = self::getPermission($filePath);

		return substr(sprintf('%o', $permission), -4);
	}

	public static function getOwner($filePath): int
	{
		return fileowner($filePath);
	}

	public static function createTemporary()
	{
		FileFunctions::createTemporary();
	}

	public function createUniqueTemporary($directory, $prefix)
	{
		return FileFunctions::createUniqueTemporary($directory, $prefix);
	}

	public static function setAccessAndModificatinTime($filePath, $time, $atime): void
	{
		FileFunctions::setAccessAndModificatinTime($filePath, $time, $atime);
	}

	public static function Unlock($fileHandler): void
	{
		FileFunctions::Unlock($fileHandler);
	}

	public function setPermission(string $filePath, int $mode): bool
	{
		return FileFunctions::setPermission($filePath, $mode);
	}

	public function changeGroup(string $filePath, string $group): bool
	{
		return FileFunctions::changeGroup($filePath, $group);
	}

	public static function Lock($fileHandler, $mode = FileMode::READ_ONLY)
	{
		FileFunctions::Lock($fileHandler, $mode);
	}

	public static function isEmpty(string $filePath): bool
	{
		return FileFunctions::isEmpty($filePath);
	}

	public static function isExists(string $filePath): bool
	{
		return FileFunctions::isExists($filePath);
	}

	public static function isUnknownFile(string $filePath): bool
	{
		return FileFunctions::isUnknownFile($filePath);
	}

	public function getSymbolicLink(string $symbolicLink)
	{
		return FileFunctions::getSymbolicLink($symbolicLink);
	}

	public static function isSymbolicLink(string $filePath): bool
	{
		return FileFunctions::isSymbolicLink($filePath);
	}

	public static function isRegularFile(string $filePath): bool
	{
		return FileFunctions::isRegularFile($filePath);
	}

	public static function isContainFolder(string $basePath, string $filePath): bool
	{
		return FileFunctions::isContainFolder($basePath, $filePath);
	}

	public function getFileEncoding(string $filePath): string
	{
		return FileFunctions::getFileEncoding($filePath);
	}

	public static function isFile(string $filePath, string $containDirectory = null): bool
	{
		return FileFunctions::isFile($filePath, $containDirectory);
	}

	public static function isEqualByLine(string $filePath, string $string = null): bool
	{
		return FileFunctions::isEqualByLine($filePath, $string);
	}

	public static function isExecutable($filePath)
	{
		return FileFunctions::isExecutable($filePath);
	}

	public static function isWritable(string $filePath): bool
	{
		return FileFunctions::isWritable($filePath);
	}

	public static function Delete(string $filePath): bool
	{
		return FileFunctions::Delete($filePath);
	}

	public static function getSize(string $filePath, bool $humanReadable = false, ?FileSizeUnit $type = NULL): int
	{
		return FileFunctions::getSize($filePath, $humanReadable, $type);
	}

	public static function Copy(string $filePath, string $destination): bool
	{
		return FileFunctions::copy($filePath, $destination);
	}

	public static function Merge(string $filePath, string $mergeFile): bool
	{
		return FileFunctions::Merge($filePath, $mergeFile);
	}

	public static function changeUmask($mask): int
	{
		return FileFunctions::changeUmask($mask);
	}

	public static function getHeaderType(string $filePath) :mixed
	{
		return FileFunctions::getHeaderType($filePath);
	}

	public static function Read(string $filePath, int $length = -1, string $mode = FileMode::READ_ONLY)
	{
		return FileFunctions::Read($filePath, $length, $mode);
	}

	public static function readAllContent(string $filePath, string $writeMode = FileMode::READ_ONLY)
	{
		return FileFunctions::readAllContent($filePath, $writeMode);
	}

	public static function Write(string $filePath, string $content = null, string $mode = 'w'): bool
	{
		return FileFunctions::Write($filePath, $content, $mode);
	}

	public static function appendContent(string $filePath, string $content = null, bool $stream = false, bool $overwrite = true): bool
	{
		return FileFunctions::appendContent($filePath, $content, $stream, $overwrite);
	}

	public static function getLastAccessDate($filePath)
	{
		return FileFunctions::getLastAccessDate($filePath);
	}

	public static function getCreatedDate($filePath)
	{
		return FileFunctions::getCreatedDate($filePath);
	}

	public static function getLastModifiedTime(string $filePath)
	{
		return FileFunctions::getLastModifiedTime($filePath);
	}

	public static function getType(string $filePath): string
	{
		return FileFunctions::getType($filePath);
	}

	public static function reverseContent(string $filePath): bool
	{
		return FileFunctions::reverseContent($filePath);
	}

	public static function getBasename(string $fileName, $extension = null): string
	{
		return FileFunctions::getBasename($fileName, $extension);
	}

	public static function getExtensionByFilePath(string $filePath): string
	{
		return FileFunctions::getExtensionByFilePath($filePath);
	}

	public static function getExtension(string $filePath): string
	{
		return FileFunctions::getExtension($filePath);
	}

	public static function getContent(string $filePath): string
	{
		return FileFunctions::getContent($filePath);
	}

	public static function Download(string $filePath, int $bufferSize = 0): bool
	{
		return FileFunctions::Download($filePath, $bufferSize);
	}

	public static function isCorrectInode($filePath): bool
	{
		return FileFunctions::isCorrectInode($filePath);
	}

	public static function getInode(string $filePath)
	{
		return FileFunctions::getInode($filePath);
	}

	public static function getInterpretedContent(string $filePath): string
	{
		return FileFunctions::getInterpretedContent($filePath);
	}

	public static function requireOnce(string $filePath): void
	{
		FileFunctions::requireOnce($filePath);
	}

	public static function Move(string $source, string $destination): bool
	{
		return FileFunctions::move($source, $destination);
	}
}
