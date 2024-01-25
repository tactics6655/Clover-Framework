<?php

namespace Neko\Message\FileHandler;

class FileHandlerMessage
{

	public static function getTargetIsNotFileMessage()
	{
		return 'Target is not File';
	}

	public static function getInvalidFileHandler()
	{
		return 'Handler type is not a resource';
	}

	public static function getDoNotUseSubDirectorySyntaxMessage()
	{
		return "Don't use Subdirectory syntax";
	}

	public static function getDoNotUsePharProtocolMessage()
	{
		return "Don't use Phar protocol";
	}

	public static function getFileIsNotExistsMessage($filePath)
	{
		return "'${filePath}' File doesn't Exist";
	}
}
