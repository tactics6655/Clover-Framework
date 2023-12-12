<?php

declare(strict_types=1);

namespace Xanax\Classes\File;

use Xanax\Enumeration\FileMode;
use Xanax\Enumeration\FileSizeUnit;

use Xanax\Classes\FileSystem\Handler as FileSystemHandler;
use Xanax\Classes\Protocol\PHP as PHPProtocol;

use Xanax\Exception\StupidIdeaException as StupidIdeaException;
use Xanax\Exception\FileHandler\FileIsNotExistsException as FileIsNotExistsException;
use Xanax\Exception\FileHandler\TargetIsNotFileException as TargetIsNotFileException;
use Xanax\Exception\FileHandler\InvalidFileHandler as InvalidFileHandler;
use Xanax\Exception\ResourceHandler\InvalidTypeException as InvalidTypeException;
use Xanax\Exception\Functions\FunctionIsNotExistsException as FunctionIsNotExistsException;

use Xanax\Validation\FileValidation as FileValidation;

use Xanax\Message\FileHandler\FileHandlerMessage as FileHandlerMessage;
use Xanax\Message\Functions\FunctionMessage as FunctionMessage;

use function clearstatcache;
use function fileatime;
use function filetype;
use function getType;
use function sha1_file;
use function parse_ini_file;
use function strrchr;
use function ob_flush;
use function rename;

class Functions
{

	public static function getClassName($filePath)
	{
		$declared = get_declared_classes();
		
		require_once $filePath;

		return array_diff(get_declared_classes(), $declared);
	}

    public static function isCorrectName(string $fileName)
    {
        if (strlen($fileName) > \PHP_MAXPATHLEN)
        {
            return false;
        }
        
        return true;
    }

	public static function getCharacter($stream): string
	{
		if (!self::isValidHandler($stream)) 
		{
			throw new InvalidFileHandler(FileHandlerMessage::getInvalidFileHandler());
		}

		return fgetc($stream);
	}

	public static function getLine($stream, int $length): string
	{
		if (!self::isValidHandler($stream)) 
		{
			throw new InvalidFileHandler(FileHandlerMessage::getInvalidFileHandler());
		}

		return fgets($stream, $length);
	}

	/**
	 * Read the file contents.
	 *
	 * @param string $filePath
	 * @param int    $length
	 * @param int    $mode
	 *
	 * @return bool
	 */
	public static function readAllContent(string $filePath, string $writeMode = FileMode::READ_ONLY)
	{
		$filePath = self::convertToNomalizePath($filePath);

		return self::Read($filePath, -1);
	}

	/**
	 * Create a file.
	 *
	 * @param string $filePath
	 * @param string $content
	 * @param string $writeMode
	 *
	 * @return bool
	 */
	public static function Write(string $filePath, string $content = null, string $mode = 'w'): bool
	{
		$filePath = self::convertToNomalizePath($filePath);

		$fileObject = new FileObject($filePath, true, $mode);
		$fileObject->startHandle();

		if (!$fileObject->successToStartHandle()) 
		{
			return false;
		}

		$fileObject->writeContent($content);

		if (!$fileObject->successToWriteContent()) 
		{
			return false;
		}

		$fileObject->closeFileHandle();

		return true;
	}

	/**
	 * Bring the created time.
	 *
	 * @param string $filePath
	 *
	 * @return string
	 */
	public static function getCreatedDate($filePath) :bool|int
	{
		$filePath = self::convertToNomalizePath($filePath);

		if (!self::isExists($filePath)) 
		{
			throw new FileIsNotExistsException(FileHandlerMessage::getFileIsNotExistsMessage());
		}

		if (!self::isFile($filePath)) 
		{
			return false;
		}

		self::clearStatusCache($filePath);
		$return = filectime($filePath);

		return $return;
	}

	/**
	 * Bring the last modified time.
	 *
	 * @param string $fileName
	 *
	 * @return string
	 */
	public static function getLastModifiedTime(string $fileName) :bool|int
	{
		$fileName = self::convertToNomalizePath($fileName);

		if (!self::isExists($fileName)) 
		{
			throw new FileIsNotExistsException(FileHandlerMessage::getFileIsNotExistsMessage());
		}

		if (!self::isFile($fileName)) 
		{
			throw new TargetIsNotFileException(FileHandlerMessage::getFileIsNotExistsMessage());
		}

		self::clearStatusCache($fileName);
		$return = filemtime($fileName);

		return $return;
	}

	/**
	 * Write the contents of the file backwards.
	 *
	 * @param string $filePath
	 *
	 * @return bool
	 */
	public static function reverseContent(string $filePath): bool
	{
		$filePath = self::convertToNomalizePath($filePath);

		$fileLines     = file($filePath);
		$invertedLines = strrev(array_shift($fileLines));

		return self::Write($filePath, $invertedLines, 'w');
	}

	public static function getExtensionByFilePath(string $filePath): string
	{
		$return = null;

		if (function_exists("pathinfo")) 
		{
			$return = pathinfo($filePath, PATHINFO_EXTENSION);
		}

		return $return;
	}

	/**
	 * Check if the file exists.
	 *
	 * @param string $filePath
	 *
	 * @return bool
	 */
	public static function isExists(string $filePath): bool
	{
		$filePath = self::convertToNomalizePath($filePath);

		$return = file_exists($filePath);

		return $return;
	}

	/**
	 * Gets whether the file is locked.
	 *
	 * @param string $filePath
	 *
	 * @return bool
	 */
	public static function isLocked($filePath): bool
	{
		$filePath = self::convertToNomalizePath($filePath);

		if (!self::isExists($filePath)) 
		{
			return false;
		}

		if (!self::isFile($filePath)) 
		{
			throw new TargetIsNotFileException(FileHandlerMessage::getFileIsNotExistsMessage());
		}

		if (!self::isValidHandler($filePath) && !self::isFile($filePath)) 
		{
			return false;
		}

		if (!self::isValidHandler($filePath)) 
		{
			$filePath = self::Open($filePath, FileMode::READ_OVERWRITE);
		}

		if (!flock($filePath, LOCK_EX)) 
		{
			return true;
		}

		return false;
	}

	/**
	 * Create a temporary file
	 *
	 * @return resource
	 */
	public static function createTemporary()
	{
		return tmpfile();
	}

	/**
	 * Create a unique temporary file
	 *
	 * @return mixed
	 */
	public static function createUniqueTemporary($directory, $prefix)
	{
		return tempnam($directory, $prefix);
	}

	/**
	 * Modify access and modification time of file
	 *
	 * @return resource
	 */
	public static function setAccessAndModificatinTime($filePath, $time, $atime): void
	{
		touch($filePath, $time, $atime);
	}

	/**
	 * Unlock the file.
	 *
	 * @param string $filePath
	 *
	 * @return bool
	 */
	public static function Unlock($fileHandler): void
	{
		if (!self::isValidHandler($fileHandler)) 
		{
			throw new InvalidFileHandler(FileHandlerMessage::getInvalidFileHandler());
		}

		flock($fileHandler, LOCK_UN); // Unlock file handler
	}

	/**
	 * Change mode of file
	 *
	 * @param string $filePath
	 * @param int    $mode
	 *
	 * @return bool
	 */
	public static function setPermission(string $filePath, int $mode): bool
	{
		if (!self::isFile($filePath)) 
		{
			throw new TargetIsNotFileException(FileHandlerMessage::getFileIsNotExistsMessage());
		}

		return chmod($filePath, $mode) ? true : false;
	}

	/**
	 * Change group of file
	 *
	 * @param string $filePath
	 * @param int    $mode
	 *
	 * @return bool
	 */
	public static function changeGroup(string $filePath, string $group): bool
	{
		if (!self::isFile($filePath)) 
		{
			throw new TargetIsNotFileException(FileHandlerMessage::getFileIsNotExistsMessage());
		}

		return chgrp($filePath, $group);
	}

	/**
	 * Lock the file.
	 *
	 * @param string $filePath
	 *
	 * @return bool
	 */
	public static function Lock($fileHandler, $mode = FileMode::READ_ONLY)
	{
		if (!self::isValidHandler($fileHandler)) 
		{
			throw new InvalidFileHandler(FileHandlerMessage::getInvalidFileHandler());
		}

		$mode = strtolower($mode);

		switch ($mode) 
		{
			case FileMode::READ_ONLY:
				flock($fileHandler, LOCK_SH); // Lock of read mode
				break;
			case FileMode::WRITE_ONLY:
				flock($fileHandler, LOCK_EX); // Lock of write mode
				break;
		}
	}

	/**
	 * Check if the file empty.
	 *
	 * @param string $filePath
	 *
	 * @return bool
	 */
	public static function isEmpty(string $filePath): bool
	{
		$filePath = self::convertToNomalizePath($filePath);

		if (!self::isFile($filePath)) 
		{
			throw new TargetIsNotFileException(FileHandlerMessage::getFileIsNotExistsMessage());
		}

		$return = self::getSize($filePath) !== 0;

		return $return;
	}

	/**
	 * Check if the file type is unknown.
	 *
	 * @param string $filePath
	 *
	 * @return bool
	 */
	public static function isUnknownFile(string $filePath): bool
	{
		$filePath = self::convertToNomalizePath($filePath);

		if (self::getType($filePath) === 'unknown') 
		{
			return true;
		}

		return false;
	}
    
	/**
	 * Get a basename of file
	 *
	 * @param string $fileName
	 * @param string $extension
	 *
	 * @return string
	 */
	public static function getBasename(string $fileName, $extension = null): string
	{
		return basename($fileName, $extension) . PHP_EOL;
	}

	/**
	 * Require once a php file
	 *
	 * @param string $filePath
	 *
	 * @return void
	 */
	public static function requireOnce(string $filePath): void
	{
		$filePath = self::convertToNomalizePath($filePath);

		if (!self::isExists($filePath)) 
		{
			throw new FileIsNotExistsException(FileHandlerMessage::getFileIsNotExistsMessage());
		}

		if (!self::isFile($filePath)) 
		{
			throw new TargetIsNotFileException(FileHandlerMessage::getFileIsNotExistsMessage());
		}

		require_once $filePath;
	}

	/**
	 * Bring the last access time.
	 *
	 * @param string $filePath
	 *
	 * @return mixed
	 */
	public static function getLastAccessDate($filePath)
	{
		$filePath = self::convertToNomalizePath($filePath);

		if (!self::isExists($filePath)) 
		{
			throw new FileIsNotExistsException(FileHandlerMessage::getFileIsNotExistsMessage());
		}

		if (!self::isFile($filePath)) 
		{
			return false;
		}

		self::clearStatusCache($filePath);
		$return = fileatime($filePath);

		return $return;
	}

	/**
	 * Append the contents to the file.
	 *
	 * @param string $filePath
	 * @param string $content
	 *
	 * @return bool
	 */
	public static function appendContent(string $filePath, string $content = null, bool $stream = false, bool $overwrite = true): bool
	{
		$filePath = self::convertToNomalizePath($filePath);

		if (!$overwrite && self::isFile($filePath)) 
		{
			throw new TargetIsNotFileException(FileHandlerMessage::getFileIsNotExistsMessage());
		}

		if ($stream === true) 
		{
			file_put_contents($filePath, $content, FILE_APPEND | LOCK_EX);
		} 
		else 
		{
			self::Write($filePath, $content, 'a');
		}

		return true;
	}
	/**
	 * Delete the state of the file.
	 *
	 * @param string $filePath
	 *
	 * @return bool
	 */
	public static function clearStatusCache($filePath): void
	{
		clearstatcache(true, $filePath);
	}

	/**
	 * Get the file type.
	 *
	 * @param string $filePath
	 *
	 * @return string
	 */
	public static function getType(string $filePath): string
	{
		$filePath = self::convertToNomalizePath($filePath);

		if (!self::isExists($filePath)) 
		{
			throw new FileIsNotExistsException(FileHandlerMessage::getFileIsNotExistsMessage());
		}

		if (!self::isFile($filePath)) 
		{
			throw new TargetIsNotFileException(FileHandlerMessage::getFileIsNotExistsMessage());
		}

		if (FileValidation::isPharProtocol($filePath)) 
		{
			throw new StupidIdeaException(FileHandlerMessage::getDoNotUsePharProtocolMessage());
		}

		self::clearStatusCache($filePath);

		$return = filetype($filePath);

		return $return;
	}

	/**
	 * Make sure the file location exists under a specific folder.
	 *
	 * @param string $basePath
	 * @param string $filePath
	 *
	 * @return bool
	 */
	public static function isContainFolder(string $basePath, string $filePath): bool
	{
		$filePath = self::convertToNomalizePath($filePath);

		$realBasePath = realpath($basePath);
		$realFilePath = realpath(dirname($filePath));

		if ($realFilePath === false || strncmp($realFilePath, $realBasePath, strlen($realBasePath)) !== 0) 
		{
			return false;
		}

		return true;
	}

	/**
	 * Return the encoding of file
	 *
	 * @param string $filePath
	 *
	 * @return string
	 */
	public static function getFileEncoding(string $filePath): string
	{
		$executeResult = array();
		exec('file -i ' . $filePath, $executeResult);

		if (isset($executeResult[0])) 
		{
			$charset = explode('charset=', $executeResult[0]);
			return isset($charset[1]) ? $charset[1] : null;
		}

		return '';
	}

	/**
	 * Check that inode of file is valid
	 *
	 * @param string $filePath
	 *
	 * @return boolean
	 */
	public static function isCorrectInode($filePath): bool
	{
		$filePath = self::convertToNomalizePath($filePath);

		if (!self::isExists($filePath)) 
		{
			throw new FileIsNotExistsException(FileHandlerMessage::getFileIsNotExistsMessage());
		}

		if (!self::isFile($filePath)) 
		{
			throw new TargetIsNotFileException(FileHandlerMessage::getFileIsNotExistsMessage());
		}

		if (FileSystemHandler::getCurrentInode() === self::getInode($filePath)) 
		{
			return true;
		}

		return false;
	}

	/**
	 * Get a inode of file
	 *
	 * @param string $filePath
	 *
	 * @return mixed
	 */
	public static function getInode(string $filePath)
	{
		$filePath = self::convertToNomalizePath($filePath);

		if (!self::isExists($filePath)) 
		{
			throw new FileIsNotExistsException(FileHandlerMessage::getFileIsNotExistsMessage());
		}

		if (!self::isFile($filePath)) 
		{
			throw new TargetIsNotFileException(FileHandlerMessage::getFileIsNotExistsMessage());
		}

		return FileSystemHandler::getInodeNumber($filePath);
	}

	public static function closeProcess($processResource)
	{
		if (getType($processResource) !== 'resource') 
		{
			throw new InvalidTypeException("");
		}

		$return = pclose($processResource);

		return $return;
	}

	public static function openProcess($processPath, $mode = FileMode::WRITE_ONLY)
	{
		$handle = popen($processPath, $mode);

		return $handle;
	}

	public static function getMIMEContentType($filePath)
	{
		$result = null;

		if (function_exists("mime_content_type")) 
		{
			$result = self::getMIMEContentTypeFromMagicMIME($filePath);
		} 
		else if (function_exists("finfo_open") && function_exists("finfo_file")) 
		{
			$result = self::getMIMEContentTypeFromAlaMimetypeExtension($filePath);
		}

		return $result;
	}

	/**
	 * Get content-type of file to use Ala Mime-type extension
	 *
	 * @param string $filePath
	 *
	 * @return string
	 */
	public static function getMIMEContentTypeFromAlaMimetypeExtension($filePath)
	{
		if (!(function_exists("finfo_open") || function_exists("finfo_file"))) 
		{
			throw new FunctionIsNotExistsException(FunctionMessage::getFunctionIsNotFileMessage());
		}

		$filePath = self::convertToNomalizePath($filePath);

		$fileinfoResource = finfo_open(FILEINFO_MIME_TYPE);

		$result = finfo_file($fileinfoResource, $filePath);

		return $result;
	}

	/**
	 * Get content-type of file from magic.mime
	 *
	 * @param string $filePath
	 *
	 * @return string
	 */
	public static function getMIMEContentTypeFromMagicMIME($filePath)
	{
		if (!function_exists("mime_content_type")) 
		{
			throw new FunctionIsNotExistsException(FunctionMessage::getFunctionIsNotFileMessage());
		}

		$filePath = self::convertToNomalizePath($filePath);

		if (!self::isExists($filePath)) 
		{
			throw new FileIsNotExistsException(FileHandlerMessage::getFileIsNotExistsMessage());
		}

		if (!self::isFile($filePath)) 
		{
			throw new TargetIsNotFileException(FileHandlerMessage::getFileIsNotExistsMessage());
		}

		return mime_content_type($filePath);
	}

	/**
	 * Interpret the INI file.
	 *
	 * @param string $filePath
	 *
	 * @return bool
	 */
	public static function parseINI(string $filePath)
	{
		$filePath = self::convertToNomalizePath($filePath);

		if (!self::isExists($filePath)) 
		{
			throw new FileIsNotExistsException(FileHandlerMessage::getFileIsNotExistsMessage());
		}

		if (!self::isFile($filePath)) 
		{
			throw new TargetIsNotFileException(FileHandlerMessage::getFileIsNotExistsMessage());
		}

		return parse_ini_file($filePath);
	}

	/**
	 * Gets the current file pointer position.
	 *
	 * @param string $fileHandler
	 *
	 * @return bool
	 */
	public static function getPointerLocation($fileHandler)
	{
		if (!self::isValidHandler($fileHandler)) 
		{
			throw new InvalidFileHandler(FileHandlerMessage::getInvalidFileHandler());
		}

		return ftell($fileHandler);
	}

	/**
	 * Delete the last directory separator.
	 *
	 * @param string $filePath
	 *
	 * @return bool
	 */
	public static function convertToNomalizePath($filePath): string
	{
		return rtrim($filePath, DIRECTORY_SEPARATOR); // Remove last Directory separator
	}

	/**
	 * Make sure the file handler is of type resource.
	 *
	 * @param string $fileHandler
	 *
	 * @return bool
	 */
	public static function isValidHandler($fileHandler)
	{
		if (getType($fileHandler) !== 'resource') 
		{
			return false;
		}

		if (get_resource_type($fileHandler) !== 'stream') 
		{
			return false;
		}

		return true;
	}

	/**
	 * Create a cache file
	 *
	 * @param string $filePath
	 * @param string $destination
	 *
	 * @return bool
	 */
	public static function createCache(string $filePath, string $destination)
	{
		$filePath    = self::convertToNomalizePath($filePath);
		$destination = self::convertToNomalizePath($destination);

		if (!self::isExists($filePath)) 
		{
			throw new FileIsNotExistsException(FileHandlerMessage::getFileIsNotExistsMessage());
		}

		if (!self::isFile($filePath)) 
		{
			return false;
		}

		$cached = self::Open($filePath, FileMode::WRITE_ONLY);
		fwrite($destination, ob_get_contents());
		fclose($destination);
		ob_end_flush();

		return true;
	}

	/**
	 * Gets whether the file can be read.
	 *
	 * @param string $filePath
	 *
	 * @return bool
	 */
	public static function isReadable(string $filePath): bool
	{
		$filePath = self::convertToNomalizePath($filePath);

		if (!self::isExists($filePath)) 
		{
			throw new FileIsNotExistsException(FileHandlerMessage::getFileIsNotExistsMessage());
		}

		if (!self::isFile($filePath)) 
		{
			throw new TargetIsNotFileException(FileHandlerMessage::getFileIsNotExistsMessage());
		}

		$return = is_readable($filePath);

		return $return;
	}

	/**
	 * Check that the file is correct.
	 *
	 * @param string $filePath
	 * @param string $containDirectory
	 *
	 * @return bool
	 */
	public static function isFile(string $filePath, string $containDirectory = null): bool
	{
		$filePath = self::convertToNomalizePath($filePath);

		if (!FileValidation::isReadable($filePath)) 
		{
			return false;
		}

		if (FileValidation::hasSubfolderSyntax($filePath)) 
		{
			if ($filePath === null) 
			{
				throw new StupidIdeaException(FileHandlerMessage::getDoNotUseSubDirectorySyntaxMessage());
			} 
			else if (!self::isContainFolder($containDirectory, $filePath)) 
			{
				return false;
			}
		}

		if (FileValidation::isPharProtocol($filePath)) 
		{
			throw new StupidIdeaException(FileHandlerMessage::getDoNotUsePharProtocolMessage());
		}

		$return = is_file($filePath);

		return $return;
	}

	public static function getSizeUnit(FileSizeUnit $type)
	{
		switch ($type)
		{
			case FileSizeUnit::LONG:
				return ['B', 'Kilo B', 'Mega B', 'Giga B', 'Tera B', 'Peta B', 'Exa B', 'Zetta B', 'Yotta B'];
			default:
			case FileSizeUnit::SHORT:
				return ['B', 'kB', 'MB', 'GB', 'TB', 'PB', 'EB', 'ZB', 'YB'];
		}
	}

	/**
	 * Check the size of the file.
	 *
	 * @param string $filePath
	 *
	 * @return int
	 */
	public static function getSize(string $filePath, bool $humanReadable = false, FileSizeUnit $type = FileSizeUnit::LONG): int
	{
		$filePath = self::convertToNomalizePath($filePath);

		if (!self::isExists($filePath)) 
		{
			throw new FileIsNotExistsException(FileHandlerMessage::getFileIsNotExistsMessage());
		}

		if (!self::isFile($filePath)) 
		{
			throw new TargetIsNotFileException(FileHandlerMessage::getFileIsNotExistsMessage());
		}

		self::clearStatusCache($filePath);

		if ($humanReadable) 
		{
			if (file_exists($filePath)) 
			{
				$bytes = (int) filesize($filePath);
			} 
			else 
			{
				$bytes = (int)is_int($filePath) ? $filePath : -1;
			}

			if ($bytes > 0) 
			{
				$sizes            = self::getSizeUnit($type);
				$measure          = strlen((string) ($bytes >> 10));
				$factor           = $bytes < (1024 ** 6) ? ($measure > 1 ? floor((($measure - 1) / 3) + 1) : 1) : floor((strlen($bytes) - 1) / 3);
				$capacity         = $bytes / pow(1024, $factor);
				$multiBytesPrefix = ($capacity === intval($capacity) ?: 'ytes');
				$bytes            = sprintf('%s%s%s', $capacity, $sizes[$factor], $multiBytesPrefix);
			}

			return $bytes;
		}

		$return = filesize($filePath);

		return $return >= 0 ? $return : -1;
	}

	public static function getOutputStream($mode = FileMode::WRITE_ONLY)
	{
		$phpProtocol = new PHPProtocol();

		$handler = self::Open($phpProtocol->getOutput(), $mode);

		return $handler;
	}

	public static function getInputStream($mode = 'rb')
	{
		$phpProtocol = new PHPProtocol();
		$handler = self::Open($phpProtocol->getInput(), $mode);

		return $handler;
	}

	public static function getMemoryStream($mode = 'rb')
	{
		$phpProtocol = new PHPProtocol();

		$handler = self::Open($phpProtocol->getMemory(), $mode);

		return $handler;
	}

	public static function getTemporaryStream($mode = 'rb')
	{
		$phpProtocol = new PHPProtocol();

		$handler = self::Open($phpProtocol->getTemporary(), $mode);

		return $handler;
	}

	public static function getFilterStream($mode = 'rb')
	{
		$phpProtocol = new PHPProtocol();

		$handler = self::Open($phpProtocol->getFilter(), $mode);

		return $handler;
	}

	public static function getStandardInputStream($mode = FileMode::READ_ONLY)
	{
		$phpProtocol = new PHPProtocol();

		$handler = self::Open($phpProtocol->getStandardInput(), $mode);

		return $handler;
	}

	public static function getStandardOutputStream($mode = FileMode::READ_ONLY)
	{
		$phpProtocol = new PHPProtocol();

		$handler = self::Open($phpProtocol->getStandardOutput(), $mode);

		return $handler;
	}

	/**
	 * Gets the interpreted file content.
	 *
	 * @param string $filePath
	 *
	 * @return string
	 */
	public static function getInterpretedContent(string $filePath, $data): string
	{
		$filePath = self::convertToNomalizePath($filePath);

		if (!self::isExists($filePath)) 
		{
			throw new FileIsNotExistsException(FileHandlerMessage::getFileIsNotExistsMessage());
		}

		if (!self::isFile($filePath)) 
		{
			throw new TargetIsNotFileException(FileHandlerMessage::getFileIsNotExistsMessage());
		}

		ob_start();

		extract($data);

		if (isset($filePath)) 
		{
			if (file_exists($filePath)) 
			{
				@include $filePath;
			} 
			else
			{
				throw new FileIsNotExistsException(FileHandlerMessage::getFileIsNotExistsMessage());
			}
		}

		$return = ob_get_contents();

		ob_end_clean();
		
		return $return;
	}


	/**
	 * Move the file to a specific location.
	 *
	 * @param string $filePath
	 * @param string $destination
	 *
	 * @return string
	 */
	public static function Move(string $source, string $destination): bool
	{
		$source      = self::convertToNomalizePath($source);
		$destination = self::convertToNomalizePath($destination);

		if (!self::isExists($source)) 
		{
			throw new FileIsNotExistsException(FileHandlerMessage::getFileIsNotExistsMessage());
		}

		if (!self::isFile($destination)) 
		{
			throw new TargetIsNotFileException(FileHandlerMessage::getFileIsNotExistsMessage());
		}

		$return = rename($source, $destination);

		return $return;
	}

	/**
	 * Read the file.
	 *
	 * @param string $filePath
	 * @param int    $length
	 * @param int    $mode
	 *
	 * @return mixed
	 */
	public static function Read(string $filePath, int $length = -1, string $mode = FileMode::READ_ONLY)
	{
		$filePath = self::convertToNomalizePath($filePath);

		$fileObject = new FileObject($filePath, false, $mode);
		if (!$fileObject->isEnoughFreeSpace()) 
		{
			self::$lastError = 'Disk space is not enough';

			return false;
		}

		$fileObject->startHandle();

		if (!$fileObject->successToStartHandle()) 
		{
			return false;
		}

		if (!$fileObject->hasReadedContent()) 
		{
			return false;
		}

		if ($length === -1) 
		{
			$fileObject->readAllContent();
		} 
		else 
		{
			$fileObject->readContent($length);
		}

		$content = $fileObject->getReadedContent();

		$fileObject->closeFileHandle();

		return $content;
	}

	/**
	 * Gets the symbolic link
	 *
	 * @param string $symbolicLink
	 *
	 * @return mixed
	 */
	public static function getSymbolicLink(string $symbolicLink)
	{
		if (!self::isSymbolicLink($symbolicLink)) 
		{
			return false;
		}

		$return = readlink($symbolicLink);

		return $return;
	}

	public static function isSymbolicLink(string $filePath): bool
	{
		$filePath = self::convertToNomalizePath($filePath);

		if (is_link($filePath) && self::getType($filePath) === 'link') 
		{
			return true;
		}

		return false;
	}

	/**
	 * Check if the file type is regular.
	 *
	 * @param string $filePath
	 *
	 * @return bool
	 */
	public static function isRegularFile(string $filePath): bool
	{
		$filePath = self::convertToNomalizePath($filePath);

		if (self::getType($filePath) === 'file') 
		{
			return true;
		}

		return false;
	}

	/**
	 * Checks for a match on a line in the file.
	 *
	 * @param string $filePath
	 * @param string $string
	 *
	 * @return bool
	 */
	public static function isEqualByLine(string $filePath, string $string = null): bool
	{
		$filePath = self::convertToNomalizePath($filePath);

		$fileObject = new FileObject($filePath, false, FileMode::READ_ONLY);
		$fileObject->startHandle();
		$bool = $fileObject->isEqualByLine($string);
		$fileObject->closeFileHandle();

		return $bool;
	}

	/**
	 * Make sure the file is executable on your system.
	 *
	 * @param string $filePath
	 *
	 * @return bool
	 */
	public static function isExecutable($filePath)
	{
		$filePath = self::convertToNomalizePath($filePath);

		if (!self::isExists($filePath)) 
		{
			throw new FileIsNotExistsException(FileHandlerMessage::getFileIsNotExistsMessage());
		}

		if (!self::isFile($filePath)) 
		{
			return false;
		}

		self::clearStatusCache($filePath);
		$return = is_executable($filePath);

		return $return;
	}

	/**
	 * Gets whether the file can be written to.
	 *
	 * @param string $filePath
	 *
	 * @return bool
	 */
	public static function isWritable(string $filePath): bool
	{
		$filePath = self::convertToNomalizePath($filePath);

		if (!self::isExists($filePath)) 
		{
			return true;
		}

		if (!self::isFile($filePath)) 
		{
			return true;
		}

		self::clearStatusCache($filePath);
		$return = is_writable($filePath);

		return $return;
	}

	/**
	 * Delete the file.
	 *
	 * @param string $filePath
	 *
	 * @return bool
	 */
	public static function Delete(string $filePath): bool
	{
		$filePath = self::convertToNomalizePath($filePath);

		if (!self::isExists($filePath)) 
		{
			throw new FileIsNotExistsException(FileHandlerMessage::getFileIsNotExistsMessage());
		}

		if (!self::isFile($filePath)) 
		{
			throw new TargetIsNotFileException(FileHandlerMessage::getFileIsNotExistsMessage());
		}

		return unlink($filePath);
	}

	/**
	 * Copy the file.
	 *
	 * @param string $filePath
	 * @param string $destination
	 *
	 * @return bool
	 */
	public static function Copy(string $filePath, string $destination): bool
	{
		$filePath = self::convertToNomalizePath($filePath);

		if (!self::isExists($filePath)) 
		{
			throw new FileIsNotExistsException(FileHandlerMessage::getFileIsNotExistsMessage());
		}

		if (!self::isFile($filePath)) 
		{
			throw new FileIsNotExistsException(FileHandlerMessage::getFileIsNotExistsMessage());
		}

		$return = copy($filePath, $destination);

		return $return;
	}

	/**
	 * Combine the two files.
	 *
	 * @param string $filePath
	 * @param string $mergeFile
	 *
	 * @return bool
	 */
	public static function Merge(string $filePath, string $mergeFile): bool
	{
		$filePath = self::convertToNomalizePath($filePath);

		$fileObject = new FileObject($filePath, false, 'a');
		$fileObject->startHandle();

		$fileObject->appendContent($mergeFile);

		$fileObject->closeFileHandle();

		return true;
	}

	public static function changeUmask($mask): int
	{
		return umask($mask);
	}

	/**
	 * Get a header type of file by big endian data
	 *
	 * @param string $filePath
	 *
	 * @return mixed
	 */
	public static function getHeaderType(string $filePath) :string|bool
	{
		$size = filesize($filePath);
		$size = $size > 100 ? 100 : $size;

		if ($size <= 4) 
		{
			return false;
		}

		$header = self::Read($filePath, $size);
		if ($header) 
		{
			$bigEndianUnpack = unpack('N', $header);
		} 
		else 
		{
			return false;
		}

		/* ISO 8859-1 */
		$fileDescription = array_shift($bigEndianUnpack);

		$mp3FileHeader = [
			/* ftyp3gp4isom3gp4 */
			'0x18',

			/* !DO (p Hq) */
			'0x3C21444F',

			'0x4D617220',

			/* ID3 (TSSE) */
			'0x2F271E8',
			/* ID3 (GEOB, TYER, TALB, TSSE, TXXX, TPE1, TIT2, TCON, PRIV, TRCK) */
			'0x49443303',
			/* ID3 (TALB, TIT2, TSS, TT2, FTT2, TPE1) */
			'0x49443302',

			'0x2E4BEAA',

			'0xFFFBE444',
			'0xFFFBE044',
			/* d (dInfo) */
			'0xFFFBD064',
			/* D (DInfo) */
			'0xFFFBD044',

			'0xFFFBC060',
			'0xFFFBB064',
			/* ` */
			'0xFFFBB060',
			/*  */
			'0xFFFBB004',
			'0xFFFBB000',
			'0xFFFBA064',
			/* ` */
			'0xFFFBA060',
			/* D */
			'0xFFFBA044',
			'0xFFFBA040',

			'0xFFFB9464',
			'0xFFFB9444',
			'0xFFFB9264',

			'0xFFFB90C4',
			'0xFFFB90C0',
			/* d (dInfo) */
			'0xFFFB9064',
			/* ` */
			'0xFFFB9060',
			/* D (DXing) */
			'0xFFFB9044',
			/* @ */
			'0xFFFB9040',
			'0xFFFB9004',
			'0xFFFB9000',
			/* p */
			'0xFFFB70C4',
			/* ` */
			'0xFFFB60C4',
			'0xFFFB50C4',

			'0xFFFB30C4',
			'0xFFFA9400',

			'0xFFF3C8C4',

			'0xFFF3A064',

			'0xFFF380C4',
			'0xFFF37454',

			'0xFFE388C4',

			/* ÿû */
			'0xC3BFC3BB',

			'0x11200100',

			'0xD0AF339',
			'0xD0AE88A',
			'0xD0A4944',
			'0xD0A6085',
			/* � (dInfo)*/
			'0xAFFFB80',
		];

		$exeFileHeader = [
			'0x4D5A5700',
			'0x4D5A5000',
			'0x4D5AC401',
			'0x4D5A8800',
			/* MZ */
			'0x4D5A9000'
		];

		$jpgFileHeader = [
			'0xFFD8FFEE',
			/* JPG, JFIF */
			'0xFFD8FFE0',
			/* Exif JPG */
			'0xFFD8FFE1',
			'0xFFD8FFDB',
			/* MZ */
			'0xFFD8FFE2',
			'0xFFD8FFEC'
		];

		$bmpFileHeader = [
			'0x424D3653',
			'0x424D569F',
			'0x424D56FE',
			'0x424D3616',
		];

		$xp3FileHeader = [
			'0x5850330D',
			'0x424D0638',
			'0x424D3404',
			'0x424D365C',
		];

		$swfFileHeader = [
			'0x46575306',
			'0x46575309',
			'0x43575306'
		];

		$oggFileHeader = [
			'4294676676'
		];

		if (in_array($fileDescription, $exeFileHeader)) 
		{
			return 'EXE';
		} 
		else if (in_array($fileDescription, $oggFileHeader)) 
		{
			return 'OGG';
		} 
		else if (in_array($fileDescription, $mp3FileHeader)) 
		{
			return 'MP3';
		} 
		else if ($fileDescription === 0x4D546864 /* MThd */ || $fileDescription === 0xB7075) 
		{
			return 'MID';
		} 
		else if (in_array($fileDescription, $jpgFileHeader)) 
		{
			return 'JPG';
		} 
		else if ($fileDescription === 0x2E0000EA) 
		{
			return 'GBA';
		} 
		else if ($fileDescription === 0x4F676753) 
		{
			return 'OGG/OGA/OGV';
		} 
		else if ($fileDescription === 0x38425053) 
		{
			return 'PSD';
		} 
		else if ($fileDescription === 0x4E45531A /* NES */) 
		{
			return 'NES';
		} 
		else if ($fileDescription === 0x494E4458) 
		{
			return 'IDX';
		} 
		else if ($fileDescription === 0x4C5A4950) 
		{
			return 'LZ';
		} 
		else if ($fileDescription === 0x44303031) 
		{
			return 'ISO';
		}
		else if ($fileDescription === 0x79703367) 
		{
			return '3GP/3G2';
		} 
		else if ($fileDescription === 0x54444546) 
		{
			return 'TDEF'
			;
		} 
		else if ($fileDescription === 0x664C6143) 
		{
			return 'FLAG';
		} 
		else if ($fileDescription === 0xC3130) 
		{
			return 'ZIP';
		} 
		else if ($fileDescription === 0x504B0304 /* PK, KPZIP/PPTX */) 
		{
			return 'ZIP/PPTX';
		} 
		else if ($fileDescription === 0x46383761 /* GIF8 (GIF87a) */ || $fileDescription === 0x47494638 /* GIF8 (GIF89a) */) 
		{
			return 'GIF';
		} 
		else if ($fileDescription === 0x4D5A6C00) 
		{
			return 'DLL';
		} 
		else if ($fileDescription === 0x4C000000 /* LLNK */) 
		{
			return 'LNK';
		} 
		else if ($fileDescription === 0x5B7B3030 || $fileDescription === 0x5B444546) 
		{
			return 'URL';
		} 
		else if ($fileDescription === 0x89504E47 /* PNG */) 
		{
			return 'PNG';
		} 
		else if ($fileDescription === 0x454E4947) 
		{
			return 'MUS';
		} 
		else if ($fileDescription === 0x1C /* M4A */) 
		{
			return 'M4A'; //ftypM4A
		} 
		else if ($fileDescription === 0x2D2D2D2D) 
		{
			return 'CAP';
		} 
		else if ($fileDescription === 0x4D534654) 
		{
			return 'TLB';
		} 
		else if ($fileDescription === 0xA050101) 
		{
			return 'PCX';
		} 
		else if ($fileDescription === 0x64343A69) 
		{
			return 'TORRENTDATA';
		} 
		else if ($fileDescription === 0x6431303A) 
		{
			return 'DAT';
		} 
		else if ($fileDescription === 0x4F676753) 
		{
			return 'OGG';
		} 
		else if ($fileDescription === 0x50445431) 
		{
			return 'PDF';
		} 
		else if ($fileDescription === 0x100) 
		{
			return 'ICODATA';
		} 
		else if ($fileDescription === 0xFFFE2300) 
		{
			return 'AIMPPL4';
		} 
		else if ($fileDescription === 0x49545346) 
		{
			return 'CHM';
		} 
		else if ($fileDescription === 0xD0CF11E0) 
		{
			return 'MSI';
		} 
		else if ($fileDescription === 0x52494646 /* RIFF */) 
		{
			return 'AVI/WAV/CPR';
		} 
		else if ($fileDescription === 0x50494646) 
		{
			return 'WAV';
		} 
		else if (in_array($fileDescription, $bmpFileHeader)) 
		{
			return 'BMP';
		} 
		else if (in_array($fileDescription, $xp3FileHeader)) 
		{
			return 'XP3';
		} 
		else if ($fileDescription === 0x3026B275) 
		{
			return 'WMV';
		} 
		else if (in_array($fileDescription, $swfFileHeader)) 
		{
			return 'SWF';
		} 
		else if ($fileDescription === 0x1A45DFA3) 
		{
			return 'WEBM';
		} 
		else if ($fileDescription === 20 || $fileDescription === 32 /* MP4 (ftypisom isomiso2avc1mp41) */) 
		{
			return 'MP4';
		} 
		else if ($fileDescription === 0x25504446 /* PDF*/) 
		{
			return 'PDF';
		} 
		else if ($fileDescription === 0x52617221 /* Rar! */) 
		{
			return 'RAR';
		} 
		else if ($fileDescription === 0x45474741) 
		{
			return 'EGG';
		} 
		else if ($fileDescription === 0x60EA2900) 
		{
			return 'ARJ';
		} 
		else if ($fileDescription === 0) 
		{
			return 'EMPTY';
		} 
		else if ($fileDescription === 0x64383A61) 
		{
			return 'TORRENT';
		} 
		else if ($fileDescription === 0x5B4E575A /* NWZ */) 
		{
			return 'NWC';
		} 
		else if ($fileDescription === 0x464C5601) 
		{
			return 'FLV';
		} 
		else if ($fileDescription === 0x213C6172) 
		{
			return 'IPK';
		} 
		else if ($fileDescription === 0x556E6974) 
		{
			return 'UnityFS';
		} 
		else if ($fileDescription === 0x3C3F7068) 
		{
			return 'PHP';
		} 
		else if ($fileDescription === 0x3C3F786D) 
		{
			return 'XML';
		} 
		
		return false;
	}

	/**
	 * Get the contents of the file.
	 *
	 * @param string $filePath
	 *
	 * @return string
	 */
	public static function getContent(string $filePath): string
	{
		$filePath = self::convertToNomalizePath($filePath);

		if (!self::isExists($filePath)) 
		{
			throw new FileIsNotExistsException(FileHandlerMessage::getFileIsNotExistsMessage());
		}

		if (!self::isFile($filePath)) 
		{
			throw new TargetIsNotFileException(FileHandlerMessage::getFileIsNotExistsMessage());
		}

		$fileHandler = self::Open($filePath, FileMode::READ_ONLY);
		$fileSize    = self::getSize($filePath);
		$return      = fread($fileHandler, $fileSize);
		fclose($fileHandler);

		return $return;
	}

	/**
	 * Download the file.
	 *
	 * @param string $filePath
	 *
	 * @return string
	 */
	public static function Download(string $filePath, int $bufferSize = 0): bool
	{
		$filePath = self::convertToNomalizePath($filePath);

		if (!self::isExists($filePath)) 
		{
			throw new FileIsNotExistsException(FileHandlerMessage::getFileIsNotExistsMessage());
		}

		if (!self::isFile($filePath)) 
		{
			throw new TargetIsNotFileException(FileHandlerMessage::getFileIsNotExistsMessage());
		}

		$fileHandler = self::Open($filePath, 'rb');
		if ($fileHandler === false) 
		{
			return false;
		}

		if ($fileHandler) 
		{
			while (!feof($fileHandler)) 
			{
				print(@fread($fileHandler, $bufferSize > 0 ? $bufferSize : (1024 * 8)));
				ob_flush();
				flush();
			}
		}

		fclose($fileHandler);

		return true;
	}

	/**
	 * Get the file's extension.
	 *
	 * @param string $filePath
	 *
	 * @return string
	 */
	public static function getExtension(string $filePath): string
	{
		$return = null;

		$filePath = self::convertToNomalizePath($filePath);

		if (function_exists("pathinfo")) 
		{
			$return = self::getExtensionByFilePath($filePath);
		} 
		else 
		{
			$dotExists = strrchr($filePath, '.');

			if ($dotExists !== false) 
			{
				$return = substr($dotExists, 1);
			}
		}

		return $return;
	}

	/**
	 * Return file pointer of specific file
	 *
	 * @param string  $filePath
	 * @param string  $mode
	 * @param boolean $use_include_path
	 *
	 * @return bool
	 */
	public static function Open($filePath, $mode, $use_include_path = false): bool|\resource
	{
		$handler = fopen($filePath, $mode);

		return $handler;
	}

	public static function generateHashByContents(string $algorithm = 'md5', string $filePath, bool|null $rawContents = true): string
	{
		$return = hash_file($algorithm, $filePath, $rawContents);

		return $return;
	}

	public static function getStandardErrorStream($mode = FileMode::READ_ONLY)
	{
		$phpProtocol = new PHPProtocol();

		$handler = self::Open($phpProtocol->getStandardError(), $mode);

		return $handler;
	}

	/**
	 * Check if two files are identical
	 *
	 * @param string $filePath
	 * @param string $secondPath
	 * @param int    $chunkSize
	 *
	 * @return bool
	 */
	public static function isEqual($firstPath, $secondPath, $chunkSize = 500)
	{
		// https://stackoverflow.com/questions/30107521/check-if-same-image-has-already-been-uploaded-by-comparing-base64

		// First check if file are not the same size as the fastest method
		if (filesize($firstPath) !== filesize($secondPath)) 
		{
			return false;
		}

		// Compare the first ${chunkSize} bytes
		// This is fast and binary files will most likely be different
		$fp1            = self::Open($firstPath, FileMode::READ_ONLY);
		$fp2            = self::Open($secondPath, FileMode::READ_ONLY);
		$chunksAreEqual = fread($fp1, $chunkSize) == fread($fp2, $chunkSize);
		fclose($fp1);
		fclose($fp2);

		if (!$chunksAreEqual) 
		{
			return false;
		}

		// Compare hashes
		// SHA1 calculates a bit faster than MD5
		$firstChecksum  = sha1_file($firstPath);
		$secondChecksum = sha1_file($secondPath);
		if ($firstChecksum != $secondChecksum) 
		{
			return false;
		}

		return true;
	}

}