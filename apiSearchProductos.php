<?php

	include("inc/config.php");
	header('Content-type: text/json'); //defino formato de documento JSON
	
	$q = (isset($_POST['q']))? $_POST['q'] : '';
	$s = (isset($_POST['s']))? $_POST['s'] : '';

	$arrOut = array();
	if($q!=''){
		$limite = isset($_POST['limite'])? $_POST['limite'] : 20; 
		$statement = $pdo->prepare("SELECT * FROM tbl_product as tp INNER JOIN tbl_stock as ts ON tp.p_id = ts.sk_id_producto WHERE (tp.p_name REGEXP '$q' OR tp.p_code REGEXP '$q' OR tp.p_codebar REGEXP '$q') AND ts.sk_id_sucursal = '$s' LIMIT $limite;");
		$statement->execute();
		$results = $statement->fetchAll(PDO::FETCH_ASSOC);
		foreach($results as $row){
			array_push($arrOut, $row);
		}
      
	}
    
    echo json_encode($arrOut);
?>