<?php

	/*
	 * @author Lukas Berger
	 * @package WP-Mon
	 * @version 0.5.1
	 * @license YOU ARE NOR ALLOWED TO CHANGE THIS SITE BECAUSE IT CONTAINS AUTHOR-CONTENT
	*/
	class WPMonPageAbout
	{

		public static function WPMonPageAbout_Init() {		
			add_action( 'admin_footer_text', array( 'WPMonLoader', 'WPMonAdminFooter' ) );
			
			?>			
			<div id="wp-mon-about-page">
				<div class="wrap about-wrap">
					<h1><img src="http://ps.w.org/wp-mon/assets/icon.svg?rev=1010653" width="64" height="64" /> <?php echo __('WP-Mon', 'wp-mon') . ' 0.5'; ?></h1>		
					
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
					
					<div class="about-text">
						<?php _e('Thank you for installing WP-Mon. You can read the changelog or continue the setup.', 'wp-mon'); ?>
					</div>
					<h2 class="nav-tab-wrapper">
						<a class="nav-tab nav-tab-active" href="http://lukasberger.at/"><?php _e( 'Developer\'s website', 'wp-mon'); ?></a>
						<a class="nav-tab" href="./admin.php?page=wp-mon"><?php _e( 'Start now!', 'wp-mon'); ?></a>
					</h2>					
					<div class="changelog" style="text-align: center">
						<h3><?php _e('Under the hood', 'wp-mon'); ?></h3>
						<div class="feature-section col three-col">
							<div>
								<h4><span class="fa fa-4x fa-folder-open"></span></h4>
								<h4><?php _e('Disk Space', 'wp-mon'); ?></h4>
								<p><?php _e('Get very simple the disk space which your WordPress-Installation is used.', 'wp-mon'); ?></p>
							</div>
							<div>
								<h4><span class="fa fa-4x fa-file-text"></span></h4>
								<h4><?php _e('Main Memory', 'wp-mon'); ?></h4>
								<p><?php _e('Show your current RAM', 'wp-mon'); ?></p>
							</div>
							<div class="last-feature">
								<h4><span class="fa fa-4x fa-wordpress"></span></h4>
								<h4><?php _e('Your WordPress', 'wp-mon'); ?></h4>
								<p><?php _e('Get more infos about your WordPress installation.', 'wp-mon'); ?></p>
							</div>
						</div>
						<div class="feature-section col three-col">
							<div>
								<h4><span class="fa fa-4x fa-folder-open"></span></h4>
								<h4><?php _e('File-Browser', 'wp-mon'); ?></h4>
								<p><?php _e('A very simple file-browser for each user which isn\'t so good with FTP', 'wp-mon'); ?></p>
							</div>
							<div>
								<h4><span class="fa fa-4x fa-th-list"></span></h4>
								<h4><?php _e('Stats', 'wp-mon'); ?></h4>
								<p><?php _e('Stats with comments, pages and more', 'wp-mon'); ?></p>
							</div>
							<div class="last-feature">
								<h4><span class="fa fa-4x fa-list-alt"></span></h4>
								<h4><?php _e('PHP configuration', 'wp-mon'); ?></h4>
								<p><?php _e('Important and all PHP variables at one click', 'wp-mon'); ?></p>
							</div>
						</div>
						<div class="feature-section col three-col">
							<div class="last-feature">
								<h4><span class="fa fa-4x fa-tasks"></span></h4>
								<h4><?php _e('The server', 'wp-mon'); ?></h4>
								<p><?php _e('Some infos about your server like the OS oder the public IP', 'wp-mon'); ?></p>
							</div>
						</div>
					</div>
					<hr />
					<div class="changelog" style="text-align: center">
						<h3><?php _e('Design', 'wp-mon'); ?></h3>
						<div class="feature-section col three-col">
							<div>
								<h4><span class="fa fa-4x fa-wordpress"></span></h4>
								<h4><?php _e('Using WordPress', 'wp-mon'); ?></h4>
								<p><?php _e('WP-Mon only using WordPress-CSS. The result is that WP-Mon is always styled in the latest version of WordPress', 'wp-mon'); ?></p>
							</div>
							<div>
								<h4><span class="fa fa-4x fa-users"></span></h4>
								<h4><?php _e('Simple but much', 'wp-mon'); ?></h4>
								<p><?php _e('WP-Mon show much infos very simple and modern', 'wp-mon'); ?></p>
							</div>
						</div>
					</div>
					<hr />
					<br />	
					
					<code>
						<strong>WPMON_VERSION:</strong> <?php echo WPMON_VERSION; ?>
					</code>
					<br />
					<br />	
					<code>
						<strong>WPMON_PLUGIN_DIR:</strong> <?php echo WPMON_PLUGIN_DIR; ?>
					</code>
					<br />	
					<br />					
					<code>
						<strong>WPMON_PLUGIN_URL:</strong> <?php echo WPMON_PLUGIN_URL; ?>
					</code>
				</div>
			</div>
			
			<?php
		}

		public static function GetPosition()
		{
			return 99;
		}
		
		public static function init() {
			add_submenu_page('wp-mon', __('About', 'wp-mon'), __('About', 'wp-mon'), 'administrator', "wp-mon/about", array( 'WPMonPageAbout', 'WPMonPageAbout_Init' ) );
		}
		
	}
	
?>