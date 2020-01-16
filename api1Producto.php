<?php

	include("inc/config.php");
	header('Content-type: text/json'); //defino formato de documento JSON
	
	$q = (isset($_POST['q']))? $_POST['q'] : '';

	$arrOut = array();
	if($q!=''){
		
		$statement = $pdo->prepare("SELECT * FROM `tbl_product` WHERE `p_id` = '$q';");
		$statement->execute();
		$results = $statement->fetchAll(PDO::FETCH_ASSOC);
		foreach($results as $row){
			array_push($arrOut, $row);
		}
      
	}
    
    echo json_encode($arrOut);
?>