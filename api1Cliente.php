<?php

	include("inc/config.php");
	header('Content-type: text/json'); //defino formato de documento JSON
	
	$q = (isset($_POST['q']))? $_POST['q'] : '';

	$arrOut = array();
	if($q!=''){
		$limite = isset($_POST['limite'])? $_POST['limite'] : 10; 
		$statement = $pdo->prepare("SELECT * FROM `tbl_cliente` WHERE `c_id` = '$q';");
		$statement->execute();
		$results = $statement->fetchAll(PDO::FETCH_ASSOC);
		foreach($results as $row){
			array_push($arrOut, $row);
		}
      
	}
    
    echo json_encode($arrOut);
?>