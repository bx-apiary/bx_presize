<?php
/**
 * Apiary ProgressiveResizer
 * @package apiary
 * @subpackage progressiveresizer
 * @copyright 2014 Apiary
 */

	namespace Apiary\ProgressiveResizer;
	
	require_once getenv('DOCUMENT_ROOT') . '/resizer/lib/config.php';
	require_once 'ApiaryException.php';

	class ImageFile
	{
		const MAX_FILESIZE = 104857600; // 100 Mb
		public $path = '';
		public $width = 0;
		public $height = 0;
		public $type = '';
		public $ext = '';

//		function __construct(string $path)
		function __construct($path)
		{
			if (file_exists($path))
			{
				if (filesize($path) <= self::MAX_FILESIZE)
				{
					if (($arInfo = getimagesize($path)) !== FALSE)
					{
						if (self::isImageByMime($arInfo['mime']))
						{
							$this->path = $path;
	
							$arPathInfo = pathinfo($path);
							$this->ext = $arPathInfo['extension'];
	
							$this->width = $arInfo['0'];
							$this->height = $arInfo['1'];
							$this->type = $arInfo['2'];
						}
						else
						{
							throw new ApiaryException('File is not image');
							// throw new ApiaryException('Unsupported image type');
						}						
					}
					else 
					{
						throw new ApiaryException('Error from getimagesize()');
					}
				}
				else
				{
					throw new ApiaryException('File is too big. Max size is ' . self::MAX_FILESIZE . ' bytes');
				}
			}
			else
			{
				throw new ApiaryException('File not exists');
			}
		}

		public function gdCreateImage()
		{
			// GD lib
			$im = FALSE;
			if (in_array($this->type, array(
				IMAGETYPE_JPEG
				, IMAGETYPE_PNG
				, IMAGETYPE_GIF
			)))
			{
				switch ($this->type)
				{
					case IMAGETYPE_JPEG:
						$im = imagecreatefromjpeg($this->path);
						break;
					case IMAGETYPE_GIF:
						$im = imagecreatefromgif($this->path);
						break;
					case IMAGETYPE_PNG:
						$im = imagecreatefrompng($this->path);
						break;
					default:
						$im = imagecreatefromjpeg($this->path);
						break;
				}
			}
			else
			{
				throw new ApiaryException('Unsupported image type');
			}
			return $im;
		}
		
		public static function isImageByMime($mime)
		{
			return (strpos($mime, 'image/') === 0);
		}
	}