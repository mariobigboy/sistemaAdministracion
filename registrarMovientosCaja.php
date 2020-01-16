<?php 
	include("inc/config.php");

	$usuario = isset($_POST['idUsuario']) ? $_POST['idUsuario'] : "";
	$sucursal = isset($_POST['sucursal']) ? $_POST['sucursal'] : "";
	$monto = isset($_POST['monto']) ? $_POST['monto'] : "";
	$movimiento = isset($_POST['movimiento']) ? $_POST['movimiento'] : "";
	$obs = isset($_POST['obs']) ? $_POST['obs'] : "";
	$fecha = time();

	if($usuario != ""){
		$insertar = $pdo->prepare("INSERT INTO cajaChica (
				monto,
				movimiento,
				idUsuario,
				idSucursal,
				obs,
				fecha
				) VALUES (?,?,?,?,?,?);");
				$insertar->execute(array(
					$monto,
					$movimiento,
					$usuario,
					$sucursal,
					$obs,
					$fecha

				));
		echo '{"success": true}';
	}else{
		echo '{"success": false}';
	}

	header('Content-type: text/json');
?>