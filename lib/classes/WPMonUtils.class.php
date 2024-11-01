<?php

	/*
	 * @author Lukas Berger
	 * @package WP-Mon
	 * @version 0.5.1
	*/
	class WPMonUtils 
	{
	
		public static function ConvertBytes($bytes, $precision = 2) {
			$units = array('B', 'KB', 'MB', 'GB', 'TB'); 

			$bytes = max($bytes, 0); 
			$pow = floor(($bytes ? log($bytes) : 0) / log(1024)); 
			$pow = min($pow, count($units) - 1); 

			// Uncomment one of the following alternatives
			// $bytes /= pow(1024, $pow);
			$bytes /= (1 << (10 * $pow)); 

			return round($bytes, $precision) . ' ' . $units[$pow]; 
		} 

		public static function str_contains($needle, $haystack)
		{
			if (strpos($haystack, $needle) !== false) {
				return true;
			}
			return false;
		}
		
		
		public static function truncate($string, $length, $dots = "...") {
			return (strlen($string) > $length) ? substr($string, 0, $length - strlen($dots)) . $dots : $string;
		}
	
	}


?>