<?php require_once('header.php'); ?>

<?php

	if(isset($_POST['form1'])){
		$error = 0;
		
		$id_producto = $_POST['id_producto'];
		$id_sucursal = $_POST['id_sucursal'];
		$stock = $_POST['cant'];
		$s_detalle = $_POST['s_detalle'];
		$h_obs = $_POST['h_obs'];


		//obtengo el registro de tbl_stock
		$statment = $pdo->prepare("SELECT * FROM tbl_stock WHERE sk_id_producto = ? AND sk_id_sucursal = ?;");
		$statment->execute(array($id_producto, $id_sucursal));
		$result = $statment->fetchAll(PDO::FETCH_ASSOC);
		$cant = $statment->rowCount();

		//stock del producto:
		foreach($result as $row){
			$sk_id = $row['sk_id'];
			$sk_stock = $row['sk_stock'];
		}

		if($cant > 0){
			//verifico si es BAJA y si la resta de stock es mayor o igual a cero.
			if($s_detalle == 2){
				$stock_temp = intval($sk_stock) - intval($stock);
				if($stock_temp>=0){ //BAJA de producto
					//UPDATE:
					$newStat = $pdo->prepare("UPDATE tbl_stock SET sk_stock = ? WHERE sk_id = ?;");
					$newStat->execute(array($stock_temp, $sk_id));

					//aquí hacer log UPDATE stock
					$success_message .= "¡Stock actualizado correctamente!";
				}else{
					$error_message .= "No existen tantos productos para quitar.";
					$error = 1;
				}
			}elseif($s_detalle == 1){ //ALTA DE PRODUCTO:
				$stock_temp = intval($sk_stock) + intval($stock);
				//UPDATE
				$newStat = $pdo->prepare("UPDATE tbl_stock SET sk_stock = ? WHERE sk_id = ?;");
					$newStat->execute(array($stock_temp, $sk_id));

					//aquí hacer log UPDATE stock
					$success_message .= "¡Stock actualizado correctamente!";
			}else{
				$error_message .= "Debe seleccionar un detalle.";
				$error = 1;
			}
		}else{
			//verifico si es BAJA y si la resta de stock es mayor o igual a cero.
			if($s_detalle == 2){
				//por lógica no hay stock:
				$error_message .= "No existen tantos productos para quitar.";
				$error = 1;
			}elseif($s_detalle == 1){
				$stock_temp = intval($sk_stock) + intval($stock);
				//INSERT
				$newStat = $pdo->prepare("INSERT INTO tbl_stock (sk_stock, sk_id_producto, sk_id_sucursal) VALUES (?,?,?);");
				$newStat->execute(array($stock_temp, $id_producto, $id_sucursal));

				//aquí hacer log INSERT stock
				$success_message .= "¡Stock actualizado correctamente!";
			}else{
				$error_message .= "Debe seleccionar un detalle.";
				$error = 1;
			}
		}

		if($error == 0){
			//Obtengo el stock total del producto y lo actualizo en tbl_product
			$statSuma = $pdo->prepare("SELECT SUM(sk_stock) AS suma FROM tbl_stock WHERE sk_id_producto = ?;");
			$statSuma->execute(array($id_producto));
			$res = $statSuma->fetchAll(PDO::FETCH_ASSOC);
			foreach($res as $row){
				$stock_total = $row['suma'];
			}
			//actualizo cantidad de stock en producto:
			$statProd = $pdo->prepare("UPDATE tbl_product SET p_qty = ? WHERE p_id = ?;");
			$statProd->execute(array($stock_total, $id_producto));

			//guardo en historia:
			//$h_detalle = ($s_detalle==1)? 'ALTA' : 'BAJA';
			switch ($s_detalle) {
				case '1':
					$h_detalle = 'ALTA';
					break;
				case '2':
					$h_detalle = 'BAJA';
					break;
				case '3':
					$h_detalle = 'TRASPASO';
					break;
				default:
					$h_detalle = '';
					break;
			}
			$h_id_user = $_SESSION['user']['id'];
			$h_id_sucursal = $id_sucursal;//$_SESSION['user']['sucursal'];
			$h_fecha = time();
			$h_stock_anterior = $_POST['p_qty'];
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

		/*
			-ALTA
			-BAJA
			-MODIFICACION
			-ELIMINACION
			-TRASPASO
			-LOGIN
			-LOGOUT
			-ABRIR CAJA
			-CERRAR CAJA
		*/
		
		//Guardo Log:
		/*$id_usuario = $_SESSION['user']['id'];
		$id_sucursal = $_SESSION['user']['sucursal'];
		$idProducto = $id_producto;
		$detalle = "ALTA STOCK";
		$fecha = time();
		$statement = $pdo->prepare("INSERT INTO tbl_logs (id_usuario, id_sucursal, id_producto, detalle, fecha) VALUES (?,?,?,?,?);"); 
		$statement->execute(array($id_usuario, $id_sucursal, $idProducto, $detalle, $fecha ));*/



		


	}


	$idProduct = isset($_GET['id']) ? $_GET['id']: -1;

	//Obtengo datos del producto:
	$productoSql = $pdo->prepare("SELECT * FROM tbl_product WHERE p_id = ?");
	$productoSql->execute([$idProduct]);
	$resultProducto = $productoSql->fetchAll(PDO::FETCH_ASSOC);
	foreach($resultProducto as $row){
		$p_id = $row['p_id'];
		$p_name = $row['p_name'];
		$p_qty = $row['p_qty'];
		$p_featured_photo = $row['p_featured_photo'];
		$p_sucursal_id = $row['p_sucursal_id'];
	}


	

?>

<section class="content-header">
	<h1><?php echo ucfirst($p_name)." ( ID: ".$p_id." )"; ?></h1>
</section>

<section class="content">
	<div class="row">
		<div class="col-md-12">
			<?php if($error_message): ?>
			<div class="callout callout-danger">
			
			<p>
			<?php echo $error_message; ?>
			</p>
			</div>
			<?php endif; ?>

			<?php if($success_message): ?>
			<div class="callout callout-success">
			
			<p><?php echo $success_message; ?></p>
			</div>
			<?php endif; ?>


			<form id="formTraspaso" class="form-horizontal" action="" method="post" >
				<div class="box box-info">
					<div class="box-body" >
						<h4><i class="fa fa-shopping-cart"></i> Stock Existente:</h4>
						<div id="boxStock">
							<?php 
								$arrIds = array();

								$statement = $pdo->prepare("SELECT * FROM tbl_stock AS t1 INNER JOIN tbl_product AS t2 ON t1.sk_id_producto = t2.p_id INNER JOIN tbl_sucursales AS t3 ON t1.sk_id_sucursal = t3.s_id WHERE t2.p_id = ?");
								$statement->execute(array($idProduct));
								$result = $statement->fetchAll(PDO::FETCH_ASSOC);
								foreach($result as $row){
									array_push($arrIds, $row['sk_id_sucursal']);
									?>
										<div class="form-group">
											<label for="" class="col-sm-3 control-label"><i class="fa fa-building-o"></i> <?php echo $row['s_name']; ?>: </label>
											<label class="control-label" style="font-weight: initial;"><?php echo $row['sk_stock']; ?></label>
											
										</div>
									<?php
								}
							 ?>

							<div class="form-group">
								<label for="" class="col-sm-3 control-label"><i class="fa fa-shopping-cart"></i> Total: </label>
								<label class="control-label" style="font-weight: initial;"><?php echo $row['p_qty']; ?></label>
							</div>

						</div>

						<hr class="hr-dark">
						<h4><i class="fa fa-cart-plus"></i> Nuevo Stock: </h4>
						<div class="form-group">
							<label for="" class="col-sm-3 control-label"><i class="fa fa-building-o"></i> Sucursal: </label>
							<div class="col-sm-4">
								<select name="id_sucursal" id="stockSucursales" class="form-control" required>
									<option value="">Seleccione Sucursal</option>
									<?php 
										$statement = $pdo->prepare("SELECT * FROM tbl_sucursales WHERE s_online=?");
										$statement->execute(array(0));
										$result = $statement->fetchAll(PDO::FETCH_ASSOC);
										foreach($result as $row){
											?>
												<option value="<?php echo $row['s_id']; ?>"><?php echo $row['s_name']; ?></option>
											<?php
										}
									 ?>
								</select>
								
							</div>
						</div><!-- .form-group -->

						<div class="form-group">
							<label for="" class="col-sm-3 control-label"><i class="fa fa-edit"></i> Cantidad: </label>
							<div class="col-sm-4">
								<input type="number" class="form-control" name="cant" value="1" min="1" required>
							</div>
						</div><!-- .form-group -->
						
						<div class="form-group">
							<label for="" class="col-sm-3 control-label"><i class="fa fa-info"></i> Detalle: </label>
							<div class="col-sm-4">
								<select name="s_detalle" id="stockSucursales" class="form-control" required>
									<option value="">Seleccione Detalle</option>
									<option value="1">ALTA</option>
									<option value="2">BAJA</option>
								</select>
								
							</div>
						</div><!-- .form-group -->

						<div class="form-group">
							<label for="" class="col-sm-3 control-label"><i class="fa fa-edit"></i> Motivo: </label>
							<div class="col-sm-4">
								<input type="text" class="form-control" name="h_obs" placeholder="Ej: Baja por robo / Alta productos nuevos" required>
							</div>
						</div><!-- .form-group -->

						<div class="form-group">
							<label for="" class="col-sm-3 control-label"></label>
							<div class="col-sm-6">
								<input type="hidden" id="idProducto" name="id_producto" value="<?php echo $_GET['id']; ?>">
								<input type="hidden" name="p_qty" value="<?php echo $p_qty; ?>">
								<button name="form1" type="submit" id="btnGuardarStock" class="btn btn-success pull-left" > Guardar </button>
							</div>
						</div>
					</div>
				</div>
			</form>
			

		</div>		
		
	</div>
</section>

<?php require_once('footer.php'); ?>