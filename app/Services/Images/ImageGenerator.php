<?php


namespace App\Services\Images;

use Intervention\Image\Facades\Image;

class ImageGenerator
{
	public const IMAGE_QUALITY = 80;
	public const IMAGE_OUTPUT_TYPE = 'jpg';
	public const IMAGE_MAX_WIDTH = 1200;
	public const THUMBNAIL_WIDTH = 400;

	/**
	 * @param $file
	 * @return \Intervention\Image\Image
	 */
	public static function createThumbnail($file): \Intervention\Image\Image
	{
		return Image::make($file)->encode(self::IMAGE_OUTPUT_TYPE, self::IMAGE_QUALITY)
			->resize(self::THUMBNAIL_WIDTH, null, static function ($constraint) {
				$constraint->aspectRatio();
			});
	}

	/**
	 * @param $file
	 * @return \Intervention\Image\Image
	 */
	public static function resizeImageIfNeeded($file): \Intervention\Image\Image
	{
		$image = Image::make($file);
		if($image->getWidth() > self::IMAGE_MAX_WIDTH){
			$image->resize(self::IMAGE_MAX_WIDTH, null, static function($constraint){
				$constraint->aspectRatio();
			});
		}
		return $image->encode(self::IMAGE_OUTPUT_TYPE, self::IMAGE_QUALITY);
	}
}
