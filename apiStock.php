<?php 
	include('inc/config.php');
	$acc = isset($_GET['acc'])? $_GET['acc'] : '';

	$arrOut = array();  //array de salida.

	switch ($acc) {
		case 'getstocks':
			//obtiene stock con respectivas sucursales y productos dependiendo de los parámetros entregados:

			if(isset($_GET['idSucursal']) AND isset($_GET['idProducto'])){
				$otrosParametros = " WHERE sk_id_sucursal=".$_GET['idSucursal']." AND sk_id_producto=".$_GET['idProducto'];			
			}else{
				if(isset($_GET['idSucursal'])){
					$idSucursal = " WHERE sk_id_sucursal = ".$_GET['idSucursal'];
				}else{
					$idSucursal = "";
				}

				if(isset($_GET['idProducto'])){
					$idProducto = "WHERE sk_id_producto = ".$_GET['idProducto'];
				}else{
					$idProducto = "";
				}
				$otrosParametros = $idProducto.$idSucursal;
			}
			
			$statement = $pdo->prepare("SELECT * FROM tbl_stock AS t1 INNER JOIN tbl_sucursales AS t2 ON t1.sk_id_sucursal = t2.s_id ".$otrosParametros);
			$statement->execute();
			$results = $statement->fetchAll(PDO::FETCH_ASSOC);
			foreach($results as $row){
				array_push($arrOut, $row);
			}
			break;
		
		case 'addstock':
			//añade stock a un producto (se debe pasar por parámetro el idProducto e idSucursal);
			$valid = 1;

			$idSucursal = isset($_GET['idSucursal'])? $_GET['idSucursal'] : -1;
			$idProducto = isset($_GET['idProducto'])? $_GET['idProducto'] : -1;
			$cantStock = isset($_GET['stock'])? intval($_GET['stock']) : 0;
			
			//chequeamos si existe el producto: (para saber si debemos hacer update o insert)
			$statement = $pdo->prepare("SELECT * FROM `tbl_stock` WHERE `sk_id_producto` = ? AND `sk_id_sucursal` = ?");
			$statement->execute(array($idProducto, $idSucursal));
			$cant = $statement->rowCount();
			
			if($cant>0){
				//si existe hago un update:
				$results = $statement->fetchAll(PDO::FETCH_ASSOC);
				foreach($results as $row){
					$idStock = $row['sk_id'];
					$stockAnterior = intval($row['sk_stock']);
				}
				
				$stockTotal = $stockAnterior + $cantStock;
				$statement = $pdo->prepare("UPDATE `tbl_stock` SET `sk_id_producto` = ?, `sk_id_sucursal` = ?, `sk_stock` = ? WHERE `sk_id` = ?;");
				$statement->execute(array($idProducto, $idSucursal, $stockTotal, $idStock));



			}else{
				//si no existe inserto:
				$statement = $pdo->prepare("INSERT INTO tbl_stock (sk_id_producto, sk_id_sucursal, sk_stock) VALUES (?,?,?);");
				$statement->execute(array($idProducto, $idSucursal, $cantStock));

			}


			break;
		
		default:
			# code...
			break;
	}

	echo json_encode($arrOut);
 ?>