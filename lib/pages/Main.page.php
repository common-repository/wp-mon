<?php

	/*
	 * @author Lukas Berger
	 * @package WP-Mon
	 * @version 0.5.1
	*/
	class WPMonPageMain
	{

		public static function WPMonPageMain_Init() {
			add_action( 'admin_footer_text', array( 'WPMonLoader', 'WPMonAdminFooter' ) );
			
			$disk_total = WPMonDisk::GetTotalDiskSpace();
			$disk_used = WPMonDisk::GetUsedDiskSpace();
			
				$color_memory = '';
				if (round((WPMonMemory::GetUsedMemory() / WPMonMemory::GetTotalMemory()) * 100, 2) > 95) {
					$color_memory = '#DB0000';
				} elseif (round((WPMonMemory::GetUsedMemory() / WPMonMemory::GetTotalMemory()) * 100, 2) > 90) {
					$color_memory = '#D8B400';
				} else {
					$color_memory = '#41D800';
				}
				
				$color_disk = '';
				if (round(($disk_used / $disk_total) * 100, 2) > 95) {
					$color_disk = '#DB0000';
				} elseif (round(($disk_used / $disk_total) * 100, 2) > 90) {
					$color_disk = '#D8B400';
				} else {
					$color_disk = '#41D800';
				}
				
				$pb_memory = '<div style="height: 15px; margin-right: 20px;">
							  <div style="background: ' . $color_memory . '; width: ' . round((WPMonMemory::GetUsedMemory() / WPMonMemory::GetTotalMemory()) * 100, 2) . '%; height: 100%;"></div>
							  </div>';
				$pb_disk = '<div style="height: 15px; margin-right: 20px;">
							<div style="background: ' . $color_disk . '; width: ' . round(($disk_used / $disk_total) * 100, 2) . '%; height: 100%;"></div>
							</div>';
				
				$mysql = mysqli_connect( DB_HOST, DB_USER, DB_PASSWORD );
				
				$memory = array (
					array( 'ID' => 1, 'Name' => "<b>" . __( "Total Memory", "wp-mon" ) . "</b>", 'Value' => WPMonUtils::ConvertBytes(WPMonMemory::GetTotalMemory()) ),
					array( 'ID' => 2, 'Name' => "<b>" . __( "Used Memory", "wp-mon" ) . "</b>", 'Value' => WPMonUtils::ConvertBytes(WPMonMemory::GetUsedMemory()) ),
					array( 'ID' => 3, 'Name' => "<b>" . __( "Available Memory", "wp-mon" ) . "</b>", 'Value' => WPMonUtils::ConvertBytes(WPMonMemory::GetTotalMemory() - WPMonMemory::GetUsedMemory()) ),
					array( 'ID' => 5, 'Name' => "<b>" . __( "Percent", "wp-mon" ) . "</b>", 'Value' => round((WPMonMemory::GetUsedMemory() / WPMonMemory::GetTotalMemory()) * 100, 2) . " % " . $pb_memory )
				);
				$disk = array (
					array( 'ID' => 1, 'Name' => "<b>" . __( "Total Disk Space", "wp-mon" ) . "</b>", 'Value' => WPMonUtils::ConvertBytes( $disk_total ) ),
					array( 'ID' => 2, 'Name' => "<b>" . __( "Used Disk Space", "wp-mon" ) . "</b>", 'Value' => WPMonUtils::ConvertBytes( $disk_used ) ),
					array( 'ID' => 3, 'Name' => "<b>" . __( "Available Disk Space", "wp-mon" ) . "</b>", 'Value' => WPMonUtils::ConvertBytes( $disk_total - $disk_used ) ),
					array( 'ID' => 4, 'Name' => "<b>" . __( "Percent", "wp-mon" ) . "</b>", 'Value' => round( ( $disk_used / $disk_total ) * 100, 2 ) . " % " . $pb_disk )
				);
								
				$wordpress = array (
					array( 'ID' => 1, 'Name' => "<b>" . __("Version", "wp-mon") . "</b>", 'Value' => get_bloginfo("version") ),
					array( 'ID' => 2, 'Name' => "<b>" . __("Language", "wp-mon") . "</b>", 'Value' => get_bloginfo("language") ),
					array( 'ID' => 3, 'Name' => "<b>" . __("Page-Title", "wp-mon") . "</b>", 'Value' => get_bloginfo("name") ),
					array( 'ID' => 4, 'Name' => "<b>" . __("Home", "wp-mon") . "</b>", 'Value' => get_bloginfo("wpurl") )
				);
				$stats = array (
					array( 'ID' => 1, 'Name' => "<b>" . __("Comments", "wp-mon") . "</b>", 'Value' => count( get_comments() ) ),
					array( 'ID' => 2, 'Name' => "<b>" . __("Pages", "wp-mon") . "</b>", 'Value' => count( get_pages() ) ),
					array( 'ID' => 3, 'Name' => "<b>" . __("Plugins", "wp-mon") . "</b>", 'Value' => count( get_plugins() ) ) ,
					array( 'ID' => 4, 'Name' => "<b>" . __("Posts", "wp-mon") . "</b>", 'Value' => count( get_posts() ) ) ,
					array( 'ID' => 5, 'Name' => "<b>" . __("Tags", "wp-mon") . "</b>", 'Value' => count( get_tags() ) ) ,
					array( 'ID' => 6, 'Name' => "<b>" . __("Themes", "wp-mon") . "</b>", 'Value' => count( wp_get_themes() ) ) ,
					array( 'ID' => 7, 'Name' => "<b>" . __("Users", "wp-mon") . "</b>", 'Value' => count( get_users() ) )
				);
				$server = array (
					array( 'ID' => 1, 'Name' => "<b>" . __("Operating System", "wp-mon") . "</b>", 'Value' => php_uname('s') ),
					array( 'ID' => 2, 'Name' => "<b>" . __("Machine Type", "wp-mon") . "</b>", 'Value' => php_uname('m') ),
					array( 'ID' => 3, 'Name' => "<b>" . __("PHP-Version", "wp-mon") . "</b>", 'Value' => phpversion() ) ,
					array( 'ID' => 4, 'Name' => "<b>" . __("MySQL-Version", "wp-mon") . "</b>", 'Value' => mysqli_get_server_info( $mysql ) ) ,
					array( 'ID' => 5, 'Name' => "<b>" . __("IP-Address", "wp-mon") . "</b>", 'Value' => gethostbyname(gethostname()) )
				);
				?>
						
				<div id="wp-mon-main-page">
					<div class="wrap">
						<h1>
							WP-Mon - <?php _e('Overview', "wp-mon"); ?>
						</h1>
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
						<div id="wp-mon-page-memory">
							<h2><?php _e('Memory', "wp-mon"); ?></h2>
							<?php
								$memory_list = new WPMonListTable( $memory, array ( 'Name' => __( 'Name', 'wp-mon'), 'Value' => __( 'Value', 'wp-mon') ) );
								$memory_list->prepare_items(); 
								$memory_list->display(); 
							?>	
						</div>
						<br>
						<div id="wp-mon-page-disk">
							<h2><?php _e('Disk Space', "wp-mon"); ?></h2>
							<?php
								$memory_list = new WPMonListTable( $disk, array ( 'Name' => __( 'Name', 'wp-mon'), 'Value' => __( 'Value', 'wp-mon') ) );
								$memory_list->prepare_items(); 
								$memory_list->display(); 
							?>	
						</div>
						<br>
						<div id="wp-mon-page-wordpress">
							<h2><?php _e('WordPress', "wp-mon"); ?></h2>
							<?php
								$wordpress_list = new WPMonListTable( $wordpress, array ( 'Name' => __( 'Name', 'wp-mon'), 'Value' => __( 'Value', 'wp-mon') ) );
								$wordpress_list->prepare_items(); 
								$wordpress_list->display(); 
							?>	
						</div>
						<br>
						<div id="wp-mon-page-stats">
							<h2><?php _e('Statistics', "wp-mon"); ?></h2>
							<?php
								$stats_list = new WPMonListTable( $stats, array ( 'Name' => __( 'Name', 'wp-mon'), 'Value' => __( 'Value', 'wp-mon') ) );
								$stats_list->prepare_items(); 
								$stats_list->display(); 
							?>	
						</div>
						<br>
						<div id="wp-mon-page-server">
							<h2><?php _e('Server', "wp-mon"); ?></h2>
							<?php
								$server_list = new WPMonListTable( $server, array ( 'Name' => __( 'Name', 'wp-mon'), 'Value' => __( 'Value', 'wp-mon') ) );
								$server_list->prepare_items(); 
								$server_list->display(); 
							?>	
						</div>
					</div>
				</div>						
			<?php
		}

		public static function GetPosition()
		{
			return 0;
		}

		public static function init() { }
		
	}
	
?>