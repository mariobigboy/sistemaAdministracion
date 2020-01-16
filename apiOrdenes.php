<?php 
	header('Content-type: text/json');
	include('inc/configMario.php');
	$acc = isset($_POST['acc'])? $_POST['acc'] : '';

	switch ($acc) {
		case 'abrirOt':
			
			$idOrden = isset($_POST['idOrden'])? $_POST['idOrden'] : -1;
			$user = isset($_POST['user'])? $_POST['user'] : -1;
			if($idOrden != -1){
				$mensaje= "Recibido, Leido y Aceptado";
				$sql= "INSERT INTO `estadoPedido`(idPedido, estado, fecha,usuario, obs) VALUES ('$idOrden',1, UNIX_TIMESTAMP(),'$user', '$mensaje')";
				$cons= mysqli_query($con,$sql) or die (mysqli_error($con));
				
				$sql1 = "UPDATE `pedido` SET estado= 1 WHERE id= '$idOrden'";
				$cons1= mysqli_query($con,$sql1) or die (mysqli_error($con));
				
				if ($cons && $con) {
					echo '{"success": 1}';
				}else{
					echo '{"success": 0}';
				}
				
				
			}else{
				echo '{"success": 0}';
			}	

			
			break;

			case 'abrirOtCarpinteria':
			
			$idOrden = isset($_POST['idOrden'])? $_POST['idOrden'] : -1;
			$user = isset($_POST['user'])? $_POST['user'] : -1;
			if($idOrden != -1){
				$mensaje= "Recibido, Leido y Aceptado";
				$sql= "INSERT INTO `estadoPedido`(idCarpinteria, estado, fecha,usuario, obs) VALUES ('$idOrden',1, UNIX_TIMESTAMP(),'$user', '$mensaje')";
				$cons= mysqli_query($con,$sql) or die (mysqli_error($con));
				
				$sql1 = "UPDATE `carpinteria` SET estado= 1 WHERE id= '$idOrden'";
				$cons1= mysqli_query($con,$sql1) or die (mysqli_error($con));
				
				if ($cons && $con) {
					echo '{"success": 1}';
				}else{
					echo '{"success": 0}';
				}
				
				
			}else{
				echo '{"success": 0}';
			}	

			
			break;

			case 'modificarEstado':
			
			$idOrden = isset($_POST['idOrden'])? $_POST['idOrden'] : -1;
			$user = isset($_POST['user'])? $_POST['user'] : -1;
			$estado = isset($_POST['estado'])? $_POST['estado'] : -1;
			$obs = isset($_POST['obs'])? $_POST['obs'] : "";
			if($idOrden != -1){
				$sql= "INSERT INTO `estadoPedido`(idPedido, estado, fecha, usuario, obs) VALUES ('$idOrden','$estado', UNIX_TIMESTAMP(),'$user', '$obs')";
				$cons= mysqli_query($con,$sql) or die (mysqli_error($con));
				
				if ($estado != 6) {
					$sql1 = "UPDATE `pedido` SET estado= '$estado' WHERE id= '$idOrden'";
					$cons1= mysqli_query($con,$sql1) or die (mysqli_error($con));
				
				}
				if ($cons) {
					echo '{"success": 1}';
				}else{
					echo '{"success": 0}';
				}
				
				
			}else{
				echo '{"success": 0}';
			}	

			
			break;


			case 'modificarEstadoCarpinteria':
			
			$idOrden = isset($_POST['idOrden'])? $_POST['idOrden'] : -1;
			$user = isset($_POST['user'])? $_POST['user'] : -1;
			$estado = isset($_POST['estado'])? $_POST['estado'] : -1;
			$obs = isset($_POST['obs'])? $_POST['obs'] : "";
			if($idOrden != -1){
				$sql= "INSERT INTO `estadoPedido`(idCarpinteria, estado, fecha, usuario, obs) VALUES ('$idOrden','$estado', UNIX_TIMESTAMP(),'$user', '$obs')";
				$cons= mysqli_query($con,$sql) or die (mysqli_error($con));
				
				if ($estado != 6) {
					$sql1 = "UPDATE `carpinteria` SET estado= '$estado' WHERE id= '$idOrden'";
					$cons1= mysqli_query($con,$sql1) or die (mysqli_error($con));
				
				}
				if ($cons) {
					echo '{"success": 1}';
				}else{
					echo '{"success": 0}';
				}
				
				
			}else{
				echo '{"success": 0}';
			}	

			
			break;

		
		default:
			echo '{"error": "ninguna seleccion"}';
			break;
	}

	
 ?>

