<?php

	/*
	 * @author Lukas Berger
	 * @package WP-Mon
	 * @version 0.5.1
	*/
	class WPMonPageSettings
	{

		public static function WPMonPageSettings_Init() {
			add_action( 'admin_footer_text', array( 'WPMonLoader', 'WPMonAdminFooter' ) );
			
		?>
			<div id="wp-mon-settings-page">
				<div class="warp">
						<h1><?php _e('Settings'); ?></h1>
						<?php	
						
						if(get_option('wpmon_setup') == null || get_option('wpmon_setup') == false)
						{
							?>
							<div id='settings-error' class='update-nag'>
								WP-Mon: <?php _e("You must setup the plugin first", 'wp-mon'); ?>, <a href="./admin.php?page=wp-mon/settings"><?php _e( 'Change settings', 'wp-mon' ); ?></a>
							</div>				
							<?php
						}							
						
						
						if(isset( $_POST['reinstall'] ) )
						{
							delete_option( 'wpmon_setup' );
							delete_option( 'wpmon_diskspace_max' );
							delete_option( 'wpmon_log_visits' );
							delete_option( 'wpmon_installed_dbversion' );
							
							delete_option( 'wpmon_module_phpinfos' );		
							delete_option( 'wpmon_module_visits' );			
							delete_option( 'wpmon_module_filebrowser' );			
							delete_option( 'wpmon_module_debug' );	
					
							?>
							<div id='setting-error-settings_updated' class='updated settings-error'>
								<p><?php _e('Settings resetted.', 'wp-mon'); ?></p>
							</div> 							
							<?php
							
						}
						
						if( isset( $_GET['settings-updated'] ) )
						{
						?>
							<div id='setting-error-settings_updated' class='updated settings-error'>
								<p><?php _e('Settings updated.', 'wp-mon'); ?></p>
							</div> 							
						<?php
						}
					
						
						?>
													
						<form method="POST" action="options.php">
									
							<input type="hidden" name="wpmon_setup" value="true">															
							<?php WPMonPageSettings::WPMonSettingsBox(); ?>
							
						</form>					
				</div>
			</div>				
					<?php
					//}
		}

		public static function WPMonSettingsBox() {										
		
			settings_fields( 'wpmon-settings' );
			do_settings_sections( 'wpmon-settings' ); 
			?>									
							<table id="wpmon-settings-general" class="form-table">
								<tbody>
								
									<tr>
										<th scope="row"><?php _e( 'Disk Space', 'wp-mon' ); ?></th>
										<td>
											<input type="text" value="<?php echo get_option('wpmon_diskspace_max'); ?>" name="wpmon_diskspace_max" id="wpmon_diskspace_max" />
											<label for="wpmon_diskspace_max"> <b><?php _e( 'Megabytes', 'wp-mon' ); ?></b></label>
											<p class="description"><?php _e( 'Size of disk space you can use for your WordPress-Installation', 'wp-mon'); ?></p>
										</td>
									</tr>
									
									<tr>
										<th scope="row"><?php _e( 'Show exceptions', 'wp-mon' ); ?></th>
										<td>
											<input id="wpmon_use_exception_handler" type="checkbox" value="1" <?php checked( get_option('wpmon_use_exception_handler'), '1' ); ?> name="wpmon_use_exception_handler" />
											<label for="wpmon_use_exception_handler"> <b><?php _e( 'Show all PHP-Exceptions', 'wp-mon'); ?></b></label>
											<p class="description"><?php _e( 'Showing all occured PHP-Exceptions in WordPress in a message box', 'wp-mon'); ?></p>
										</td>
									</tr>
								
									<tr>
										<th scope="row"><?php _e( 'Show errors', 'wp-mon' ); ?></th>
										<td>
											<input id="wpmon_use_error_handler" type="checkbox" value="1" <?php checked( get_option('wpmon_use_error_handler'), '1' ); ?> name="wpmon_use_error_handler" />
											<label for="wpmon_use_error_handler"> <b><?php _e( 'Show all PHP-Errors', 'wp-mon'); ?></b></label>
											<p class="description"><?php _e( 'Showing all occured PHP-Errors in WordPress in a message box', 'wp-mon'); ?></p>
										</td>
									</tr>
									
								</tbody>
							</table>	
									
							<?php submit_button();	?>
											
							<table id="wpmon-settings-modules" class="form-table">
								<tbody>
								
									<tr>
										<th scope="row"><?php _e( 'PHP-Infos', 'wp-mon' ); ?></th>
										<td>
											<input id="wpmon_module_phpinfos" type="checkbox" value="1" <?php checked( get_option('wpmon_module_phpinfos'), '1' ); ?> name="wpmon_module_phpinfos" />
											<label for="wpmon_module_phpinfos"> <b><?php _e( 'Use "PHP-Infos"', 'wp-mon' ); ?></b></label>
											<p class="description"><?php _e( 'The module PHPInfo shows you every accessible PHP-Variable', 'wp-mon'); ?></p>
										</td>
									</tr>
								
									<tr>
										<th scope="row"><?php _e( 'Visitor-Log', 'wp-mon' ); ?></th>
										<td>
											<input id="wpmon_module_visits" type="checkbox" value="1" <?php checked( get_option('wpmon_module_visits'), '1' ); ?> name="wpmon_module_visits" />
											<label for="wpmon_module_visits"> <b><?php _e( 'Use "Visitor-Log"', 'wp-mon'); ?></b></label>
											<p class="description"><?php _e( 'Visitor-Log saves some useful infos about your visitors', 'wp-mon'); ?></p>
										</td>
									</tr>
								
									<tr>
										<th scope="row"><?php _e( 'File-Browser', 'wp-mon' ); ?></th>
										<td>
											<input id="wpmon_module_filebrowser" type="checkbox" value="1" <?php checked( get_option('wpmon_module_filebrowser'), '1' ); ?> name="wpmon_module_filebrowser" />
											<label for="wpmon_module_filebrowser"> <b><?php _e( 'Use "File-Browser"', 'wp-mon'); ?></b></label>
											<p class="description"><?php _e( 'With the file-browser you can edit, delete, copy or move any file of your WordPress-Installation', 'wp-mon'); ?></p>
										</td>
									</tr>
								
									<tr>
										<th scope="row"><?php _e( 'Debug', 'wp-mon' ); ?></th>
										<td>
											<input id="wpmon_module_debug" type="checkbox" value="1" <?php checked( get_option('wpmon_module_debug'), '1' ); ?> name="wpmon_module_debug" />
											<label for="wpmon_module_debug"> <b><?php _e( 'Use "Debug"', 'wp-mon'); ?></b></label>
											<p class="description"><?php _e( 'Gather some useful infos for forums from where you need help with your WordPress', 'wp-mon'); ?></p>
										</td>
									</tr>
									
								</tbody>
							</table>	
									
							<?php submit_button();		
		}
		
		public static function GetPosition() {
			return 97;
		}
						
		public static function wp_scripts() {
			wp_enqueue_script( 
				'jquery-ui-postbox', 
				plugins_url( 'assets/js/postbox.js', dirname( dirname( __FILE__ ) ) ),
				array( 'jquery-ui-sortable', 'jquery' )
			);	
			wp_enqueue_script('postbox');
		}

		public static function wp_init() {			
			add_meta_box( "wpmon-settings-general", __('General', 'wp-mon'), array( 'WPMonPageSettings', "WPMonGeneralSettingsBox" ), "wpmon-settings", "advanced", "high" );
			add_meta_box( "wpmon-settings-modules", __('Modules', 'wp-mon'), array( 'WPMonPageSettings', "WPMonModuleSettingsBox" ), "wpmon-settings", "advanced", "high" );
			
			add_screen_option('layout_columns', array('max' => 2, 'default' => 2) );
		}
		
		public static function init() {
			$hook_suffix = add_submenu_page('wp-mon', __('Settings', 'wp-mon'), __('Settings', 'wp-mon'), 'administrator', "wp-mon/settings", array( 'WPMonPageSettings', 'WPMonPageSettings_Init' ) );
			add_action('admin_print_scripts-' . $hook_suffix, array( 'WPMonPageSettings', 'wp_scripts' ) );
		}
		
	}
	
?>