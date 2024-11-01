<?php

	/*
	 * @author Lukas Berger
	 * @package WP-Mon
	 * @version 0.5.1
	*/
	class WPMonPageDebug
	{

		public static function WPMonPageDebug_Init() {	
			add_action( 'admin_footer_text', array( 'WPMonLoader', 'WPMonAdminFooter' ) );	

			?>			
				<div id="wp-mon-debug-page">
					<div class="wrap">
						<h2>
							<?php _e( 'Debug', 'wp-mon' ); ?>
						</h2>
						<?php						
							if(get_option('wpmon_setup') == null || get_option('wpmon_setup') == false)
							{
								?>
								<div id='settings-error' class='update-nag'>
									WP-Mon: <?php _e("You must setup the plugin first", 'wp-mon'); ?>, <a href="./admin.php?page=wp-mon/settings"><?php _e( 'Change settings', 'wp-mon' ); ?></a>
								</div>				
								<?php
							}							
						?>
						<div>
							<?php _e('Show all important system informations'); ?>
						</div>
						<br />
						<div class="code">
							<?php echo WPMonPageDebug::GetSystemInfos(); ?>
						</div>
					</div>
				</div>	
			
			<?php
		}
				
		public static function GetSystemInfos() {
			global $wpdb;
			
			$infos = "";
			
			$infos .= "<b><i>WordPress " . get_bloginfo("version") . "</i></b><br />\n";
			$infos .= "Language: " . get_bloginfo("language") . "<br />\n";
			$infos .= "<br />\n";
			if(ini_get('allow_url_fopen') == true) {
				$infos .= "furl_open: on<br />\n";
			} else {
				$infos .= "furl_open: off<br />\n";
			} 
			$infos .= "<br />\n";			
			$infos .= "<b><i>Security infos</i></b><br />\n";
			
			if($wpdb->prefix == "wp_") { 
				$infos .= "<label style=\"color: red\">It's recommended to change the table prefix (Current: " . $wpdb->prefix . ")</label><br />\n"; 
			} else {
				$infos .= "<label style=\"color: green\">You don't using the standard prefix (Current: " . $wpdb->prefix . ")</label><br />\n"; 			
			}
			
			if(is_writeable( ABSPATH . ".htaccess" )) { 
				$infos .= "<label style=\"color: red\">Your .htaccess is writeable</label><br />\n"; 
			} else {
				$infos .= "<label style=\"color: green\">Your .htaccess is not writeable</label><br />\n"; 			
			}			
			if(is_writeable( ABSPATH . "wp-config.php" )) { 
				$infos .= "<label style=\"color: red\">Your wp-config.php is writeable</label><br />\n"; 
			} else {
				$infos .= "<label style=\"color: green\">Your wp-config.php is not writeable</label><br />\n"; 			
			}
			
			$infos .= "<br />\n";
			$infos .= "<br />\n";
			
			if( ini_get('disable_functions') == "") {
				$infos .= "<b><i>No disabled function</i></b><br />\n"; 
			} 
			else { 
				$infos .= "<b><i>Disabled functions:</i></b><br /> - " . str_replace(",", "<br /> - ",  ini_get('disable_functions')) . "<br />\n"; 		
			}
			
			$infos .= "<br />\n";
			$infos .= "Maximal Memory: " . WPMonUtils::ConvertBytes(WPMonMemory::GetTotalMemory()) . "<br />\n";
			$infos .= "Used Memory: " . WPMonUtils::ConvertBytes(WPMonMemory::GetUsedMemory()) . "<br />\n";
			$infos .= "<br />\n";
			
			$plugins = 1;
			foreach(get_plugins() as $key => $plugin)
			{						
				$infos .= "<b>Plugin #" . $plugins . ":</b> " . $plugin['Name'] . " <b>by</b> " . $plugin['Author'] . "<br />\n";
				$plugins++;
			}
			$infos .= "<br />\n";
			
			$infos .= "OS: " . php_uname('s') . "<br />\n";
			$infos .= "Machine: " . php_uname('m') . "<br />\n";
			$infos .= "PHP-Version: " . phpversion() . "<br />\n";
			
			$mysql = mysqli_connect(DB_HOST, DB_USER, DB_PASSWORD);
			$infos .= "MySQL-Version: " . mysqli_get_server_info($mysql) . "<br />\n";
			
			$infos .= "<br />\n";

			return $infos;
		}
		
		public static function GetPosition()
		{
			return 98;
		}

		public static function init() {
			if( get_option( 'wpmon_module_debug' ) == "1" ) 
				add_submenu_page('wp-mon', __('Debug', 'wp-mon'), __('Debug', 'wp-mon'), 'administrator', "wp-mon/debug", array( 'WPMonPageDebug', 'WPMonPageDebug_Init' ) );
		}
		
	}
	
?>