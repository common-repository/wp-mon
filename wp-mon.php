<?php
	
	/*
	 Plugin Name:  WP-Mon - Monitoring, Visitor-Logging & File-Browsing
	 Text Domain:  wp-mon
	 Plugin URI:   http://lukasberger.at
	 Description:  Show your system infos, get statistics about your WordPress, see more infos about your visitors and navigate through your wordpress files
	 Version:      0.5.1
	 Author:       Lukas Berger
	 Author URI:   http://lukasberger.at
	 License:      GPLv3/Custom
	*/
		
	session_start();
	session_cache_limiter(60);

	define( 'WPMON_VERSION', '0.5.1' );
	define( 'WPMON_PLUGIN_DIR',  plugin_dir_path(dirname( __FILE__ )) . 'wp-mon' . DIRECTORY_SEPARATOR );
	define( 'WPMON_PLUGIN_URL',  plugins_url( '', __FILE__ ) );
	
	if( get_option('wpmon_use_exception_handler') == "1" ) set_exception_handler( 'WPMonExceptionHandler' );
	if( get_option('wpmon_use_error_handler') == "1" ) set_error_handler( 'WPMonErrorHandler' );
	
	require_once( WPMON_PLUGIN_DIR . 'wp-mon-loader.php' );		
	
	new WPMonLoader();
	
	/*
	 * @author Lukas Berger
	 * @package WP-Mon
	 * @version 0.5.1
	*/
	function WPMonExceptionHandler($exception)
	{
		?>
		<div id="wpmon-exception" class="error">
			<p>
				<b><?php _e( 'An exception occured in', 'wp-mon' ); ?> <?php echo $exception->getFile(); ?> <?php _e( 'at line', 'wp-mon' ); ?> <?php echo $exception->getLine(); ?>:</b><br />
				<i><?php echo nl2br( $exception->getMessage() ); ?></i>
			</p>
		</div>
		<?php
	}
					
	/*
	 * @author Lukas Berger
	 * @package WP-Mon
	 * @version 0.5.1
	*/
	function WPMonErrorHandler( $errno, $errstr, $errfile, $errline, $errcontext )
	{
		?>
		<div id="wpmon-exception" class="error">
			<p>
				<b><?php _e( 'An error occured in', 'wp-mon' ); ?> <?php echo $errfile; ?> <?php _e( 'at line', 'wp-mon' ); ?> <?php echo $errline; ?>:</b><br />
				<i><?php echo nl2br( $errstr ); ?></i>
			</p>
		</div>
		<?php
	}

	
?>