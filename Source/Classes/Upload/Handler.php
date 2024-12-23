<?php

declare(strict_types=1);

namespace Clover\Classes\Upload;

use Clover\Classes\File\Handler as FileHandler;

use Clover\Enumeration\UploadedFile;
use Clover\Enumeration\UploadedFileErrorMessage;

class Handler
{

	public static function get($name, $key = UploadedFile::FULLY_DATA)
	{
		if ($key === UploadedFile::FULLY_DATA) {
			return isset($_FILES[$name]) ? $_FILES[$name] : null;
		}

		if (preg_match('/^([A-Za-z0-9-_]{1,})\[[A-Za-z0-9-_]{1,}\]$/', $name, $match)) {
			return isset($_FILES[$match[1]][$key][$match[2]]) ? $_FILES[$match[1]][$key][$match[2]] : null;
		}

		return isset($_FILES[$name][$key]) ? $_FILES[$name][$key] : null;
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
		switch ($error) {
			case \UPLOAD_ERR_OK:
				$response = UploadedFileErrorMessage::UPLOAD_ERR_OK;
				break;
			case \UPLOAD_ERR_INI_SIZE:
				$response = UploadedFileErrorMessage::UPLOAD_ERR_INI_SIZE;
				break;
			case \UPLOAD_ERR_FORM_SIZE:
				$response = UploadedFileErrorMessage::UPLOAD_ERR_FORM_SIZE;
				break;
			case \UPLOAD_ERR_PARTIAL:
				$response = UploadedFileErrorMessage::UPLOAD_ERR_PARTIAL;
				break;
			case \UPLOAD_ERR_NO_FILE:
				$response = UploadedFileErrorMessage::UPLOAD_ERR_NO_FILE;
				break;
			case \UPLOAD_ERR_NO_TMP_DIR:
				$response = UploadedFileErrorMessage::UPLOAD_ERR_NO_TMP_DIR;
				break;
			case \UPLOAD_ERR_CANT_WRITE:
				$response = UploadedFileErrorMessage::UPLOAD_ERR_CANT_WRITE;
				break;
			case \UPLOAD_ERR_EXTENSION:
				$response = UploadedFileErrorMessage::UPLOAD_ERR_EXTENSION;
				break;
			default:
				$response = UploadedFileErrorMessage::UPLOAD_ERR_UNKNOWN;
				break;
		}

		return $response;
	}

	public static function isEmpty()
	{
		return empty($_FILES);
	}

	public static function hasError($name)
	{
		return (self::getFileError($name) !== UPLOAD_ERR_OK);
	}

	/**
	 * Tells whether the file was uploaded via HTTP POST
	 */
	public static function isTemporaryUploaded($name)
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

	public static function inExtension($name, $extensions)
	{
		return (in_array(self::getExtension($name), $extensions));
	}

	public static function getExtension($name)
	{
		$fileName = self::get($name, UploadedFile::NAME);

		return substr($fileName, strrpos($fileName, '.') + 1);
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

	public static function getFileErrorMessage($name)
	{
		$code = self::getFileError($name);

		return self::getErrorMessageFromCode($code);
	}

	public static function isExists($name = UploadedFile::TEMPORARY_NAME)
	{
		return self::get($name) !== null;
	}
}
