<?php

	/*
	 * @author Lukas Berger
	 * @package WP-Mon
	 * @version 0.5.1
	*/
	class WPMonPageVisits
	{

		public static function WPMonPageVisits_Init() {	
			add_action( 'admin_footer_text', array( 'WPMonLoader', 'WPMonAdminFooter' ) );
			$last_visitor = WPMonVisitCounter::GetLastVisitor();

			?>			
				<div id="wp-mon-logs-page">
					<div class="wrap">
						<h1>
							<?php _e( 'Visits', 'wp-mon' ); ?>
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
						<div>
							<b><?php _e( 'IP of last visitor', 'wp-mon' ); ?>:</b> <?php echo $last_visitor['IP']; ?><br />
							<b><?php _e( 'Time of the last visit', 'wp-mon' ); ?>:</b> <?php echo $last_visitor['Time']; ?><br />
							<b><?php _e( 'The address of your last visitor', 'wp-mon' ); ?>:</b> <?php echo $last_visitor['VisitedUrl']; ?><br />
							<b><?php _e( 'The last address of your last visitor', 'wp-mon' ); ?>:</b> <?php echo $last_visitor['LastUrl']; ?><br />
						</div>
						<br>
						<?php
							
							if( isset( $_GET['action'] ) && $_GET['action'] == "clear-logs" ) WPMonVisitCounter::DeleteVisitors();
						
							$_VISITS = array();
							$_COLS = array( 'ID' => __( 'Log-ID', 'wp-mon' ), 
											'IP'    => __( 'IP', 'wp-mon' ), 
											'Time'  => __( 'Date and Time', 'wp-mon' ),  
											'VisitedUrl'   => __( 'Requested address', 'wp-mon' ),  
											'LastURL'   => __( 'Last address', 'wp-mon' ),  
											'UserAgent'   => __( 'User-Agent', 'wp-mon' ) );
							
							foreach( WPMonVisitCounter::GetVisitors() as $item_key => $item_value )
							{							
								$print_item = false;
								$_item = array( );
								
								foreach( $item_value as $key => $value )
								{			
									if ( isset( $_POST['search'] ) && $_POST['search'] != "" )
									{
										if( stripos( $key, $_POST['search'] ) !== false || stripos( $value, $_POST['search'] ) !== false )
										{	
											$print_item = true;	
										}	
										
										if( $print_item ) 
										{
											$_item[$key] = $value;
										}
									}
									else
									{		
										$_item[$key] = $value;
									}									
								}
								
								if( !empty( $_item ) ) $_VISITS[] = $_item;
								
							}
							
							$visits_list = new WPMonListTable( $_VISITS, $_COLS );
							$visits_list->prepare_items();
						?>
						<form method="POST">
							<p>								
								<label class="screen-reader-text" for="wpmon-header-search-input"><?php _e( 'Search', 'wp-mon' ); ?>:</label> 
								<input id="wpmon-header-search-input" type="text" name="search" value="<?php if( isset($_POST['search']) ) { echo $_POST['search']; } else { echo ""; } ?>" /> 
								<input id="wpmon-header-search-button" class="button" type="submit" name="" value="<?php _e( 'Search', 'wp-mon' ); ?>" />
							</p>
						</form>
						<?php
							$visits_list->display();
						?>
						<form method="POST">
							<p>								
								<label class="screen-reader-text" for="wpmon-header-search-input"><?php _e( 'Search', 'wp-mon' ); ?>:</label> 
								<input id="wpmon-header-search-input" type="text" name="search" value="<?php if( isset($_POST['search']) ) { echo $_POST['search']; } else { echo ""; } ?>" /> 
								<input id="wpmon-header-search-button" class="button" type="submit" name="" value="<?php _e( 'Search', 'wp-mon' ); ?>" />
							</p>
						</form>
					</div>
				</div>	
			
			<?php
		}

		public static function WPAdminHeaderHTML()
		{
		?>
		<!-- ============= -->
		<!-- WP-Mon #BEGIN -->
		<!-- ============= -->
		
		<!-- ============= -->
		<!--  WP-Mon #END  -->
		<!-- ============= -->
		<?php
		}
		
		public static function GetPosition()
		{
			return 2;
		}
		
		public static function init() {
			if( get_option( 'wpmon_module_visits' ) == "1" ) 
				add_submenu_page('wp-mon', __('Visits', 'wp-mon'), __('Visits', 'wp-mon'), 'administrator', "wp-mon/visits", array( 'WPMonPageVisits', 'WPMonPageVisits_Init' ) );
		}

	}
	
?>