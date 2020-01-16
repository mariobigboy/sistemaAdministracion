<?php 
	include('inc/config.php');
	header('Content-type: text/json');
	$acc = isset($_POST['acc'])? $_POST['acc'] : '';

	$arrOut = array();  //array de salida.

	switch ($acc) {
		case 'devolver':

			$id_sucursal = isset($_POST['id_sucursal'])? $_POST['id_sucursal'] : '';
			$nro_orden_comodato = isset($_POST['nro_orden_comodato'])? $_POST['nro_orden_comodato'] : '';

			if($id_sucursal!=''){
				if($nro_orden_comodato!=''){
					//devolver productos prestados a la sucursal elegida:
					$statComodato = $pdo->prepare("SELECT * FROM tbl_comodato WHERE orden = ? AND estado = ?;");
					$statComodato->execute(array($nro_orden_comodato, 'Prestado'));	
					$resultComodato = $statComodato->fetchAll(PDO::FETCH_ASSOC);
					$fecha_devolucion = time(); //fecha_devolucion
					$estado = 'Devuelto';

					//print_r($resultComodato);
					
					foreach($resultComodato as $row){
						// sk_id
						// sk_id_producto
						// sk_id_sucursal
						// sk_stock
						$id_row = $row['id'];
						$id_producto = $row['id_producto'];
						$cantidad = $row['cantidad'];

						if($id_producto>0){
							//obtengo el stock anterior para poder sumarle la cantidad devuelta:
							$statPrevStock = $pdo->prepare("SELECT * FROM tbl_stock WHERE sk_id_producto = ? AND sk_id_sucursal = ?;");
							$statPrevStock->execute(array($id_producto, $id_sucursal));
							$resultPrevStk = $statPrevStock->fetchAll(PDO::FETCH_ASSOC);
							
							foreach($resultPrevStk as $rowPrevStk){
								$sk_id = $rowPrevStk['sk_id'];
								$sk_stock = $rowPrevStk['sk_stock'];
							}

							//devolvemos el stock:
							$new_stock = $cantidad + $sk_stock;
							
							$statUpdStk = $pdo->prepare("UPDATE tbl_stock SET sk_stock = ? WHERE sk_id = ?;");
							$statUpdStk->execute(array($new_stock, $sk_id));


							//log:
							session_start();
							$h_id_user = $_SESSION['user']['id'];
							$h_id_sucursal = $id_sucursal;//$_SESSION['user']['sucursal'];
							$h_fecha = $fecha_devolucion;
							$h_stock_anterior = $sk_stock;
							$stock_total = $new_stock;
							$h_detalle = 'ALTA';
							$h_obs = 'ALTA POR PRESTAMO';

							/*
							*/
							$statHistory = $pdo->prepare("INSERT INTO tbl_historia (
																					h_id_producto,
																					h_id_user,
																					h_id_sucursal,
																					h_stock_anterior,
																					h_stock_actual,
																					h_detalle,
																					h_obs,
																					h_fecha
																				) VALUES (?,?,?,?,?,?,?,?);");
							$statHistory->execute(array(
														$id_producto,
														$h_id_user,
														$h_id_sucursal,
														$h_stock_anterior,
														$stock_total,
														$h_detalle,
														$h_obs,
														$h_fecha));
						}

						

						
					}

					//usuario:
					session_start();
					$user_devuelve = $_SESSION['user']['id'];
					/*
					if($_SESSION['user']['role']=='Super Admin'){
						$sucursal_devuelto = $id_sucursal;
					}else{
						$sucursal_devuelto = $_SESSION['user']['sucursal'];
					}*/

					$sucursal_devuelto = $id_sucursal;
					//actualizamos el estado del comodato:
					$statUpdComodato = $pdo->prepare("UPDATE tbl_comodato SET estado = ?, fecha_devolucion = ?, sucursal_devuelto = ?, devuelto_por = ? WHERE orden = ?;");
					$statUpdComodato->execute(array($estado, $fecha_devolucion, $sucursal_devuelto, $user_devuelve, $nro_orden_comodato));


					
					echo '{"success": true}';
				}else{
					echo '{"success":false, "msg": "Falta número de orden de comodato"}';
				}
			}else{
				echo '{"success":false, "msg": "Falta ID de sucursal"}';
			}
			
			break;
		
		case 'nuevo':
				$arr_productos = isset($_POST['productos'])? json_decode($_POST['productos']) : [];
				
				if(sizeof($arr_productos)>0){
					$orden = time();
					$fecha_emision = $orden; //fecha de emisión será la misma que orden
					$fecha_limite = isset($_POST['fecha_limite'])? $_POST['fecha_limite'] : 0;
					$id_cliente = isset($_POST['id_cliente'])? $_POST['id_cliente']: '';
					$observaciones = isset($_POST['observaciones'])? $_POST['observaciones']: '';
					$estado = 'Prestado';
					session_start();
					$id_user = $_SESSION['user']['id'];
					
					
					foreach($arr_productos as $prod){
						
						$id_producto = $prod->idProducto;
						$id_sucursal = $prod->id_sucursal;
						$cantidad = $prod->cant;

						$statNuevo = $pdo->prepare("INSERT INTO `tbl_comodato`(`id_cliente`, `id_sucursal`, `id_producto`, `cantidad`, `id_user`, `observaciones`, `fecha_emision`, `fecha_devolucion`, `estado`, `orden`, `fecha_limite`) VALUES (?,?,?,?,?,?,?,?,?,?,?);");
						$statNuevo->execute(array($id_cliente, $id_sucursal, $id_producto, $cantidad, $id_user, $observaciones, $fecha_emision, NULL, $estado, $orden, $fecha_limite));

						//quitamos del stock los productos con id diferente a -1
						if($id_producto>0){ // mientras sea diferente a -1

							//obtengo el stock anterior para poder restarle la cantidad requerida:
							$statPrevStock = $pdo->prepare("SELECT * FROM tbl_stock WHERE sk_id_producto = ? AND sk_id_sucursal = ?;");
							$statPrevStock->execute(array($id_producto, $id_sucursal));
							$resultPrevStk = $statPrevStock->fetchAll(PDO::FETCH_ASSOC);
							
							foreach($resultPrevStk as $rowPrevStk){
								$sk_id = $rowPrevStk['sk_id'];
								$sk_stock = $rowPrevStk['sk_stock'];
							}

							//devolvemos el stock:
							$new_stock = $sk_stock - $cantidad;
							$statUpdStk = $pdo->prepare("UPDATE tbl_stock SET sk_stock = ? WHERE sk_id = ?;");
							$statUpdStk->execute(array($new_stock, $sk_id));

							//log:
							$h_id_user = $id_user;
							$h_id_sucursal = $id_sucursal;//$_SESSION['user']['sucursal'];
							$h_fecha = $fecha_emision;
							$h_stock_anterior = $sk_stock;
							$stock_total = $new_stock;
							$h_detalle = 'BAJA';
							$h_obs = 'BAJA POR PRESTAMO';
							/*
								session_start();
							*/
							$statHistory = $pdo->prepare("INSERT INTO tbl_historia (
																					h_id_producto,
																					h_id_user,
																					h_id_sucursal,
																					h_stock_anterior,
																					h_stock_actual,
																					h_detalle,
																					h_obs,
																					h_fecha
																				) VALUES (?,?,?,?,?,?,?,?);");
							$statHistory->execute(array(
														$id_producto,
														$h_id_user,
														$h_id_sucursal,
														$h_stock_anterior,
														$stock_total,
														$h_detalle,
														$h_obs,
														$h_fecha));

						}
					}
					//print_r($_POST);
				}

				echo '{"success": true}';
			break;
		
		default:
			echo '{"error": "acción no seleccionada"}';
			break;
	}

	//echo json_encode($arrOut);
 ?>