<?php
	if (!defined('PATH_SEPARATOR'))
	{
		define('PATH_SEPARATOR', getenv('COMSPEC') ? ';' : ':');
	}
	ini_set('include_path', ini_get('include_path') . PATH_SEPARATOR . dirname(__FILE__));
	/*
		require_once getenv('DOCUMENT_ROOT') . '/lib/config.php';
		require_once 'library_name.php';
	*/
?>