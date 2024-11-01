<?php

	/*
	 * @author  Lukas Berger
	 * @version  0.3
	 * @package  WP-Mon
	*/
	class WPMonFileMgr {
		
		/* Full path of file */
		private $fullpath = "";
		
		/* Only the directory's name */
		private $dirname = "";
		
		/* Only the file's name */
		private $filename = "";
		
		/*
		 @author  Lukas Berger
		 @description  Initialize FileMgr
		*/
		public function __construct( $path ) {
		
			$this->fullpath = $path;
			$this->dirname = dirname( $path );
			$this->filename = basename( $path );
		
		}
		
		/*
		 @author  Lukas Berger
		 @description  Returns MIME-Type
		*/
		public function getMimeType( $mode = 0 )
		{
			$mime_types = array(
				'txt' => 'text/plain',
				'htm' => 'text/html',
				'html' => 'text/html',
				'php' => 'application/x-httpd-php',
				'css' => 'text/css',
				'js' => 'application/javascript',
				'json' => 'application/json',
				'xml' => 'application/xml',
				'swf' => 'application/x-shockwave-flash',
				'flv' => 'video/x-flv',

				// images
				'png' => 'image/png',
				'jpe' => 'image/jpeg',
				'jpeg' => 'image/jpeg',
				'jpg' => 'image/jpeg',
				'gif' => 'image/gif',
				'bmp' => 'image/bmp',
				'ico' => 'image/vnd.microsoft.icon',
				'tiff' => 'image/tiff',
				'tif' => 'image/tiff',
				'svg' => 'image/svg+xml',
				'svgz' => 'image/svg+xml',

				// archives
				'zip' => 'application/zip',
				'rar' => 'application/x-rar-compressed',
				'exe' => 'application/x-msdownload',
				'msi' => 'application/x-msdownload',
				'cab' => 'application/vnd.ms-cab-compressed',

				// audio/video
				'mp3' => 'audio/mpeg',
				'qt' => 'video/quicktime',
				'mov' => 'video/quicktime',

				// adobe
				'pdf' => 'application/pdf',
				'psd' => 'image/vnd.adobe.photoshop',
				'ai' => 'application/postscript',
				'eps' => 'application/postscript',
				'ps' => 'application/postscript',

				// ms office
				'doc' => 'application/msword',
				'rtf' => 'application/rtf',
				'xls' => 'application/vnd.ms-excel',
				'ppt' => 'application/vnd.ms-powerpoint',
				'docx' => 'application/msword',
				'xlsx' => 'application/vnd.ms-excel',
				'pptx' => 'application/vnd.ms-powerpoint',


				// open office
				'odt' => 'application/vnd.oasis.opendocument.text',
				'ods' => 'application/vnd.oasis.opendocument.spreadsheet',
			);

			$ext_exploded = explode( '.', $this->fullpath );
			$ext_arraypop = array_pop( $ext_exploded );
			$ext = strtolower( $ext_arraypop );

			if( function_exists( 'mime_content_type' ) && $mode == 0 ){
				$mimetype = mime_content_type( $this->fullpath );
				return $mimetype;
			}

			if( function_exists( 'finfo_open' ) && $mode == 0 )
			{
				$finfo = finfo_open( FILEINFO_MIME );
				$mimetype = finfo_file( $finfo, $this->fullpath );
				finfo_close( $finfo );
				return $mimetype;
			}
			else if( array_key_exists( $ext, $mime_types ) )
			{
				return $mime_types[$ext];
			}
			else 
			{
				return 'application/octet-stream';
			}

		}
				
		/*
		 @author  Lukas Berger
		 @description  Returns filesize in Bytes
		*/		
		public function getSizeInByte()
		{
			return filesize( $this->fullpath ) . " B";  
		}
		
		/*
		 @author  Lukas Berger
		 @description  Returns filesize in Kilobyte
		*/		
		public function getSizeInKilobyte( $prec )
		{
			return round( filesize( $this->fullpath ) / 1024, $prec ) . " KB";  
		}
		
		/*
		 @author  Lukas Berger
		 @description  Returns filesize in Megabyte
		*/		
		public function getSizeInMegabyte( $prec )
		{
			return round( filesize( $this->fullpath ) / 1024 / 1024, $prec ) . " MB";  
		}
		
		/*
		 @author  Lukas Berger
		 @description  Returns filesize in Gigabyte
		*/		
		public function getSizeInGigabyte( $prec )
		{
			return round( filesize( $this->fullpath ) / 1024 / 1024 / 1024, $prec ) . " GB";  
		}
		
		/*
		 @author  Lukas Berger
		 @description  Returns filename
		*/		
		public function getFileName()
		{
			return $this->filename;
		}
		
		/*
		 @author  Lukas Berger
		 @description  Returns directory
		*/		
		public function getDirName()
		{
			return $this->dirname;
		}
		
		/*
		 @author  Lukas Berger
		 @description  Returns full path
		*/		
		public function getFullName()
		{
			return $this->fullpath;
		}
		
	}
	
?>