<?php 
 $db_host = 'localhost';
   
   	// Database Name
	$db_name = 'sistemaDemo';

	// Database Username
	$db_user = 'root';

	// Database Password
	$db_pass = '';
	$con = mysqli_connect($db_host,$db_user,$db_pass,$db_name) or die("Problemas en la base de datos");
 	mysqli_set_charset( $con, 'utf8');
    mysqli_query($con,"SET NAMES 'utf8'");

 ?>