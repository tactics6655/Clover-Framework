<?php

namespace Xanax\Implement;

use Xanax\Enumeration\FileMode;

interface FileHandlerInterface {

	public static function open($filePath, $mode, $use_include_path = false);
	
	public static function generateHashByContents($algorithm = 'md5', $filePath, $rawContents): string;

	public static function openProcess($processPath, $mode = 'w');

	public static function createTemporary();
	
	public static function reverseContent(string $filePath): bool;

	public static function isFile(string $filePath, string $containDirectory = null) :bool;
	
	public static function getTemporaryStream($mode = 'rb');

	public static function getMemoryStream($mode = 'rb');

	public static function getInputStream($mode = 'rb');

	public static function getOutputStream($mode = FileMode::WRITE_ONLY);

	public static function isEmpty(string $filePath) :bool;

	public static function isExists(string $filePath) :bool;

	public static function isContainFolder(string $basePath, string $filePath) :bool;

	public static function isCorrectInode($filePath) :bool;

	public static function isValidHandler($fileHandler);

	public static function getMIMEContentTypeFromMagicMIME($filePath);

	public static function getMIMEContentTypeFromAlaMimetypeExtension($filePath);

	public static function getMIMEContentType($filePath);

	public static function isReadable(string $filePath) :bool;

	public static function isLocked($filePath) :bool;

	public static function isRegularFile(string $filePath) :bool;

	public static function isSymbolicLink(string $filePath) :bool;

	public static function isUnknownFile(string $filePath) :bool;

	public static function isEqual($firstPath, $secondPath, $chunkSize = 500);

	public static function isEqualByLine(string $filePath, string $string = null) :bool;

	public static function isWritable(string $filePath) :bool;

	public static function getSize(string $filePath, bool $humanReadable) :int;

	public static function getInode(string $filePath);

	public static function getLastModifiedTime(string $filePath);

	public static function getCreatedDate($filePath);

	public static function getLastAccessDate($filePath);

	public static function getType(string $filePath) :string;

	public static function getExtension(string $filePath) :string;

	public static function getBasename(string $fileName, $extension = null) :string;
	
	public static function getExtensionByFilePath(string $filePath): string;

	public static function getContent(string $filePath) :string;

	public static function getHeaderType(string $filePath) :mixed;

	public static function getInterpretedContent(string $filePath) :string;

	public static function merge(string $filePath, string $mergeFile) :bool;

	public static function delete(string $filePath) :bool;

	public static function copy(string $filePath, string $destinationPath) :bool;

	public static function appendContent(string $filePath, string $content = null, bool $overwrite = true) :bool;

	public static function write(string $filePath, string $content = null, string $writeMode = FileMode::WRITE_ONLY) :bool;

	public static function read(string $filePath, int $length = -1, string $mode = FileMode::READ_ONLY);

	public static function readAllContent(string $filePath, string $writeMode = FileMode::READ_ONLY);

	public static function download(string $filePath, int $bufferSize = 0): bool;

	public static function requireOnce(string $filePath);

	public static function move(string $source, string $destination) :bool;

	public static function getOwner($filePath): int;

	public static function getPermissionCode($filePath): string;

	public static function getPermission($filePath): int;

	public static function getCharacter(string $fileHandler, int $length): string;

	public static function getLine(string $fileHandler, int $length): string;

	public static function parseINI(string $filePath);

	public static function createCache(string $filePath, string $destination);

	public static function getPointerLocation($fileHandler);

	public static function convertToNomalizePath($filePath): string;

	public static function clearStatusCache($filePath): void;

	public static function closeProcess($processResource);
}
