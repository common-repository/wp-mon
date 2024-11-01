<?php

	/*
	 * @author Lukas Berger
	 * @package WP-Mon
	 * @version 0.5.1
	*/
	class WPMonVisitCounter
	{
	
		public static function Initialize()
		{
			global $wpmon;
			
			if( get_option( "wpmon_module_visits" ) == 1 )
			{
				add_action( 'wp_footer', array( 'WPVisitCounter', 'AddVisit' ) );			
			}
			
			$installeddbversion = get_option( "wpmon_installed_dbversion" );
			if( $installeddbversion != $wpmon->current_db_version ) {
				global $wpdb;
				
				$wpmon_visits_name = $wpdb->prefix . "wpmon_visits";
				$wpmon_visits_sql = "CREATE TABLE `{$wpmon_visits_name}` (
						  `ID` int(11) NOT NULL,
						  `IP` text NOT NULL,
						  `Time` text NOT NULL,
						  `VisitedUrl` text NOT NULL,
						  `RefererUrl` text NOT NULL,
						  `UserAgent` text NOT NULL,
						  UNIQUE KEY `ID` (`ID`)
						) ENGINE=MyISAM DEFAULT CHARSET=latin1;";
				
				require_once( ABSPATH . 'wp-admin/includes/upgrade.php' );
				dbDelta( $wpmon_visits_sql );
						
				update_option( "wpmon_installed_dbversion", $wpmon->current_db_version );				
			}
		}
		
		public static function GetLastVisitor( $return_ip = true, $return_time = true, $return_url = true, $return_lasturl = true, $return_useragent = true  )
		{
			global $wpdb;
				
			$table = $wpdb->prefix . "wpmon_visits";		
			require_once( ABSPATH . "wp-config.php" );	
			$mysql = mysqli_connect( DB_HOST, DB_USER, DB_PASSWORD, DB_NAME );
			
			$all_logs = mysqli_query( $mysql, "SELECT * FROM `{$table}` ORDER BY ID DESC LIMIT 0,1" );
			
			$id = "";
			$ip = "";
			$time = "";
			$url = "";
			$lasturl = "";
			$useragent = "";
			
			while( $row = mysqli_fetch_array( $all_logs ) ) 
			{ 
				$id = $row['ID'];
				$ip = $row['IP'];
				$time = $row['Time'];
				$url = $row['VisitedUrl'];
				$lasturl = $row['RefererUrl'];
				$useragent = $row['UserAgent'];
			}
			
			$ret = array();
			$ret['ID'] = $id;
			if( $return_ip == true ) $ret['IP'] = $ip;
			if( $return_time == true ) $ret['Time'] = $time;
			if( $return_url == true ) $ret['VisitedUrl'] = $url;
			if( $return_lasturl == true ) $ret['LastUrl'] = $lasturl;
			if( $return_useragent == true ) $ret['UserAgent'] = $useragent;
			return $ret;
		}
		
		public static function GetVisitors()
		{
			global $wpdb;
				
			$table = $wpdb->prefix . "wpmon_visits";		
			require_once( ABSPATH . "wp-config.php" );	
			$mysql = mysqli_connect( DB_HOST, DB_USER, DB_PASSWORD, DB_NAME );
			
			$all_logs = mysqli_query( $mysql, "SELECT * FROM `{$table}`" );
			
			$visitors = array();
			
			while( $row = mysqli_fetch_array( $all_logs ) ) 
			{ 
				$visitors[$row['ID']]['ID'] = $row['ID'];
				$visitors[$row['ID']]['IP'] = $row['IP'];
				$visitors[$row['ID']]['Time'] = $row['Time'];
				$visitors[$row['ID']]['VisitedUrl'] = $row['VisitedUrl'];
				$visitors[$row['ID']]['LastUrl'] = $row['RefererUrl'];
				$visitors[$row['ID']]['UserAgent'] = $row['UserAgent'];
			}
			
			return $visitors;
		}
		
		public static function DeleteVisitors()
		{
			global $wpdb;
				
			$table = $wpdb->prefix . "wpmon_visits";		
			require_once( ABSPATH . "wp-config.php" );	
			$mysql = mysqli_connect( DB_HOST, DB_USER, DB_PASSWORD, DB_NAME );
			
			mysqli_query( $mysql, "TRUNCATE `{$table}`" );
		}
		
		public static function AddVisit()
		{						
			global $wpdb;
			
			$table = $wpdb->prefix . "wpmon_visits";		
			require_once( ABSPATH . "wp-config.php" );	
			$mysql = mysqli_connect( DB_HOST, DB_USER, DB_PASSWORD, DB_NAME );
				
			$all_logs = mysqli_query( $mysql, "SELECT * FROM `{$table}`" );
				
			$id = 0;
			while( $row = mysqli_fetch_array( $all_logs ) ) { $id++; }
				
			$ip = html_entity_decode($_SERVER['REMOTE_ADDR']);
			$currtime = html_entity_decode(date( "d.m.Y G:H:s" ));
			$currurl = html_entity_decode(get_bloginfo( 'home' ) . $_SERVER['REQUEST_URI']);	
			$lasturl = html_entity_decode($_SERVER['HTTP_REFERER']);			
			$useragent = html_entity_decode($_SERVER['HTTP_USER_AGENT']);			
							
			mysqli_query( $mysql, "INSERT INTO `{$table}`(`ID`, `IP`, `Time`, `VisitedUrl`, `RefererUrl`, `UserAgent`) VALUES ({$id},'{$ip}','{$currtime}','{$currurl}','{$lasturl}','{$useragent}')" );
		
		}
	
	}

?>