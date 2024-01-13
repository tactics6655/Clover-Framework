<?php

declare(strict_types=1);

namespace Xanax\Classes\Compression;

class Zlib
{
	public function uncompress($filePath, $destination)
	{

		if (!is_file($filePath) || !file_exists($filePath) || filesize($filePath) <= 0) {
			return false;
		}

		$fileHandler = fopen($filePath, 'rb');

		if (!$fileHandler) {
			fclose($fileHandler);
			return false;
		}

		$uncompressContents = fread($fileHandler, filesize($filePath));
		fclose($fileHandler);

		$uncompressing = gzuncompress($uncompressContents);

		if (!$uncompressing) {
			return false;;
		}

		$fileHandler = fopen($destination, 'wb');
		fwrite($fileHandler, $uncompressing);
		fclose($fileHandler);

		return true;
	}

	public function compress($filePath, $destination)
	{
		$fp = fopen($filePath, 'rb');
		$compressContents = fread($fp, filesize($filePath));
		fclose($fp);

		$compressing = gzcompress($compressContents);

		if ($compressing) {
			$fp = fopen($destination, 'wb');
			fwrite($fp, $compressing);
			fclose($fp);
		}
	}
}
