<?php
				
	/*
	 * @author Lukas Berger
	 * @package WP-Mon
	 * @version 0.5.1
	*/
	class WPMonFuncEnabled {

		public static function func_enabled($func) {
			$disabled = explode(',', ini_get('disable_functions'));
			foreach ($disabled as $disableFunction) {
				$is_disabled[] = trim($disableFunction);
			}
			
			$enabled = false;
			if (in_array($func, $is_disabled)) {
				$enabled = false;
			} 
			else 
			{
				$enabled = true;
			}
			return $enabled;
		}
		
	}

?>