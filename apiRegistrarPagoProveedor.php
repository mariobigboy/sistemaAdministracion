<?php 
	include('inc/config.php');

	//$idCliente = isset($_POST['idCliente']) ? $_POST['idCliente'] : "";
	$idCuenta = isset($_POST['idCuenta']) ? $_POST['idCuenta'] : "";
	$pago = isset($_POST['pago']) ? $_POST['pago'] : "";
	$usuario = isset($_POST['usuario']) ? $_POST['usuario'] : "";
	$formaPago = isset($_POST['metodoPago']) ? $_POST['metodoPago'] : "";
	$estado_cuenta = isset($_POST['estado_cuenta']) ? $_POST['estado_cuenta'] : "0";
	$id_proveedor = isset($_POST['id_proveedor']) ? $_POST['id_proveedor'] : "";
	$fecha = time();

	if ($idCuenta != "") {

			/*
				INSERT INTO `detalleProveedores`(`id`, `idCuenta`, `pago`, `formaPago`, `fecha`) VALUES ([value-1],[value-2],[value-3],[value-4],[value-5])
			*/

			
			$statement = $pdo->prepare("INSERT INTO `detalleProveedores`(
													`id`, 
													`idCuenta`, 
													`pago`, 
													`formaPago`, 
													`fecha`) VALUES (NULL,?,?,?,?)");
			$statement->execute(array(
				$idCuenta,
				$pago,
				$formaPago,
				$fecha
			));
		

			$statProveedor = $pdo->prepare("SELECT monto FROM `cuentasProveedores` WHERE id = ?;");
			$statProveedor->execute(array($idCuenta));
			$resProveedor = $statProveedor->fetchAll(PDO::FETCH_ASSOC);
			foreach($resProveedor as $rowProve){
				$monto = $rowProve['monto'];
			}

			$statDetalles = $pdo->prepare("SELECT SUM(`detalleProveedores`.pago) total FROM `detalleProveedores` WHERE idCuenta = ?;");
			$statDetalles->execute(array($idCuenta));
			$resDetalles = $statDetalles->fetchAll(PDO::FETCH_ASSOC);
			foreach($resDetalles as $rowDeta){
				$total_pagado = $rowDeta['total'];
			}
			
			if($total_pagado>=$monto){
				//update estado de cuenta:
				$statUpd = $pdo->prepare("UPDATE `cuentasProveedores` SET `estado`= ? WHERE `idProveedor`= ?;");
				$statUpd->execute(array('1', $id_proveedor));
			}

			echo '{"success": true}';
		

		
	}else{
		echo '{"success": false}';
	}
	header('Content-type: text/json');
	//header('Location: clientes-cuenta.php?id='.idCliente);

 ?>