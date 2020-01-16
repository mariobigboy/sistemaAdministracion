<?php 
	include("inc/config.php");

	$idProduct = isset($_GET['id'])? $_GET['id'] : -1;

	if($idProduct != -1){
		$statement = $pdo->prepare("SELECT * FROM `tbl_product` as t1 LEFT JOIN `tbl_sucursales` as t2 ON t1.p_sucursal_id = t2.s_id LEFT JOIN tbl_marcas as t3 ON t3.id = t1.p_brand WHERE t1.p_id = ?;");
		$statement->execute(array($idProduct));
		$results = $statement->fetchAll(PDO::FETCH_ASSOC);
		$arrOut = array();
		foreach($results as $row){
			array_push($arrOut, $row);
		}

		$statStock = $pdo->prepare("SELECT t1.sk_stock, t2.s_name FROM tbl_stock as t1 INNER JOIN tbl_sucursales as t2 ON t1.sk_id_sucursal = t2.s_id WHERE t1.sk_id_producto = ?;");
		$statStock->execute(array($idProduct));
		$resultStock = $statStock->fetchAll(PDO::FETCH_ASSOC);
		$arrStock = array();

		foreach($resultStock as $stk){
			array_push($arrStock, $stk);
		}
		array_push($arrOut, ["stock" => $arrStock]);
		echo json_encode($arrOut);
	}else{
		echo '{"error": "Falta id del producto."}';
	}

	header('Content-type: text/json');
?>