<?php

declare(strict_types=1);

namespace Neko\Classes\Image;

use Neko\Implement\ImageHandlerInterface;

use Neko\Enumeration\Orientation;
use Neko\Enumeration\ImageFilter;
use Neko\Enumeration\ExifFileHeader;
use Neko\Enumeration\MIME;

use Neko\Classes\Header\File as FileHeader;

use function getimagesizefromstring;
use GdImage;
use Exception;

class Handler implements ImageHandlerInterface
{

	public function parseGif($filename)
	{
		if (!$fp = @fopen($filename, 'rb')) {
			return false;
		}

		$signature = fread($fp, 3);
		$version = fread($fp, 3);
		$screen_descriptor = fread($fp, 7);

		$screen_width = ((ord($screen_descriptor[1])) << 8) +
			((ord($screen_descriptor[0])));

		$screen_height = ((ord($screen_descriptor[3])) << 8) +
			((ord($screen_descriptor[2])));

		$global_color_table_size = (ord($screen_descriptor[4]) + ord($screen_descriptor[5]));
		$global_color_table_size = 3 * (2 ^ ($global_color_table_size + 1));

		echo $global_color_table_size;
	}

	//http://www.php.net/manual/en/function.imagecreatefromgif.php#104473
	public function isAnimated($filename)
	{
		if (!($fh = @fopen($filename, 'rb'))) {
			return false;
		}

		$count = 0;
		// an animated gif contains multiple "frames", with each frame having a
		// header made up of:
		// * a static 4-byte sequence (\x00\x21\xF9\x04)
		// * 4 variable bytes
		// * a static 2-byte sequence (\x00\x2C) (some variants may use \x00\x21 ?)

		// We read through the file til we reach the end of the file, or we've found
		// at least 2 frame headers
		while (!feof($fh) && $count < 2) {
			$chunk = fread($fh, 1024 * 100); //read 100kb at a time
			$count += preg_match_all(
				'#\x00\x21\xF9\x04.{4}\x00(\x2C|\x21)#s',
				$chunk,
				$matches
			);
		}

		fclose($fh);
		return $count > 1;
	}

	/**
	 * Draw picture to pallete
	 *
	 * @param mixed $imageResource
	 * @param int $width
	 * @param int $height
	 *
	 * return Resource
	 */
	public function drawRepeat($imageResource, $tile, int $width = 0, int $height = 0)
	{
		if (!self::isResource($imageResource)) {
			$imageResource = self::getInstance($imageResource);
		}

		$width = $width ?? self::getWidth($imageResource);
		$height = $height ?? self::getHeight($imageResource);

		imagesettile($imageResource, $tile);
		imagefilledrectangle($imageResource, 0, 0, $width, $height, IMG_COLOR_TILED);

		return $imageResource;
	}

	/**
	 * Get a exif data of image file
	 *
	 * @param resource $imageResource
	 *
	 * @return mixed
	 */
	public function getExifData($filePath, $header = ExifFileHeader::MAIN_IMAGE)
	{
		if (function_exists('exif_read_data')) {
			return exif_read_data($filePath, $header);
		}

		return $filePath;
	}

	private function getExifOrientationData($orientation)
	{
		$corrections = array(
			// Horizontal (normal)
			'1' => array(
				"Degree" => 0,
				"Orientation" => Orientation::NORMAL
			),
			// Mirror horizontal
			'2' => array(
				"Degree" => 0,
				"Orientation" => Orientation::HORIZONTAL
			),
			// Rotate 180
			'3' => array(
				"Degree" => 180,
				"Orientation" => Orientation::NORMAL
			),
			// Mirror vertical
			'4' => array(
				"Degree" => 0,
				"Orientation" => Orientation::VERTICAL
			),
			// Mirror horizontal and rotate 270 CW
			'5' => array(
				"Degree" => 270,
				"Orientation" => Orientation::HORIZONTAL
			),
			// Rotate 90 CW
			'6' => array(
				"Degree" => 270,
				"Orientation" => Orientation::NORMAL
			),
			// Mirror horizontal and rotate 90 CW
			'7' => array(
				"Degree" => 90,
				"Orientation" => Orientation::HORIZONTAL
			),
			// Rotate 270 CW
			'8' => array(
				"Degree" => 90,
				"Orientation" => Orientation::NORMAL
			)
		);

		return $corrections[$orientation];
	}

	public function fixOrientation($filePath, $imageResource)
	{
		$exif = $this->getExifData($filePath);

		$image = $imageResource;
		$degree = 0;
		$flip = 0;

		if (!empty($exif['Orientation'])) {
			$orientation = $exif['Orientation'];

			$data = $this->getExifOrientationData($orientation);

			$degree = $data['Degree'];
			$flip = $data['Orientation'];
		}

		$image = $this->rotate($imageResource, $degree);

		switch ($flip) {
			case Orientation::VERTICAL:
			case Orientation::HORIZONTAL:
				$image = $this->flip($image, $flip);
				break;
		}

		return $image;
	}

	/**
	 * Draw eclipse to image resource
	 *
	 * @param mixed $imageResource
	 * @param int      $width
	 * @param int      $height
	 * @param int      $x
	 * @param int      $y
	 * @param int      $reg
	 * @param int      $green
	 * @param int      $blue
	 *
	 * @return mixed
	 */
	public function drawEclipse($imageResource, $width, $height, $x, $y, $red, $green, $blue)
	{
		if (!self::isResource($imageResource)) {
			$imageResource = self::getInstance($imageResource);
		}

		$backgroundColor = imagecolorallocate($imageResource, $red, $green, $blue);
		$outputImage = imagefilledellipse($imageResource, $x, $y, $width, $height, $backgroundColor);

		return $outputImage;
	}

	public function combine($paletteImage, $combineImage, $right = 0, $top = 0)
	{
		if (!self::isResource($paletteImage)) {
			$paletteImage = self::getInstance($paletteImage);
		}

		if (!self::isResource($combineImage)) {
			$combineImage = self::getInstance($combineImage);
		}

		$x = imagesx($paletteImage) - imagesx($combineImage) - $right;
		$y = imagesy($paletteImage) - imagesy($combineImage) - $top;
		imagecopy($paletteImage, $combineImage, intval($x), intval($y), 0, 0, imagesx($combineImage), imagesy($combineImage));

		return $paletteImage;
	}

	/**
	 * Ratio resize to specific size
	 *
	 * @param mixed $imageResource
	 * @param int      $resizeWidth
	 * @param int      $resizeHeight
	 *
	 * @return resource
	 */
	public function ratioResize($imageResource, $resizeWidth, $resizeHeight, $thumbnailWidth = 0, $thumbnailHeight = 0)
	{
		if (!self::isResource($imageResource)) {
			$imageResource = self::getInstance($imageResource);
		}

		list($origin_width, $origin_height) = getimagesize($imageResource);
		$ratio = $origin_width / $origin_height;
		$resizeWidth = $resizeHeight = min($resizeWidth, max($origin_width, $origin_height));

		if ($ratio < 1) {
			$resizeWidth = $thumbnailHeight * $ratio;
		} else {
			$resizeHeight = $thumbnailWidth / $ratio;
		}

		$outputImage = imagecreatetruecolor($resizeWidth, $resizeHeight);

		$width = self::getWidth($imageResource);
		$height = self::getHeight($imageResource);

		//make image alpha
		imageAlphaBlending($outputImage, false);
		imageSaveAlpha($outputImage, false);

		imagecopyresampled($outputImage, $imageResource, 0, 0, 0, 0, $resizeWidth, $resizeHeight, $width, $height);

		return $outputImage;
	}

	/**
	 * Crop Image
	 *
	 * @param mixed $imageResource
	 * @param int      $width
	 * @param int      $height
	 * @param int      $left
	 * @param int      $top
	 */
	public function crop($imageResource, $resizeWidth, $resizeHeight, $sourceX = 0, $sourceY = 0)
	{
		if (!self::isResource($imageResource)) {
			$imageResource = self::getInstance($imageResource);
		}

		$trueColorImage = self::createTrueColorImage($resizeWidth, $resizeHeight);
		$this->setAlphaBlendMode($trueColorImage);
		$this->saveAlphaChannel($trueColorImage, false);

		$this->resample($trueColorImage, $imageResource, 0, 0, $sourceX, $sourceY, $resizeWidth, $resizeHeight, $resizeWidth - $sourceX, $resizeHeight - $sourceY);

		return $trueColorImage;
	}

	public function centerCrop($imageResource, $resizeWidth, $resizeHeight)
	{
		if (!self::isResource($imageResource)) {
			$imageResource = self::getInstance($imageResource);
		}

		$sourceWidth = self::getWidth($imageResource);
		$sourceHeight = self::getHeight($imageResource);

		$centreX = round($sourceWidth / 2);
		$centreY = round($sourceHeight / 2);

		$cropWidthHalf  = round($resizeWidth / 2);
		$cropHeightHalf = round($resizeHeight / 2);

		$x1 = max(0, $centreX - $cropWidthHalf);
		$y1 = max(0, $centreY - $cropHeightHalf);

		$x2 = min($sourceWidth, $centreX + $cropWidthHalf);
		$y2 = min($sourceHeight, $centreY + $cropHeightHalf);

		$trueColorImage = self::createTrueColorImage($resizeWidth, $resizeHeight);
		$this->setAlphaBlendMode($trueColorImage);
		$this->saveAlphaChannel($trueColorImage, false);

		$this->resample($trueColorImage, $imageResource, 0, 0, (int)$x1, (int)$y1, $resizeWidth, $resizeHeight, $resizeWidth, $resizeHeight);

		return $trueColorImage;
	}

	public function resample($destinationImage, $imageResource, $destinationX = 0, $destinationY = 0, $sourceX = 0, $sourceY = 0, $destinationWidth = 0, $destinationHeight = 0, $sourceWidth = 0, $sourceHeight = 0)
	{
		imagecopyresampled($destinationImage, $imageResource, $destinationX, $destinationY, $sourceX, $sourceY, $destinationWidth, $destinationHeight, $sourceWidth, $sourceHeight);
	}

	public function saveAlphaChannel($imageResource, $saveFlag = false)
	{
		imageSaveAlpha($imageResource, $saveFlag);
	}

	public static function createTrueColorImage($width, $height)
	{
		return imagecreatetruecolor($width, $height);
	}

	public function setAlphaBlendMode($imageResource, $useBlendMode = true)
	{
		imagealphablending($imageResource, $useBlendMode);
	}

	/**
	 * Apply specific filter to image resource
	 *
	 * @param GdImage | resource $imageResource
	 * @param string $type
	 * @param resource $args1
	 * @param resource $args2
	 * @param resource $args3
	 *
	 * @return GdImage | resource stream
	 */

	// TODO get a args by array data
	public function filter($imageResource, string $type, ...$args)
	{
		$filter = 0;
		$type = strtolower($type);

		switch ($type) {
			case ImageFilter::REVERSE: // 0
				$filter = IMG_FILTER_NEGATE;
				break;
			case ImageFilter::GRAYSCALE: // 1
				$filter = IMG_FILTER_GRAYSCALE;
				break;
			case ImageFilter::BRIGHTNESS: // 2
				$filter = IMG_FILTER_BRIGHTNESS;
				break;
			case ImageFilter::CONTRAST: // 3
				$filter = IMG_FILTER_CONTRAST;
				break;
			case ImageFilter::COLORIZE: // 4
				$filter = IMG_FILTER_COLORIZE;
				break;
			case ImageFilter::EDGEDETECT: // 5
				$filter = IMG_FILTER_EDGEDETECT;
				break;
			case ImageFilter::EMBOSS: // 6
				$filter = IMG_FILTER_EMBOSS;
				break;
			case ImageFilter::GAUSSIAN_BLUR: // 7
				$filter = IMG_FILTER_GAUSSIAN_BLUR;
				break;
			case ImageFilter::SELECTIVE_BLUR: // 8
				$filter = IMG_FILTER_SELECTIVE_BLUR;
				break;
			case ImageFilter::SKETCH: // 9
				$filter = IMG_FILTER_MEAN_REMOVAL;
				break;
			case ImageFilter::SMOOTH: // 10
				$filter = IMG_FILTER_SMOOTH;
				break;
			case ImageFilter::PIXELATE: // 11
				$filter = IMG_FILTER_PIXELATE;
				break;
			case ImageFilter::SCATTER: // 12
				$filter = IMG_FILTER_SCATTER;
				break;
		}

		imagefilter($imageResource, $filter, $args);

		return $imageResource;
	}

	/**
	 * Draw a picture to buffer
	 *
	 * @param GdImage | string | resource $imageResource
	 *
	 * @return void
	 */
	public function draw($imageResource, $format, $quality = 1)
	{
		if (!self::isResource($imageResource)) {
			$imageResource = self::getInstance($imageResource);
		}

		switch ($format) {
			case MIME::IMAGE_JPEG:
				FileHeader::fromMIME('jpeg');
				imagejpeg($imageResource, null, $quality);
				break;
			case MIME::IMAGE_PNG:
				FileHeader::fromMIME('png');
				imagepng($imageResource, null, $quality);
				break;
			case MIME::IMAGE_BMP:
				FileHeader::fromMIME('bmp');
				imagebmp($imageResource, null);
				break;
			case MIME::IMAGE_GIF:
				FileHeader::fromMIME('gif');
				imagegif($imageResource);
				break;
			case MIME::IMAGE_WBMP:
				FileHeader::fromMIME('wbmp');
				imagewbmp($imageResource);
				break;
			case MIME::IMAGE_XBM:
				FileHeader::fromMIME('xbm');
				imagexbm($imageResource, null);
				break;
			case MIME::IMAGE_GD:
				header("Content-Type: image/gd");
				imagegd($imageResource);
				break;
			case MIME::IMAGE_GD2:
				header("Content-Type: image/gd2");
				imagegd($imageResource);
				break;
			default:
				break;
		}
	}

	/**
	 * Pick a color of specific position
	 *
	 * @param mixed $imageResource
	 * @param int      $x
	 * @param int      $y
	 *
	 * @return array($alpha, $r, $g, $b)
	 */
	public function pickColor($imageResource, $x, $y): array
	{
		if (!self::isResource($imageResource)) {
			$imageResource = self::getInstance($imageResource);
		}

		//  0xAARRGGBB => 00000001(alpha) 00000010(red) 00000011(green) 00000100(blue)
		$rgb = imagecolorat($imageResource, $x, $y);
		$alpha = ($rgb >> 24) & 0xFF;
		$r = ($rgb >> 16) & 0xFF;
		$g = ($rgb >> 8) & 0xFF;
		$b = $rgb & 0xFF;

		return array($alpha, $r, $g, $b);
	}

	/**
	 * Draw text to image resource
	 *
	 * @param mixed $imageResource
	 * @param int      $fontSize
	 * @param int      $x
	 * @param int      $y
	 * @param string   $text
	 * @param int      $reg
	 * @param int      $green
	 * @param int      $blue
	 *
	 * @return mixed
	 */
	public function drawText($imageResource, $fontSize, $x, $y, $text, $red, $green, $blue)
	{
		if (!self::isResource($imageResource)) {
			$imageResource = self::getInstance($imageResource);
		}

		$textcolor = imagecolorallocate($imageResource, $red, $green, $blue);
		imagestring($imageResource, $fontSize, $x, $y, $text, $textcolor);

		return $imageResource;
	}

	/**
	 * Get type of image file
	 *
	 * @param mixed $filePath
	 *
	 * @return mixed
	 */
	public static function getType($filePath)
	{
		$format = "unknown";

		if (self::isResource($filePath)) {
			$format = getimagesizefromstring($filePath);
		} else {
			$finfo = getimagesize($filePath);
			if ($finfo === false) {
				return false;
			}

			$format = $finfo['mime'];
		}

		return $format;
	}

	/**
	 * Create a image to path
	 *
	 * @param mixed $imageResource
	 * @param string   $outputPath
	 * @param int      $quality
	 *
	 * @return boolean
	 */
	public static function create($filePath, $imageResource, $outputPath, $quality = 100)
	{
		$format = self::getType($filePath);

		switch ($format) {
			case 'image/jpeg':
				imagejpeg($imageResource, $outputPath, $quality);
				break;
			case  'image/png':
				imagepng($imageResource, $outputPath);
				break;
			case  'image/gif':
				imagegif($imageResource, $outputPath);
				break;
			case  'image/wbmp':
				imagewbmp($imageResource, $outputPath);
				break;
			case  'image/xbm':
				imagexbm($imageResource, $outputPath);
				break;
			case  'image/gd':
				imagegd($imageResource, $outputPath);
				break;
			case  'image/gd2':
				imagegd2($imageResource, $outputPath);
				break;
			default:
				return false;
		}

		return true;
	}

	/**
	 * Flip a image resource
	 *
	 * @param mixed $imageResource
	 *
	 * @return resource
	 */
	public function flip($imageResource, $type)
	{
		if (!self::isResource($imageResource)) {
			$imageResource = self::getInstance($imageResource);
		}

		switch ($type) {
			case Orientation::VERTICAL:
				imageflip($imageResource, IMG_FLIP_VERTICAL);
				break;
			case Orientation::HORIZONTAL:
				imageflip($imageResource, IMG_FLIP_HORIZONTAL);
				break;
			case Orientation::BOTH:
				imageflip($imageResource, IMG_FLIP_BOTH);
				break;
		}

		return $imageResource;
	}

	/**
	 * Get width of image resource
	 *
	 * @param mixed $imageResource
	 *
	 * @return int
	 */
	public static function getWidth($imageResource): int
	{
		if (!self::isResource($imageResource)) {
			$imageResource = self::getInstance($imageResource);
		}

		if (function_exists('exif_read_data')) {
			$exifData = exif_read_data($imageResource, null, true, false);

			if (isset($exifData['COMPUTED'])) {
				$computed = $exifData['COMPUTED'];
				return $computed['Width'];
			}
		}

		return imagesx($imageResource);
	}

	/**
	 * Get height of image resource
	 *
	 * @param mixed $imageResource
	 *
	 * @return int
	 */
	public static function getHeight($imageResource): int
	{
		if (!self::isResource($imageResource)) {
			$imageResource = self::getInstance($imageResource);
		}

		if (function_exists('exif_read_data')) {
			$exif = exif_read_data($imageResource, null, true, false);

			if (isset($exif['COMPUTED'])) {
				$computed = $exif['COMPUTED'];
				return $computed['Height'];
			}
		}

		return imagesy($imageResource);
	}

	/**
	 * Check that resource is valid
	 *
	 * @param resource $imageResource
	 *
	 * @return boolean
	 */
	public static function isResource(mixed $imageResource)
	{
		if (gettype($imageResource) === 'resource' || $imageResource instanceof GdImage) {
			return true;
		}

		return false;
	}

	/**
	 * Rotate a image resource
	 *
	 * @param mixed $imageResource
	 * @param int $degrees
	 *
	 * @return resource
	 */
	public function rotate($imageResource, $degrees)
	{
		if (!self::isResource($imageResource)) {
			$imageResource = self::getInstance($imageResource);
		}

		$image = \imagerotate($imageResource, $degrees, 0);

		return $image;
	}

	public static function isGdExtensionLoaded()
	{
		return extension_loaded('gd');
	}

	/**
	 * Get a resource of file
	 *
	 * @param mixed $filePath
	 *
	 * @return resource|bool
	 */
	public static function getimageResource($filePath): mixed
	{
		$format = self::getType($filePath);
		$createObject = null;

		try {
			switch ($format) {
				case 'image/jpeg':
					if (self::isGdExtensionLoaded()) {
						$createObject = \imagecreatefromjpeg($filePath);
					}
					break;
				case 'image/bmp':
					$createObject = \imagecreatefrombmp($filePath);
					break;
				case 'image/png':
					if (self::isGdExtensionLoaded()) {
						$createObject = \imagecreatefrompng($filePath);
					}
					break;
				case 'image/gif':
					if (self::isGdExtensionLoaded()) {
						$createObject = \imagecreatefromgif($filePath);
					}
					break;
				case 'image/webp':
					if (self::isGdExtensionLoaded()) {
						$createObject = \imagecreatefromwebp($filePath);
					}
					break;
				default:
					return false;
			}
		} catch (Exception $e) {
		}

		return $createObject;
	}

	/**
	 * Get a resource of blank image
	 *
	 * @param int $width
	 * @param int $height
	 * @param int $red
	 * @param int $blue
	 * @param int $green
	 *
	 * @return resource
	 */
	public function getBlank($width, $height, $red, $blue, $green): GdImage|false
	{
		$image = imagecreatetruecolor($width, $height);
		if (!$image) {
			return false;
		}

		$backgroundColor = imagecolorallocate($image, $red, $green, $blue);
		if (!$backgroundColor) {
			return false;
		}

		imagefilledrectangle($image, 0, 0, $width, $height, $backgroundColor);
		imagecolortransparent($image, $backgroundColor);

		return self::getInstance($image);
	}

	public static function resize($imageResource, $resizeWidth, $resizeHeight)
	{
		if (!self::isResource($imageResource)) {
			$imageResource = self::getInstance($imageResource);
		}

		$outputImage = self::createTrueColorImage($resizeWidth, $resizeHeight);

		$width = self::getWidth($imageResource);
		$height = self::getHeight($imageResource);

		imageAlphaBlending($outputImage, false);
		imageSaveAlpha($outputImage, false);

		imagecopyresampled($outputImage, $imageResource, 0, 0, 0, 0, $resizeWidth, $resizeHeight, $width, $height);

		return $outputImage;
	}

	/**
	 * Merge of two image to palette
	 *
	 * @param mixed $sourceCreateObject
	 * @param mixed $mergeCreateObject
	 * @param int $transparent
	 *
	 * @return bool
	 */
	public function merge(mixed $sourceCreateObject, mixed $mergeCreateObject, $transparent)
	{
		if (!self::isResource($sourceCreateObject)) {
			$sourceCreateObject = self::getInstance($sourceCreateObject);
		}

		if (!self::isResource($mergeCreateObject)) {
			$mergeCreateObject = self::getInstance($mergeCreateObject);
		}

		$sourceWidth = self::getWidth($sourceCreateObject);
		$sourceHeight = self::getHeight($sourceCreateObject);

		return imagecopymerge($mergeCreateObject, $sourceCreateObject, 0, 0, 0, 0, $sourceWidth, $sourceHeight, $transparent);
	}

	/**
	 * Get a singletone of image file
	 *
	 * @param mixed $filePath
	 *
	 * @return GdImage | resource | false
	 */
	public static function getInstance(mixed $filePath)
	{
		if (self::isResource($filePath)) {
			return \getimagesizefromstring($filePath);
		}

		if (is_array(\getImageSize($filePath))) {
			return self::getImageResource($filePath);
		}

		$finfo = getImageSize($filePath);
		if ($finfo === false) {
			return false;
		}

		return $filePath;
	}

	/**
	 * Convert hex to rgb
	 *
	 * @param string $hex
	 *
	 * @return array
	 */
	public function hexToRgb($hex)
	{
		$rgb = substr($hex, 2, strlen($hex) - 1);

		$r = hexdec(substr($rgb, 0, 2));
		$g = hexdec(substr($rgb, 2, 2));
		$b = hexdec(substr($rgb, 4, 2));

		return array($r, $g, $b);
	}
}
