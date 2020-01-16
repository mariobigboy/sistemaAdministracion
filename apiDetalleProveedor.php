<?php 
	include('inc/config.php');
	$acc = isset($_POST['acc'])? $_POST['acc'] : '';
	header('Content-type: text/json');

	$arrOut = array();  //array de salida.

	switch ($acc) {
		case 'get':
			//obtiene stock con respectivas sucursales y productos dependiendo de los parámetros entregados:
			/*$statement = $pdo->prepare("SELECT * FROM tbl_stock AS t1 INNER JOIN tbl_sucursales AS t2 ON t1.sk_id_sucursal = t2.s_id ".$otrosParametros);
			$statement->execute();
			$results = $statement->fetchAll(PDO::FETCH_ASSOC);
			foreach($results as $row){
				array_push($arrOut, $row);
			}*/
			echo '{"success": true}';
			break;
		case 'set':
			//guardamos detalles de la cuenta del proveedor:
			if(isset($_POST)){
				//variables que nos llegan:
				$idProveedor = isset($_POST['idProveedor'])? $_POST['idProveedor'] : '';
				//$idCuenta = isset($_POST['idCuenta'])? $_POST['idCuenta'] : '-1';
				$fecha_time = time();
				$fecha = isset($_POST['fecha'])? $_POST['fecha'] : '';
				$descripcion = isset($_POST['descripcion'])? $_POST['descripcion'] : '';
				$cant = isset($_POST['cant'])? $_POST['cant'] : '0';
				$precioUnitario = isset($_POST['precioUnitario'])? $_POST['precioUnitario'] : '0.0';
				$pago = isset($_POST['pago'])? $_POST['pago'] : '0.0';

				session_start();
				$usuario = $_SESSION['user']['full_name'];

				// if($idCuenta == -1){
				// 	$statNuevaCuenta = $pdo->prepare("INSERT INTO `cuentasProveedores`(
				// 						`id`, 
				// 						`idProveedor`, 
				// 						`factura`, 
				// 						`detalle`, 
				// 						`fecha_factura`, 
				// 						`monto`, 
				// 						`fecha`, 
				// 						`usuario`, 
				// 						`tipoProveedor`, 
				// 						`estado`) 
				// 						VALUES (NULL,?,?,?,?,?,?,?,?,?);");
				// 	$statNuevaCuenta->execute(array(
				// 						$idProveedor,
				// 						$descripcion,
				// 						'',
				// 						$fecha,
				// 						($cant * $precioUnitario),
				// 						$fecha_time,
				// 						$usuario,
				// 						'1',
				// 						'0'
				// 					));
				// 	$idCuenta = $pdo->lastInsertId();
				// }

				//insertamos en base de datos:
				$statement = $pdo->prepare("INSERT INTO `detalleProveedores`(`id`, `idProveedor`, `usuario`, `pago`, `fecha`, `fechaPicker`, `descripcion`, `cant`, `precio`) VALUES (NULL, ?, ?, ?, ?, ?, ?, ?, ?)");
				$statement->execute(array($idProveedor, $usuario, $pago, $fecha_time, $fecha, $descripcion, $cant, $precioUnitario));


				echo '{"success": true}';
			}else{
				echo '{"success": false, "error": "no hay datos post."}';
			}

			break;
		default:
			# code...
			echo '{"success": false, "error": "No seleccionó opción."}';
			break;
	}

	//echo json_encode($arrOut);
 ?>