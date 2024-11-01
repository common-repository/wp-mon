<?php
	
	/*
	 * @author Lukas Berger
	 * @package WP-Mon
	 * @version 0.5.1
	*/
	class WPMonLoader
	{		
	
		function __construct() {								
			add_action( 'admin_menu', array( $this, 'AddMenu' ) );
			add_action( 'init', array( $this, 'LoadLanguages') );			
			add_action( 'admin_print_styles', array ( $this, 'WPAdminHead' ) );			
			add_action( 'admin_init', array( $this, 'WPMonAdminInit' ) );		
			
			register_activation_hook(__FILE__, array( $this, 'PluginActivate') );
						
			file_put_contents( 
								ABSPATH . 'wp-admin' . DIRECTORY_SEPARATOR . 'wpmon-download.php',
								file_get_contents( plugin_dir_path(dirname( __FILE__ )) . 'wp-mon' . DIRECTORY_SEPARATOR . 'assets' . DIRECTORY_SEPARATOR . 'download.php' )
							);
			
			$this->LoadLibraries( plugin_dir_path(dirname( __FILE__ )) . 'wp-mon' . DIRECTORY_SEPARATOR . 'lib' . DIRECTORY_SEPARATOR . 'classes' );
			$this->LoadPages( plugin_dir_path(dirname( __FILE__ )) . 'wp-mon' . DIRECTORY_SEPARATOR . 'lib' . DIRECTORY_SEPARATOR . 'pages' );		
				
			if(get_option('wpmon_setup') == null || get_option('wpmon_setup') == false)
			{
				if(WPMonFuncEnabled::func_enabled('disk_total_space'))
				{			
					update_option('wpmon_diskspace_max', disk_total_space(ABSPATH));
				}
				else
				{			
					update_option('wpmon_diskspace_max', "1024");
				}
				
				update_option('wpmon_diskspace_dir', ABSPATH);
				update_option('wpmon_use_exception_handler', "1");		
				update_option('wpmon_use_error_handler', "0");
				
				update_option('wpmon_module_phpinfos', "1");		
				update_option('wpmon_module_visits', "1");			
				update_option('wpmon_module_filebrowser', "1");			
				update_option('wpmon_module_debug', "1");					
			}
		}
		
		function AddMenu()
		{
			add_menu_page('WP-Mon', 'Monitor', 'administrator', "wp-mon", array( 'WPMonPageMain', 'WPMonPageMain_Init' ) , 'dashicons-desktop', '3.75');
		}
		
		function PluginActivate() {
			add_option('wpmon_welcome_redirect', true);
		}
		
		public static function WPMonAdminFooter() {
			?>
			<em><? _e( 'WP-Mon by', 'wp-mon' ); ?>: <a href="htpt://lukasberger.at/">lukasberger.at</a></em>
			<?php
		}
		
		function WPMonAdminInit() {
			if (get_option('wpmon_welcome_redirect', false)) {
				delete_option('wpmon_welcome_redirect');
				wp_redirect("admin.php?page=wpmon-about");
			}
						
			add_option("wpmon_diskspace_max", "", "", "yes");	
			add_option("wpmon_log_visits", "", "", "yes");
			
			
			/* Register general settings */
			register_setting( 'wpmon-settings', 'wpmon_setup' );
			register_setting( 'wpmon-settings', 'wpmon_diskspace_max' );
			register_setting( 'wpmon-settings', 'wpmon_use_exception_handler' );
			register_setting( 'wpmon-settings', 'wpmon_use_error_handler' );
			
			/* Register module settings */
			register_setting( 'wpmon-settings', 'wpmon_module_phpinfos' );
			register_setting( 'wpmon-settings', 'wpmon_module_visits' );
			register_setting( 'wpmon-settings', 'wpmon_module_filebrowser' );
			register_setting( 'wpmon-settings', 'wpmon_module_debug' );
		}
						
		public static function WPAdminHead()
		{
			?>
			<!-- BEGIN WP-Mon -->
				<link href="//netdna.bootstrapcdn.com/font-awesome/4.2.0/css/font-awesome.css" type="text/css" rel="stylesheet">		
			<!-- END WP-Mon -->
			<?php
		}
		
		function LoadLanguages()
		{
			$domain = 'wp-mon';
			$locale = apply_filters('plugin_locale', get_locale(), $domain);
			
			load_textdomain($domain, WP_LANG_DIR . DIRECTORY_SEPARATOR . $domain . '-' . $locale . '.mo');
			load_textdomain($domain, dirname(__FILE__) . DIRECTORY_SEPARATOR . "languages" . DIRECTORY_SEPARATOR . $locale . '.mo');
		}
		
		function LoadPages($dir)
		{
			$files = glob($dir . '/*.page.php');
			if( $files == null ) return;
			
			$pages[] = array ( );
			
			foreach($files as $file)
			{
				$class = "WPMonPage" . basename($file, ".page.php");	
							
				require_once( $file );
				
				$pos = $class::GetPosition();
				$pages[$pos] = basename($file, ".page.php");
			}
			
			ksort($pages);		
			
			foreach($pages as $key => $page)
			{				
				if( method_exists( "WPMonPage" . $page, 'wp_init' ) ) add_action( 'admin_init', array( "WPMonPage" . $page , 'wp_init' ) );
				add_action( 'admin_menu', array( "WPMonPage" . $page , 'init' ) );
			}
		}
		
		function LoadLibraries($dir)
		{
			$files = glob($dir . '/*.class.php');
			if( $files == null ) return;
			
			foreach($files as $file)
			{				
				require_once($file);
				
				$class = basename( $file, '.class.php' );
				
				if( method_exists( $class, 'Initialize' ) )
				{
					$class::Initialize();				
				}
			}
		}
			
	}

?>