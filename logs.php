<?php 

	//inserto en log
		//Guarda Stock actual en la sucursal
		//ALTA
		//BAJA
		//MODIFICACION
		//TRASPASO
		//LOGIN
		//LOGOUT
		//ABRIR CAJA
		//CERRAR CAJA

// function logs($detalle, $user){
// 	require_once('inc/config.php');

		
// 		$id_usuario = $user['id'];
// 		$id_sucursal = $user['sucursal'];
// 		$detalle = $detalle;
// 		$fecha = time();
// 		$statement = $pdo->prepare("INSERT INTO tbl_logs (
// 										id_usuario,
// 										id_sucursal,
// 										id_producto,
// 										detalle,
// 										fecha) VALUES (?,?,?,?,?)");
// 		$statement->execute(array(
// 										$id_usuario,
// 										$id_sucursal,
// 										$idProducto,
// 										$detalle,
// 										$fecha
										
// 									));
// }

function logs($detalle, $user, $id){
	//require_once('inc/config.php');

		
		$id_usuario = $user['id'];
		$id_sucursal = $user['sucursal'];
		$detalle = $detalle;
		$fecha = time();
		$statement = $pdo->prepare("INSERT INTO tbl_logs (
										id_usuario,
										id_sucursal,
										id_producto,
										detalle,
										fecha) VALUES (?,?,?,?,?)");
		$statement->execute(array(
										$id_usuario,
										$id_sucursal,
										$id,
										$detalle,
										$fecha
										
									));
}

 ?>