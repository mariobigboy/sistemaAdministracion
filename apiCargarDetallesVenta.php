<?php 
include('inc/config.php');

$datos = $_POST;
	/*
	*/
	$tipoComprobante = isset($_POST['tipoComprobante'])? $_POST['tipoComprobante'] : '';
	$nameUsuario = isset($_POST['idUsuario'])? $_POST['idUsuario'] : '';
	$idCliente = isset($_POST['idCliente'])? $_POST['idCliente'] : '';
	$productos_array = json_decode(isset($_POST['lista'])? $_POST['lista'] : '[]');
	$totalVenta = isset($_POST['totalVenta'])? $_POST['totalVenta'] : '';
	$idSucursalVenta = isset($_POST['idSucursalVenta'])? $_POST['idSucursalVenta'] : '';
	$obs = isset($_POST['obs'])? $_POST['obs'] : '';

	$desc_gral = isset($_POST['descuento_gral'])? $_POST['descuento_gral'] : '';
	
	$metodo = isset($_POST['metodo'])? $_POST['metodo'] : '';
	$aCobrar = isset($_POST['aCobrar'])? $_POST['aCobrar'] : '';
	$interesVenta1 = isset($_POST['interesVenta1'])? $_POST['interesVenta1'] : '';
	$subt1 = isset($_POST['subt1'])? $_POST['subt1'] : '';
	
	$metodo1 = isset($_POST['metodo1'])? $_POST['metodo1'] : '';
	$aCobrar1 = isset($_POST['aCobrar1'])? $_POST['aCobrar1'] : '';
	$interesVenta2 = isset($_POST['interesVenta2'])? $_POST['interesVenta2'] : '';
	$subt2 = isset($_POST['subt2'])? $_POST['subt2'] : '';
	
	$metodo2 = isset($_POST['metodo2'])? $_POST['metodo2'] : '';
	$aCobrar2 = isset($_POST['aCobrar2'])? $_POST['aCobrar2'] : '';
	$interesVenta3 = isset($_POST['interesVenta3'])? $_POST['interesVenta3'] : '';
	$subt3 = isset($_POST['subt3'])? $_POST['subt3'] : '';

	$fecha = time();

	if ($tipoComprobante == '1') {
		//primero verificamos la disponibilidad:
		$disponibilidad = true;
		foreach($productos_array as $item){
			if ($item->codigo != "HD0001" && $item->codigo != "HD0002") {
				$idProducto = $item->idProducto;
				$cant = $item->cant;
				$consul = $pdo->prepare("SELECT * FROM `tbl_stock` where sk_id_producto = ? AND sk_id_sucursal = ? AND sk_stock >= ?");
				$consul->execute(array($idProducto, $idSucursalVenta, $cant));
				$conteo = $consul->rowCount();
				if($conteo<=0){
					$disponibilidad = false;
				}
			}
		}
		//fin verificacion disponibilidad.
		
		if($disponibilidad){
			$stat = $pdo->prepare("INSERT INTO factura (id_cliente, fecha, sucursal, usuario, total, descuento_gral, obs) VALUES (?,?,?,?,?,?,?);");
			$stat->execute(array(
				$idCliente,
				$fecha,
				$idSucursalVenta,
				$nameUsuario,
				$totalVenta,
				$desc_gral,
				$obs
			));
			$idFactura = $pdo->lastInsertId();

			// 0 PENDIENTE
			// 1 PAGADO
			$estado =  ($metodo == "4" || $metodo1 == "4" || $metodo2 == "4") ? 0 : 1;

			$statPago = $pdo->prepare("INSERT INTO pagos (
				id_factura,
				id_cliente,
				metodo1,
				interes1,
				subt1,
				metodo2,
				interes2,
				subt2,
				metodo3,
				interes3,
				subt3,
				estado
			) VALUES (?,?,?,?,?,?,?,?,?,?,?,?);");
			$statPago->execute(array(
				$idFactura,
				$idCliente,
				$metodo,
				$interesVenta1,
				$subt1,
				$metodo1,
				$interesVenta2,
				$subt2,
				$metodo2,
				$interesVenta3,
				$subt3,
				$estado
			));

			$totalCuentaCorriente = 0;
			if ($estado==0) {
				if ($metodo=='4') {
					$totalCuentaCorriente += $subt1;
				}
				if ($metodo1=='4') {
					$totalCuentaCorriente += $subt2;
				}
				if ($metodo2=='4') {
					$totalCuentaCorriente += $subt3;
				}

				$h_fecha = time();

				$creaCuenta = $pdo->prepare("INSERT INTO cuenta_corriente (
				id_cliente,
				id_factura,
				deuda,
				pago,
				tipo,
				fecha
				) VALUES (?,?,?,?,?,?);");
				$creaCuenta->execute(array(
					$idCliente,
					$idFactura,
					$totalCuentaCorriente,
					0,
					0,
					$h_fecha

				));
			}

			foreach($productos_array as $item){
				//obtengo producto como item:
				$dataId = $item->dataId;
				$codigo = $item->codigo;
				$idProducto = $item->idProducto;
				$descuento = $item->descuento;
				$cant = $item->cant;
				$nombre = $item->nombre;
				if ($codigo=="HD0002") {
					$precio = $item->precio * -1;
					$total = $item->total * -1;
				}else{
					$precio = $item->precio;
					$total = $item->total;
				}
				

				//insertamos en base de datos:
				$statDetalle = $pdo->prepare("INSERT INTO detalle (
					id_factura, 
					id_producto, 
					cantidad, 
					nombre,
					precio,
					descuento
				) VALUES (?,?,?,?,?,?);");
				$statDetalle->execute(array(
					$idFactura,
					$idProducto,
					$cant,
					$nombre,
					$precio,
					$descuento
				));

				if ($item->codigo != "HD0001" && $item->codigo != "HD0002") {
					//a la vez voy quitando stock:
					$consulStock = $pdo->prepare("SELECT * FROM `tbl_stock` WHERE sk_id_producto = ? AND sk_id_sucursal = ?;");
					$consulStock->execute(array($idProducto, $idSucursalVenta));
					$resultStock = $consulStock->fetchAll(PDO::FETCH_ASSOC);
					foreach($resultStock as $stk){
						$stock_actual = $stk['sk_stock'];
						$nuevoStock = intval($stock_actual) - intval($cant);
						$updateStock = $pdo->prepare("UPDATE `tbl_stock` SET sk_stock = ? WHERE sk_id_producto = ? AND sk_id_sucursal = ?;");
						$updateStock->execute(array($nuevoStock, $idProducto, $idSucursalVenta));
					}

					//Actualizo stock en tbl_product
					$statStk = $pdo->prepare("SELECT SUM(sk_stock) as stk_total FROM `tbl_stock` WHERE sk_id_producto = ?;");
					$statStk->execute(array($idProducto));
					$resultStk = $statStk->fetchAll(PDO::FETCH_ASSOC);
					foreach($resultStk as $stk2){
						$stk_total = $stk2['stk_total'];
						$updProd = $pdo->prepare("UPDATE tbl_product SET p_qty = ? WHERE p_id = ?;");
						$updProd->execute(array($stk_total, $idProducto));
					}

					//LOG en tbl_historia:
					//historia (BAJA):
					//guardo en historia: (BAJA)
					session_start();
					$h_detalle = 'BAJA';
					$h_obs = 'VENTA EN SUCURSAL';			
					$h_id_user = $_SESSION['user']['id'];
					$h_id_sucursal = $idSucursalVenta;
					$h_fecha = time();
					$h_stock_anterior = $stock_actual;
					$stock_actual = $nuevoStock;
					$statHistory = $pdo->prepare("INSERT INTO tbl_historia (h_id_producto, h_id_user, h_id_sucursal, h_stock_anterior, h_stock_actual, h_detalle, h_obs, h_fecha ) VALUES (?,?,?,?,?,?,?,?);");
					$statHistory->execute(array($idProducto, $h_id_user, $h_id_sucursal, $h_stock_anterior, $stock_actual, $h_detalle, $h_obs, $h_fecha));
					}

			} //end foreach()
			echo '{"success": "success"}';
		}else{
			echo '{"success": "error", "error": "without_stock"}';
		}
		
	}else{ //Presupuestos
		$stat = $pdo->prepare("INSERT INTO presupuesto (id_cliente, fecha, sucursal, usuario, total, obs) VALUES (?,?,?,?,?,?);");
		$stat->execute(array(
			$idCliente,
			$fecha,
			$idSucursalVenta,
			$nameUsuario,
			$totalVenta,
			$obs
		));
		$idPresupuesto = $pdo->lastInsertId();

		$statPago = $pdo->prepare("INSERT INTO pagos (
				id_presupuesto,
				id_cliente,
				metodo1,
				interes1,
				subt1,
				metodo2,
				interes2,
				subt2,
				metodo3,
				interes3,
				subt3,
				estado
			) VALUES (?,?,?,?,?,?,?,?,?,?,?,?);");
			$statPago->execute(array(
				$idPresupuesto,
				$idCliente,
				$metodo,
				$interesVenta1,
				$subt1,
				$metodo1,
				$interesVenta2,
				$subt2,
				$metodo2,
				$interesVenta3,
				$subt3,
				$estado
			));

		foreach($productos_array as $item){
			$dataId = $item->dataId;
			$codigo = $item->codigo;
			$idProducto = $item->idProducto;
			$descuento = $item->descuento;
			$cant = $item->cant;
			$nombre = $item->nombre;
			$precio = $item->precio;
			$total = $item->total;

			//insertamos en base de datos:
			$statDetalle = $pdo->prepare("INSERT INTO detalle (
				id_producto, 
				cantidad, 
				nombre,
				precio,
				descuento,
				id_presupuesto
			) VALUES (?,?,?,?,?,?);");
			$statDetalle->execute(array(
				$idProducto,
				$cant,
				$nombre,
				$precio,
				$descuento,
				$idPresupuesto
			));

		}
		echo '{"success": "success"}';

	}

	header('Content-Type: text/json');
	
	
	//var_dump($productos_array);


	/*
		array(1) {
		  [0]=>
			  object(stdClass)#2 (9) {
			    ["dataId"]=>
			    int(0)
			    ["id_Dom"]=>
			    string(3) "id0"
			    ["codigo"]=>
			    string(12) "HD1557784652"
			    ["idProducto"]=>
			    string(2) "83"
			    ["descuento"]=>
			    NULL
			    ["cant"]=>
			    int(1)
			    ["nombre"]=>
			    string(7) "Galleta"
			    ["precio"]=>
			    float(110.5)
			    ["total"]=>
			    float(110.5)
			  }
		}
	*/
?>