<?php
/**
 * Apiary
 * @package apiary
 * @copyright 2014 Apiary
 */

 	namespace Apiary\Common;
 
	class Service
	{
		function __construct() {}

		static public function showArray()
		{
			$arArgs = func_get_args();
			foreach($arArgs as $arArg)
			{
				// echo('<pre>', htmlspecialchars(print_r($arArg, TRUE)), '<pre>');
				echo '<pre>', htmlspecialchars(print_r($arArg, TRUE)), '</pre>';
			}
		}

		static public function getMicrotime()
		{
			list($usec, $sec) = explode(' ', microtime());
			return ((float)$usec + (float)$sec);
		}
	}