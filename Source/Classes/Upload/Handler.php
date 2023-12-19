<?php

declare(strict_types=1);

namespace Xanax\Classes\Upload;

use Xanax\Classes\File\Handler as FileHandler;

use Xanax\Enumeration\UploadedFile;
use Xanax\Enumeration\UploadedFileError;

class Handler 
{
	
	public static function get($name, $key = UploadedFile::NAME) 
	{
		if (preg_match('/^([A-Za-z0-9-_]{1,})\[[A-Za-z0-9-_]{1,}\]$/', $name, $match)) 
		{
			return isset($_FILES[$match[1]][$key][$match[2]]) ? $_FILES[$match[1]][UploadedFile::NAME][$match[2]] : null;
		} 
		else 
		{
			if ($key === UploadedFile::NAME) 
			{
				return isset($_FILES[$name]) ? $_FILES[$name] : null;
			} 
			else 
			{
				return isset($_FILES[$name][$key]) ? $_FILES[$name][$key] : null;
			}
		}
	}

	public static function move($name, $filePath) 
	{
		$temporaryName = self::getTemporaryName($name);

		$result = move_uploaded_file($temporaryName,  $filePath);
		
		return $result;
	}
	
	public static function isUploaded($name, $filePath) 
	{
		$temporaryName = self::getTemporaryName($name);
		
		return file_exists(sprintf("%s/%s", $filePath, $temporaryName));
	}
	
	public static function getErrorMessageFromCode($error) 
	{
		switch ($error) 
		{
			case \UPLOAD_ERR_OK:
				$response = UploadedFileError::UPLOAD_ERR_OK;
				break;
			case \UPLOAD_ERR_INI_SIZE:
				$response = UploadedFileError::UPLOAD_ERR_INI_SIZE;
				break;
			case \UPLOAD_ERR_FORM_SIZE:
				$response = UploadedFileError::UPLOAD_ERR_FORM_SIZE;
				break;
			case \UPLOAD_ERR_PARTIAL:
				$response = UploadedFileError::UPLOAD_ERR_PARTIAL;
				break;
			case \UPLOAD_ERR_NO_FILE:
				$response = UploadedFileError::UPLOAD_ERR_NO_FILE;
				break;
			case \UPLOAD_ERR_NO_TMP_DIR:
				$response = UploadedFileError::UPLOAD_ERR_NO_TMP_DIR;
				break;
			case \UPLOAD_ERR_CANT_WRITE:
				$response = UploadedFileError::UPLOAD_ERR_CANT_WRITE;
				break;
			case \UPLOAD_ERR_EXTENSION:
				$response = UploadedFileError::UPLOAD_ERR_EXTENSION;
				break;
			default:
				$response = 'Unknown upload error';
				break;
		}

		return $response;
	}

	public static function hasError($name) 
	{
		if (self::getFileError($name) === UPLOAD_ERR_OK) 
		{
			return false;
		}

		return true;
	}

	public static function isTempUploaded($name)
	{
		return is_uploaded_file($_FILES[$name][UploadedFile::TEMPORARY_NAME]);
	}

	public static function hasItem() 
	{
		return (count($_FILES) > 0);
	}

	public static function getTemporaryName($name) 
	{
		return self::get($name, UploadedFile::TEMPORARY_NAME);
	}

	public static function getFileType($name) 
	{
		return self::get($name, UploadedFile::TYPE);
	}

	public static function getFileName($name) 
	{
		return self::get($name, UploadedFile::NAME);
	}

	public static function getFileSize($name) 
	{
		return self::get($name, UploadedFile::SIZE);
	}

	public static function getFileError($name) 
	{
		return self::get($name, UploadedFile::ERROR);
	}

	public static function isExists($name = UploadedFile::TEMPORARY_NAME) 
	{
		if (self::get($name) === null) 
		{
			return false;
		}

		return true;
	}
	
}
