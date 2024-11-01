<?php

	class WPMonSqlAdmin
	{
	
		private $dbHost = "";
		private $dbUser = "";
		private $dbPass = "";
		private $dbName = "";
		
		private $mysql;
						
		public function __construct( $host, $user, $pass, $name )
		{					
			$this->dbHost = $host;
			$this->dbUser = $user;
			$this->dbPass = $pass;
			$this->dbName = $name;

			$this->mysql = mysqli_connect( $host, $user, $pass, $name );		
		}			
						
		public function DoQuery( $query )
		{
			return mysqli_query( $this->mysql, $query );
		}	
						
		public function GetLastError()
		{
			return mysqli_error( $this->mysql );
		}
		
		public function GetTables()
		{
			$result = array( );
			
			$query = mysqli_query( $this->mysql, "SHOW TABLES FROM " . $this->dbName );
			if( $query == false ) return false;
				
			while ( $table = mysqli_fetch_row( $query ) ) 
			{
				$result[] = $table;
			}				
				
			mysqli_free_result( $query );			
			return $result;
		}	
		
		public function GetTableStructure( $table )
		{
			$result = array( );
			
			$query = mysqli_query( $this->mysql, "SELECT COLUMN_NAME FROM INFORMATION_SCHEMA.COLUMNS WHERE TABLE_SCHEMA = '". $this->dbName . "' AND TABLE_NAME = '" . $table . "';" );
			if( $query == false) return false;
				
			while ( $table = mysqli_fetch_row( $query ) ) 
			{
				$result[] = $table[0];
			}
								
			mysqli_free_result( $query );			
			return $result;
		}	
		
		public function GetTableEntries( $table )
		{
			$result = array( );
			
			$query = mysqli_query( $this->mysql, "SELECT * FROM " . $table );
			if( $query == false ) return false;
			
			$count = 0;
			while ( $row = mysqli_fetch_array( $query ) ) 
			{
				foreach ($row as $key => $value)
				{
					$result[$count][$key] = $value;
				}
				$count++;
			}
								
			mysqli_free_result( $query );			
			return $result;			
		}
			
		public function QuerySelectTable( $query, $table )
		{
			$result = array( );
			
			$query = mysqli_query( $this->mysql, "SELECT " . $query . " FROM " . $table );
			if( $query == false ) return false;
			
			$count = 0;
			while ( $row = mysqli_fetch_array( $query ) ) 
			{
				foreach ($row as $key => $value)
				{
					$result[$count][$key] = $value;
				}
				$count++;
			}
								
			mysqli_free_result( $query );			
			return $result;			
		}
		
	}

?>