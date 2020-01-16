<?php 
	include("inc/config.php");

	$idProduct = isset($_GET['id'])? $_GET['id'] : -1;
	$idSucursal = isset($_GET['s'])? $_GET['s'] : -1;

	if($idProduct != -1){
		$statement = $pdo->prepare("SELECT * FROM `tbl_product` as t1 LEFT JOIN `tbl_stock` as t2 ON t1.p_id = t2.sk_id_producto WHERE t1.p_code = ? AND t2.sk_id_sucursal = ?;");
		$statement->execute(array($idProduct,$idSucursal));
		$results = $statement->fetchAll(PDO::FETCH_ASSOC);
		$arrOut = array();
		foreach($results as $row){
			array_push($arrOut, $row);
		}

		//$statement = $pdo->prepare();
		echo json_encode($arrOut);
	}else{
		echo '{"error": "Falta id del producto."}';
	}

	header('Content-type: text/json');
?>