<?php

	include("inc/config.php");
	header('Content-type: text/json'); //defino formato de documento JSON
	
	$q = (isset($_GET['q']))? $_GET['q'] : '';

	$arrOut = array();
	if($q!=''){
		$limite = isset($_GET['limite'])? $_GET['limite'] : 10; 
		$statement = $pdo->prepare("SELECT * FROM `proveedores` WHERE `nombre` REGEXP '$q' OR `cuil` REGEXP '$q' OR `tel` REGEXP '$q' OR `correo` REGEXP '$q' LIMIT $limite;");
		$statement->execute();
		$results = $statement->fetchAll(PDO::FETCH_ASSOC);
		foreach($results as $row){
			array_push($arrOut, $row);
		}
      
	}
    
    echo json_encode($arrOut);
?>