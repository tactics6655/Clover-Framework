<?php

namespace Xanax\Implement;

interface FileSystemInterface
{

	public static function getCurrentInode();

	public static function getStat($filePath): array;

	public static function getDeviceNumber($filePath);

	public static function getInodeNumber($filePath);

	public static function getProtectionNumber($filePath);

	public static function getLinkNumber($filePath);

	public static function getOwnerUserID($filePath);

	public static function getOwnerGroupID($filePath);

	public static function getDeviceType($filePath);

	public static function getSizeOfByte($filePath);

	public static function getLastAccessTime($filePath);

	public static function getLastModifiedTime($filePath);

	public static function getLastInodeModifiedTime($filePath);

	public static function getIOBlockSize($filePath);

	public static function get512ByteAllocatedBlocks($filePath);
}
