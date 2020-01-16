<?php

	include("inc/config.php");
	header('Content-type: text/json'); //defino formato de documento JSON
	
	$id = (isset($_POST['id']))? $_POST['id'] : '';
	

	$arrOut = array();
	if($id!=''){
		$statement = $pdo->prepare("SELECT p.p_id, p.p_name, p.p_code, p.p_current_price, p.p_qty, p.p_featured_photo, sc.s_name, s.sk_stock 							FROM tbl_product as p
									  INNER JOIN tbl_stock AS s ON p.p_id = s.sk_id_producto
									  INNER JOIN tbl_sucursales as sc ON s.sk_id_sucursal = sc.s_id WHERE p.p_id = ? ");
		$statement->execute(array($id));
		$results = $statement->fetchAll(PDO::FETCH_ASSOC);
		foreach($results as $row){
			array_push($arrOut, $row);
		}
      
	}
    
    echo json_encode($arrOut);
?>