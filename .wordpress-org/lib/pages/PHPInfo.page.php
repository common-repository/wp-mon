<?php

	/*
	 * @author Lukas Berger
	 * @package WP-Mon
	 * @version 0.5.1
	*/
	class WPMonPagePHPInfo
	{

		public static function WPMonPagePHPInfo_Init() {	
			add_action( 'admin_footer_text', array( 'WPMonLoader', 'WPMonAdminFooter' ) );	
	
			?>			
				<div id="wp-mon-phpinfo-page">
					<div class="wrap">
						<h1>
							<?php _e( 'PHP-Infos', 'wp-mon' ); ?>
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
						<?php
						
							$phpinfo = array ();
							$ids = 0;
							$results = 0;
							
							foreach( WPMonPagePHPInfo::GetAllInfos() as $group => $item )
							{
								foreach( $item as $key => $value )
								{								
									if ( isset( $_POST['search'] ) && $_POST['search'] != "" )
									{
										if( is_array( $value ) == false )
										{	
											if( stripos( $group, $_POST['search'] ) !== false || stripos( $key, $_POST['search'] ) !== false || stripos( $value, $_POST['search'] ) !== false )
											{																	
												$phpinfo[] = array ( 
													'Group' => $group, 
													'Name' => $key,
													'Value' => $value
												);	
											}
										}
										else
										{
											if( stripos( $group, $_POST['search'] ) !== false || stripos( $key, $_POST['search'] ) !== false )
											{																	
												$phpinfo[] = array ( 
													'Group' => $group, 
													'Name' => $key,
													'Value' => $value
												);	
											}
										}
									}
									else
									{									
										$phpinfo[] = array ( 
											'Group' => $group, 
											'Name' => $key,
											'Value' => $value
										);	
									}
								}
							}
				
							$phpinfo_list = new WPMonListTable( $phpinfo, array ( 'Group' => __( 'Group', 'wp-mon' ), 'Name' => __( 'Name', 'wp-mon'), 'Value' => __( 'Value', 'wp-mon') ) );						
							$phpinfo_list->prepare_items(); 
							?>
							<form method="POST">
								<p>								
									<label class="screen-reader-text" for="wpmon-header-search-input"><?php _e( 'Search', 'wp-mon' ); ?>:</label> 
									<input id="wpmon-header-search-input" type="text" name="search" value="<?php if( isset($_POST['search']) ) { echo $_POST['search']; } else { echo ""; } ?>" /> 
									<input id="wpmon-header-search-button" class="button" type="submit" name="" value="<?php _e( 'Search', 'wp-mon' ); ?>" />
								</p>
							</form>
							<?php
							$phpinfo_list->display(); 
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
		
		public static function GetAllInfos($return = true){
		
			ob_start();
			phpinfo(-1);
			 
			$pi = preg_replace(
			array('#^.*<body>(.*)</body>.*$#ms', '#<h2>PHP License</h2>.*$#ms',
				'#<h1>Configuration</h1>#',  "#\r?\n#", "#</(h1|h2|h3|tr)>#", '# +<#',
				"#[ \t]+#", '#&nbsp;#', '#  +#', '# class=".*?"#', '%&#039;%',
				'#<tr>(?:.*?)" src="(?:.*?)=(.*?)" alt="PHP Logo" /></a>'
				.'<h1>PHP Version (.*?)</h1>(?:\n+?)</td></tr>#',
				'#<h1><a href="(?:.*?)\?=(.*?)">PHP Credits</a></h1>#',
				'#<tr>(?:.*?)" src="(?:.*?)=(.*?)"(?:.*?)Zend Engine (.*?),(?:.*?)</tr>#',
				"# +#", '#<tr>#', '#</tr>#'),
			array('$1', '', '', '', '</$1>' . "\n", '<', ' ', ' ', ' ', '', ' ',
				'<h2>PHP Configuration</h2>'."\n".'<tr><td>PHP Version</td><td>$2</td></tr>'.
				"\n".'<tr><td>PHP Egg</td><td>$1</td></tr>',
				'<tr><td>PHP Credits Egg</td><td>$1</td></tr>',
				'<tr><td>Zend Engine</td><td>$2</td></tr>' . "\n" .
				'<tr><td>Zend Egg</td><td>$1</td></tr>', ' ', '%S%', '%E%'),
			ob_get_clean());

			$sections = explode('<h2>', strip_tags($pi, '<h2><th><td>'));
			unset($sections[0]);

			$pi = array();
			foreach($sections as $section){
				$n = substr($section, 0, strpos($section, '</h2>'));
				preg_match_all( '#%S%(?:<td>(.*?)</td>)?(?:<td>(.*?)</td>)?(?:<td>(.*?)</td>)?%E%#', $section, $askapache, PREG_SET_ORDER);
				
				foreach($askapache as $m){
					if( count( $m ) > 2 )
					{
						$pi[$n][$m[1]] = ( !isset($m[3]) || $m[2] == $m[3] ) ? $m[2] : array_slice($m, 2);					
					} else {
						$pi[$n][$m[1]] = ( !isset($m[3]) || $m[2] == $m[3] ) ? $m[1] : array_slice($m, 2);					
					}
				}
				
			}

			return ($return === false) ? print_r($pi) : $pi;
		}

		public static function GetPosition()
		{
			return 1;
		}
		
		public static function init() {
			if( get_option( 'wpmon_module_phpinfos' ) == "1" ) 
				add_submenu_page('wp-mon', __('PHP-Info', 'wp-mon'), __('PHP-Info', 'wp-mon'), 'administrator', "wp-mon/php-info", array( 'WPMonPagePHPInfo', 'WPMonPagePHPInfo_Init' ) );
		}
		
	}
	
?>