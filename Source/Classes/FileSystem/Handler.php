<?php

declare(strict_types=1);

namespace Neko\Classes\FileSystem;

use Neko\Implement\FileSystemInterface as FileSystemInterface;

class Handler implements FileSystemInterface
{

	public function __construct()
	{
	}

	public static  function getCurrentInode()
	{
		return getmyinode();
	}

	public static function getStatFromIndex($filePath, $index)
	{
		$stat = self::getStat($filePath);

		if (count($stat) >= $index) {
			return $stat[$index];
		}

		return false;
	}

	public static function getStat($filePath): array
	{
		$return = stat($filePath);

		return $return;
	}

	public static function getDeviceNumber($filePath)
	{
		return self::getStatFromIndex($filePath, 0);
	}

	public static function getInodeNumber($filePath)
	{
		return self::getStatFromIndex($filePath, 1);
	}

	public static function getProtectionNumber($filePath)
	{
		return self::getStatFromIndex($filePath, 2);
	}

	public static function getLinkNumber($filePath)
	{
		return self::getStatFromIndex($filePath, 3);
	}

	public static function getOwnerUserID($filePath)
	{
		return self::getStatFromIndex($filePath, 4);
	}

	public static function getOwnerGroupID($filePath)
	{
		return self::getStatFromIndex($filePath, 5);
	}

	public static function getDeviceType($filePath)
	{
		return self::getStatFromIndex($filePath, 6);
	}

	public static function getSizeOfByte($filePath)
	{
		return self::getStatFromIndex($filePath, 7);
	}

	public static function getLastAccessTime($filePath)
	{
		return self::getStatFromIndex($filePath, 8);
	}

	public static function getLastModifiedTime($filePath)
	{
		return self::getStatFromIndex($filePath, 9);
	}

	public static function getLastInodeModifiedTime($filePath)
	{
		return self::getStatFromIndex($filePath, 10);
	}

	public static function getIOBlockSize($filePath)
	{
		return self::getStatFromIndex($filePath, 11);
	}

	public static function get512ByteAllocatedBlocks($filePath)
	{
		return self::getStatFromIndex($filePath, 12);
	}
}
