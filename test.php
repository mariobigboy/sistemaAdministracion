<?php 
	//$tiempo = time();
	//echo "tiempo: ".$tiempo." - date: ".date('m/d/y', $tiempo)."\n";
	//echo "tiempo +1 day: ".strtotime('+1 day', $tiempo)." - date: ".date('m/d/y -03:00', $tiempo)."\n";
	//session_start();
	//print_r($_SESSION);

	// include('inc/config.php');
	// $stat = $pdo->prepare("SELECT * FROM `tbl_comodato` WHERE fecha_limite is NULL");
	// $stat->execute(array(NULL));
	// $res = $stat->fetchAll(PDO::FETCH_ASSOC);
	// print_r($res);
	session_start();
	print_r ($_SESSION['user']);
			
?>