<?php

	header( 'Content-Type: ' . $_GET['type'] );
	header( 'Content-Disposition: attachment; filename="' . $_GET['name'] . '"' );
	readfile( $_GET['path'] . DIRECTORY_SEPARATOR . $_GET['name'] );

?>