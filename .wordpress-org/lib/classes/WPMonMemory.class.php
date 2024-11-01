<?php

	/*
	 * @author Lukas Berger
	 * @package WP-Mon
	 * @version 0.5.1
	*/
	class WPMonMemory
	{
		
		public static function GetUsedMemory()
		{
			return memory_get_usage(true);
		}
			
		public static function GetTotalMemory()
		{
			$memory_limit = ini_get('memory_limit');
			if (preg_match('/^(\d+)(.)$/', $memory_limit, $matches)) {
				if ($matches[2] == 'M') {
					$memory_limit = $matches[1] * 1024 * 1024; // nnnM -> nnn MB
				} else if ($matches[2] == 'K') {
					$memory_limit = $matches[1] * 1024; // nnnK -> nnn KB
				}
			}
			return $memory_limit;
		}
		
	}

?>