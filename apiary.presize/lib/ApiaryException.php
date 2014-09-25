<?php
/**
 * Apiary
 * @package apiary
 * @copyright 2014 Apiary
 */

	namespace Apiary\Common;

	class ApiaryException extends \Exception
	{
        
		function __construct($message = '', $code = 0)
		{
			parent::__construct($message, $code);
			
		}

		public function show()
		{
			echo $this->getMessage();
		}
	}