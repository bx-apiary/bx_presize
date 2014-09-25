<?php
/**
 * Apiary ProgressiveResizer
 * @package apiary
 * @subpackage progressiveresizer
 * @copyright 2014 Apiary
 */

	namespace Apiary\ProgressiveResizer;
	
/*
 *	@TODO
 *	- if ($newWidth > $width)
 *	- disk quota
 *	- events GetModuleEvents('apiary.progressiveresizer', 'OnBeforeResizeImage', TRUE)
 *	- imageCopyResampled || imageCopyResized
 *	- quality <= 100
 *	- cacheTime
 *	- Interface IResizer
 *	- transparent (
 * 		// У PNG заменяем прозрачность на белый цвет, т.к. превью отдается в jpg
 * 		$color = imageColorAllocate($im, $R, $G, $B);
 *		imageFill($im, 0, 0, $color);
 * 		)
 *		(
 *		$txtcolor = "FFFFFF";
 *		sscanf($txtcolor, '%2x%2x%2x', $R, $G, $B); // $txtcolor = sprintf('%2x%2x%2x, $R, $G, $B) 
 *		imageColorAllocate($im, $R, $G, $B) // уже существующий, ближайший к требуемому
 * 		мож ваще средний цвет
 *		)
 *	- сделать возможность сохранять в тот формат, который у входного файла
 *	- watermark
 *	- out JPG only
 *	- empty image, no_photo.jpg
 *	- error text on picture
 *	- headers (
 *		Content-type, Last-Modified, Content-Transfer-Encoding,
 *		Content-Disposition, Content-Length, Connection
 * 		)
 *	- cacheManager
 *	- memorylimits
 *	- from URL (allow_url_fopen) (getimagesize() not supported URL)
 *	- watermark: fonts
 *	- exception list on phpDoc
*/

	require_once getenv('DOCUMENT_ROOT') . '/resizer/lib/config.php';
	require_once 'IProgressiveResizer.php';
	require_once 'ApiaryException.php';
	require_once 'ImageFile.php';

	class ProgressiveResizer implements IProgressiveResizer  
	{
		private $src = '';
		private $inputImageFile;
		private $newWidth = 0;
		private $newHeight = 0;
		private $resizeType = self::RT_ORIGINAL;
		private $quality = 90;
		private $cacheFileName = '';
		private $cacheDir = '/resizer/upload/apiary/progressiveresizer/cache';
		// private $cacheDirMaxSize = (1024 * 1024) * 100;
		// private $ignoreCache = FALSE;
		// private $cacheManager;
		
		function __construct(){}

		public function resize($inputImageAbsPath, $newWidth, $newHeight, $resizeType = self::RT_ORIGINAL, $quality = 90)
		{
			$this->inputImageFile = new ImageFile($inputImageAbsPath);
			$this->resizeType = $resizeType;
			$this->newWidth = $newWidth;
			$this->newHeight = $newHeight;

			$this->buildCacheFileName();
			if (!file_exists($this->cacheFileName))
			{
				$this->gdResize();
			}
			return $this->src;
		}
		
		private function gdResize()
		{
			// @TODO rename to resizeGD
			// GD lib
			$src_im = $this->inputImageFile->gdCreateImage();
			$dst_im = imagecreatetruecolor($this->newWidth, $this->newHeight);
			// imageCopyResampled(
			imagecopyresized(
				$dst_im, $src_im,
				0, 0, 0, 0,
				$this->newWidth, $this->newHeight,
				$this->inputImageFile->width, $this->inputImageFile->height
			);
			imageinterlace($dst_im, 1); // progressive JPEG
			imagejpeg($dst_im, $this->cacheFileName, $this->quality);
			imagedestroy($src_im);
			imagedestroy($dst_im);
		}
		
		private function buildCacheFileName()
		{
			$salt = $this->newWidth . $this->newHeight . $this->resizeType . $this->quality;
			$this->src =
				$this->cacheDir . '/' .
				md5($salt . md5_file($this->inputImageFile->path)) .
				'.' . $this->inputImageFile->ext;
			$this->cacheFileName = getenv('DOCUMENT_ROOT') . $this->src; 
		}		
	}