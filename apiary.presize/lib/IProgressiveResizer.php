<?php
/**
 * Apiary ProgressiveResizer
 * @package apiary
 * @subpackage progressiveresizer
 * @copyright 2014 Apiary
 */

	namespace Apiary\ProgressiveResizer;

	interface IProgressiveResizer
	{
		const RT_ORIGINAL = 1; // Resize Type
		function resize($inputImageAbsPath, $newWidth, $newHeight, $resizeType = self::RT_ORIGINAL, $jpgQuality = 90);
	}

	interface IWatermarkGenerator
	{
		function applyWatermark();
	}