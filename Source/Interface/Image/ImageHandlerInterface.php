<?php

namespace Xanax\Implement;

interface ImageHandlerInterface {
	
	public function isAnimated($filename);

	public function drawRepeat($imageResource, $width, $height);

	public function drawEclipse($imageResource, $width, $height, $x, $y, $red, $green, $blue);

	public function Combine($paletteImage, $combineImage, $right = 0, $top = 0);

	public function ratioResize($imageResource, $resizeWidth, $resizeHeight);

	public function Filter ($imageResource, string $type, ...$args);

	public function Draw($imageResource, $format);

	public function pickColor($imageResource, $x, $y) :array;

	public function drawText($imageResource, $fontSize, $x, $y, $text, $red, $green, $blue);

	public function getExifData($imageResource);

	public function fixOrientation($filePath, $imageResource);

	public static function getType($filePath);

	public static function Create($filePath, $imageResource, $outputPath, $quality = 100 );

	public function Flip($imageResource, $type);

	public static function getWidth($imageResource);

	public static function getHeight($imageResource);

	public static function isResource($imageResource);

	public function Rotate($imageResource, $degrees);

	public static function getimageResource($filePath);

	public function getBlank($width, $height, $red, $blue, $green);

	public function Merge($sourceCreateObject, $mergeCreateObject, $transparent);

	public static function getInstance($filePath);

	public function hexToRgb($hex);
	
}
