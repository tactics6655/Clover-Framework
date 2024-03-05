<?php

namespace Clover\Implement;

interface ImageHandlerInterface
{

	public function isAnimated($filename);

	public function drawRepeat($imageResource, $tile, int $width = 0, int $height = 0);

	public function drawEclipse($imageResource, $width, $height, $x, $y, $red, $green, $blue);

	public function combine($paletteImage, $combineImage, $right = 0, $top = 0);

	public function ratioResize($imageResource, $resizeWidth, $resizeHeight);

	public function filter($imageResource, string $type, ...$args);

	public function draw($imageResource, $format);

	public function pickColor($imageResource, $x, $y): array;

	public function drawText($imageResource, $fontSize, $x, $y, $text, $red, $green, $blue);

	public function getExifData($imageResource);

	public function fixOrientation($filePath, $imageResource);

	public static function getType($filePath);

	public static function create($filePath, $imageResource, $outputPath, $quality = 100);

	public function flip($imageResource, $type);

	public static function getWidth($imageResource);

	public static function getHeight($imageResource);

	public static function isResource($imageResource);

	public function rotate($imageResource, $degrees);

	public static function getimageResource($filePath);

	public function getBlank($width, $height, $red, $blue, $green);

	public function merge($sourceCreateObject, $mergeCreateObject, $transparent);

	public static function getInstance($filePath);

	public function hexToRgb($hex);
}
