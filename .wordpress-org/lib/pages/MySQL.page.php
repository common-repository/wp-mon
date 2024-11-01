<?php

	/*
	 * @author Lukas Berger
	 * @package WP-Mon
	 * @version 0.5.1
	 * @license YOU ARE NOR ALLOWED TO CHANGE THIS SITE BECAUSE IT CONTAINS AUTHOR-CONTENT
	*/
	class WPMonPageMySQL
	{

		public static function WPMonPageMySQL_Init() {		
			add_action( 'admin_footer_text', array( 'WPMonLoader', 'WPMonAdminFooter' ) );
			
			?>			
			
			<style type="text/css">
			
				.wp-list-table {
					width: 100%;
				}
				
				.manage-column {
					width: auto !important;
				}
			
			</style>
			
			<div id="wp-mon-about-page">
				<div class="wrap">
						<h1><?php _e('MySQL'); ?></h1>
					
					<?php						
						if(get_option('wpmon_setup') == null || get_option('wpmon_setup') == false)
						{
							?>
							<div id='settings-error' class='update-nag'>
								WP-Mon: <?php _e("You must setup the plugin first", 'wp-mon'); ?>, <a href="./admin.php?page=wp-mon/settings"><?php _e( 'Change settings', 'wp-mon' ); ?></a>
							</div>				
							<?php
						}	
					
						$mysql_driver = new WPMonSqlAdmin( DB_HOST, DB_USER, DB_PASSWORD, DB_NAME );
						$al = admin_url( "admin.php?page=wp-mon/mysql" );
							
						if( isset( $_GET['action'] ) )
						{
								
							$action = $_GET['action'];
								
							if( $action == "truncate" )
							{
								$result = $mysql_driver->DoQuery( "TRUNCATE " . $_GET['table'] );
								?>
								<code>Result of "TRUNCATE <?php echo $_GET['table']; ?>": <?php if( $result == "" ) { echo $mysql_driver->GetLastError(); } else { echo "Success"; } ?></code>
								<h2><a href="<?php echo $al; ?>" class="add-new-h2"><?php _e( 'Go to overview', 'wp-mon' ); ?></a></h2>
								<?php
							}
							else if( $action == "delete" )
							{
								$result = $mysql_driver->DoQuery( "DROP TABLE " . $_GET['table'] );
								?>
								<code>Result of "DROP TABLE <?php echo $_GET['table']; ?>": <?php if( $result == "" ) { echo $mysql_driver->GetLastError(); } else { echo "Success"; } ?></code>
								<h2><a href="<?php echo $al; ?>" class="add-new-h2"><?php _e( 'Go to overview', 'wp-mon' ); ?></a></h2>
								<?php
							}
							else if( $action == "query" )
							{
								$result = $mysql_driver->DoQuery( $_POST['query-content'] );
								?>
								<code>Result of "<?php echo Utils::truncate( $_POST['query-content'], 100 ); ?>": <?php if( $result == "" ) { echo $mysql_driver->GetLastError(); } else { echo "Success"; } ?></code>
								<h2><a href="<?php echo $al; ?>" class="add-new-h2"><?php _e( 'Go to overview', 'wp-mon' ); ?></a></h2>
								<?php
							}
								
						}
						else
						{					
							$_COLS = array( );											
							$_ITEMS = array( );
																	
							if( isset( $_GET['table'] ) )
							{			
								$_COLS = $mysql_driver->GetTableStructure( $_GET['table'] );					
								$_MYSQL_ITEMS = $mysql_driver->GetTableEntries( $_GET['table'] );				
								
								if ( isset( $_POST['search'] ) && $_POST['search'] != "" )
								{
									$indexes = array( );
									foreach( $_MYSQL_ITEMS as $item )
									{						
										$index_add = array( );
										$add_the_item = false;
										foreach( $item as $key => $val )
										{
											if( stripos( $key, $_POST['search'] ) !== false || stripos( $val, $_POST['search'] ) !== false )
											{
												$add_the_item = true;
											}
										}		
										if( $add_the_item )
										{
											foreach( $item as $key => $val )
											{
												$index_add[$key] = $val;
											}
											$indexes[] = $index_add;										
										}										
									}					
																		
									if( !empty( $indexes ) )
									{
										foreach( $indexes as $index )
										{							
											$_item = array( );
											foreach( $index as $key => $val )
											{
												$_item[$key] = $val;
											}		
											$_ITEMS[] = $_item;											
										}
									}
								}
								else
								{
									foreach( $_MYSQL_ITEMS as $item )
									{									
										$_item = array( );
										foreach( $item as $key => $val )
										{
											$_item[$key] = $val;
										}		
										$_ITEMS[] = $_item;
									}
								}
							}
							else
							{
								$_COLS = array( 'Name' => __( 'Name', 'wp-mon' ), 'Actions' => __( 'Actions', 'wp-mon' ) );
								$_MYSQL_ITEMS = $mysql_driver->GetTables();
															
								if ( isset( $_POST['search'] ) && $_POST['search'] != "" )
								{
									$indexes = array( );
									foreach( $_MYSQL_ITEMS as $item )
									{						
										if( stripos( $item[0], $_POST['search'] ) !== false )
										{
											$indexes[] = $item;
										}										
									}					
																		
									if( !empty( $indexes ) )
									{
										foreach( $indexes as $index )
										{							
											$name = '<a href="' . $al . '&table=' . $index[0] . '" title="' . $index[0] . '">' . $index[0] . '</a>';
									
											$menu_actions = '<span class="file-browser-action-menu">';
											$menu_actions .= '<a href="' . $al . '&table=' . $index[0] .'&action=delete"><span class="fa fa-trash"> <span>' . __( 'Delete', 'wp-mon' ) . '</span></span>&nbsp;|&nbsp;</a>';	
											$menu_actions .= '<a href="' . $al . '&table=' . $index[0] .'&action=truncate"><span class="fa fa-cut"> <span>' . __( 'Truncate', 'wp-mon' ) . '</span></span></a>';					
											$menu_actions .= '</span>';
												
											$_ITEMS[] = array( 'Name' => $name, 'Actions' => $menu_actions );										
										}
									}
								}
								else
								{			
									foreach( $_MYSQL_ITEMS as $item )
									{		
										$name = '<a href="' . $al . '&table=' . $item[0] . '" title="' . $item[0] . '">' . $item[0] . '</a>';
								
										$menu_actions = '<span class="file-browser-action-menu">';
										$menu_actions .= '<a href="' . $al . '&table=' . $item[0] .'&action=delete"><span class="fa fa-trash"> <span>' . __( 'Delete', 'wp-mon' ) . '</span></span>&nbsp;|&nbsp;</a>';	
										$menu_actions .= '<a href="' . $al . '&table=' . $item[0] .'&action=truncate"><span class="fa fa-cut"> <span>' . __( 'Truncate', 'wp-mon' ) . '</span></span></a>';					
										$menu_actions .= '</span>';
											
										$_ITEMS[] = array( 'Name' => $name, 'Actions' => $menu_actions );
									}	
								}
										
							}
							
							if( isset( $_GET['table'] ) )
							{
								?>
								<h2><?php _e( 'Edit table', 'wp-mon' ); ?></h2>
								<h2>
									<a class="add-new-h2" href="<?php echo $al; ?>&table=<?php echo $_GET['table']; ?>&action=delete"><?php _e( 'Delete table', 'wp-mon' ); ?></a>
									<a class="add-new-h2" href="<?php echo $al; ?>&table=<?php echo $_GET['table']; ?>&action=truncate"><?php _e( 'Truncate table', 'wp-mon' ); ?></a>
									<br /><br />
									<form action="<?php echo $al; ?>&table=<?php echo $_GET['table']; ?>&action=query" method="POST">
										<textarea name="query-content" style="width: 100%; height: 200px"></textarea>
										<input type="submit" class="add-new-h2" value="<?php _e( 'Do Query', 'wp-mon' ); ?>" />
									</form>
								</h2>
								<?php
							}
							
							?><br /><?php
							
							$list = new WPMonListTable( $_ITEMS, $_COLS );
							$list->prepare_items();
							?>
							<form method="POST">
								<p>								
									<label class="screen-reader-text" for="wpmon-header-search-input"><?php _e( 'Search', 'wp-mon' ); ?>:</label> 
									<input id="wpmon-header-search-input" type="text" name="search" value="<?php if( isset($_POST['search']) ) { echo $_POST['search']; } else { echo ""; } ?>" /> 
									<input id="wpmon-header-search-button" class="button" type="submit" name="" value="<?php _e( 'Search', 'wp-mon' ); ?>" />
								</p>
							</form>
							<?php
								$list->display();
							?>
							<form method="POST">
								<p>								
									<label class="screen-reader-text" for="wpmon-header-search-input"><?php _e( 'Search', 'wp-mon' ); ?>:</label> 
									<input id="wpmon-header-search-input" type="text" name="search" value="<?php if( isset($_POST['search']) ) { echo $_POST['search']; } else { echo ""; } ?>" /> 
									<input id="wpmon-header-search-button" class="button" type="submit" name="" value="<?php _e( 'Search', 'wp-mon' ); ?>" />
								</p>
							</form>
							<?php
						
						}
						
					?>
					
				</div>	
			</div>
			
			<?php
		}
		
		public static function GetPosition()
		{
			return 51;
		}
		
		public static function init() {
			add_submenu_page('wp-mon', __('MySQL', 'wp-mon'), __('MySQL', 'wp-mon'), 'administrator', "wp-mon/mysql", array( 'WPMonPageMySQL', 'WPMonPageMySQL_Init' ) );
		}
		
	}
	
?>