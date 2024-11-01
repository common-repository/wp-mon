<?php

	/*
	 * @author Lukas Berger
	 * @package WP-Mon
	 * @version 0.5.1
	*/
	class WPMonDisk
	{
	
		public static function GetTotalDiskSpace()
		{
			return get_option('wpmon_diskspace_max') * 1024 * 1024;
		}

		public static function GetUsedDiskSpace_DirSize($dir)
		{
			@$dh = opendir($dir);       
			$size = 0;       

			while ($file = @readdir($dh))       
			{         

				if ($file != "." and $file != "..")         
				{           
					$path = $dir."/".$file;           
					if (is_dir($path))          
					{             
						$size += WPMonDisk::GetUsedDiskSpace_DirSize($path);     
					}          
					elseif (is_file($path))          
					{             
						$size += filesize($path);         
					}         
				}       

			}
				
			@closedir($dh);      
			return $size;     
		}

		public static function GetUsedDiskSpace()
		{
			return WPMonDisk::GetUsedDiskSpace_DirSize(get_option('wpmon_diskspace_dir'));
		}
		
	}

?>