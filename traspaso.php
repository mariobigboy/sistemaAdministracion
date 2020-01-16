<?php require_once('header.php'); ?>



<?php
	
	$idProducto = isset($_GET['id'])? $_GET['id'] : -1;
	$sucursal_id = $_SESSION['user']['sucursal'];
	$role = $_SESSION['user']['role'];

	if(isset($_POST['form1'])){

		$valid = 1;

		if($_SESSION['user']['role']!='Empleado'){
			if(empty($_POST['from'])){
				$error_message .= "Necesita seleccionar la sucursal de la cual traspasar el stock.<br>";
				$valid = 0;
			}
		}else{
			$_POST['from'] = $sucursal_id;
		}

		if(empty($_POST['to'])){
			$error_message .= "Necesita seleccionar la sucursal a la que desea traspasar el stock.<br>";
			$valid = 0;
		}		

		if(empty($_POST['stock'])){
			$error_message .= "El Stock no puede ser 0 (cero).<br>";
			$valid = 0;
		}

		if($valid==1){
			//realizo el traspaso:
			

			$id_sucursal_from = $_POST['from'];
			$id_sucursal_to = $_POST['to'];
			$stock = intval($_POST['stock']);

			//obtengo registro del cual voy a hacer el traspaso:
			$statementFrom = $pdo->prepare("SELECT * FROM tbl_stock WHERE sk_id_producto = ? AND sk_id_sucursal = ?;");
			$statementFrom->execute(array($idProducto, $id_sucursal_from));
			$resultFrom = $statementFrom->fetchAll(PDO::FETCH_ASSOC);
			foreach ($resultFrom as $rowFrom) {
				$sk_id_from = $rowFrom['sk_id'];
				$sk_stock_from = intval($rowFrom['sk_stock']);
			}
			$cantFrom = $statementFrom->rowCount();

			//obtengo registro al cual voy a hacer el traspaso:
			$statementTo = $pdo->prepare("SELECT * FROM tbl_stock WHERE sk_id_producto = ? AND sk_id_sucursal = ?;");
			$statementTo->execute(array($idProducto, $id_sucursal_to));
			$resultTo = $statementTo->fetchAll(PDO::FETCH_ASSOC);
			foreach ($resultTo as $rowTo) {
				$sk_id_to = $rowTo['sk_id'];
				$sk_stock_to = intval($rowTo['sk_stock']);
			}
			$cantTo = $statementTo->rowCount();


			if($cantTo>0){
				//si existe hago UPDATE en tbl_stock

				//añado stock en esta sucursal (to):
				$stk = $sk_stock_to + $stock;
				$statement = $pdo->prepare("UPDATE tbl_stock SET sk_stock = ? WHERE sk_id = ?;");
				$statement->execute(array($stk, $sk_id_to));

				


			}else{
				//sino INSERT en tbl_stock
				$statement = $pdo->prepare("INSERT INTO tbl_stock (sk_id_producto, sk_id_sucursal, sk_stock) VALUES (?, ?, ?);");
				$statement->execute(array($idProducto, $id_sucursal_to, $stock));

			}

			//quito el mismo stock en la sucursal anterior (from):
			$stk = $sk_stock_from - $stock;
			$statement = $pdo->prepare("UPDATE tbl_stock SET sk_stock = ? WHERE sk_id = ?;");
			$statement->execute(array($stk, $sk_id_from));


			$success_message .= "¡Traspaso de Stock realizado con éxito!";


			//historia (BAJA):
			//guardo en historia: (BAJA)
			$h_detalle = 'TRASPASO';
			$h_obs = 'BAJA EN SUCURSAL';			
			$h_id_user = $_SESSION['user']['id'];
			$h_id_sucursal = $id_sucursal_from;
			$h_fecha = time();
			$h_stock_anterior = $sk_stock_from;
			$stock_actual = $sk_stock_from - $stock;
			$statHistory = $pdo->prepare("INSERT INTO tbl_historia (h_id_producto, h_id_user, h_id_sucursal, h_stock_anterior, h_stock_actual, h_detalle, h_obs, h_fecha ) VALUES (?,?,?,?,?,?,?,?);");
			$statHistory->execute(array($idProducto, $h_id_user, $h_id_sucursal, $h_stock_anterior, $stock_actual, $h_detalle, $h_obs, $h_fecha));

			//guardo en historia: (ALTA)
			$h_obs = 'ALTA EN SUCURSAL';
			$h_id_sucursal = $id_sucursal_to;
			$h_fecha = time();
			$h_stock_anterior = $sk_stock_to || 0;
			$stock_actual = $h_stock_anterior + $stock;
			$statHistory = $pdo->prepare("INSERT INTO tbl_historia (h_id_producto, h_id_user, h_id_sucursal, h_stock_anterior, h_stock_actual, h_detalle, h_obs, h_fecha ) VALUES (?,?,?,?,?,?,?,?);");
			$statHistory->execute(array($idProducto, $h_id_user, $h_id_sucursal, $h_stock_anterior, $stock_actual, $h_detalle, $h_obs, $h_fecha));
						
			//realizo log del traspaso:
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
			//$id_usuario = $_SESSION['user']['id'];
			//$id_sucursal = $_SESSION['user']['sucursal'];
			//$idProducto = $id_producto;
			//$detalle = "TRASPASO STOCK";
			//$fecha = time();
			//$statement = $pdo->prepare("INSERT INTO tbl_logs (id_usuario, id_sucursal, id_producto, detalle, fecha) VALUES (?,?,?,?,?);"); 
			//$statement->execute(array($id_usuario, $id_sucursal, $idProducto, $detalle, $fecha));
		}
	}

?>

<?php 
	$statement = $pdo->prepare("SELECT * FROM tbl_product WHERE p_id = ?;");
	$statement->execute(array($idProducto));
	$results = $statement->fetchAll(PDO::FETCH_ASSOC);
	foreach($results as $row){
		$p_id = $row['p_id'];
		$p_name = $row['p_name'];
	}
 ?>
<section class="content-header">
	<h1>Traspaso de Stock: <?php echo ucfirst($p_name)." ( ID: ".$p_id." )"; ?></h1>
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

			<form class="form-horizontal" action="" method="post" >
				<input type="hidden" name="prodId" value="<?php echo $idProducto; ?>">
				<div class="box box-info">
					<div class="box-body" >
						
						

						<p style="font-style: italic;">Traspasar stock de una sucursal a otra.</p>

						<?php 

							//$role = $_SESSION['user']['role'];
							if($role=='Super Admin' || $role=='Admin'){
								?>
									<div class="form-group col-md-4">
										<label for="" class="control-label col-md-4"> <i class="fa fa-building-o"></i> Desde: </label>
										<div class="col-md-8">
											<select name="from" id="selTraspasoFrom" class="form-control col-md-4" required>
												
												<?php 
													$statement = $pdo->prepare("SELECT * FROM tbl_stock AS t1 INNER JOIN tbl_sucursales AS t2 ON t1.sk_id_sucursal = t2.s_id WHERE t1.sk_id_producto = ? AND t1.sk_stock > ?;");
													$statement->execute(array($idProducto, 0));
													$results = $statement->fetchAll(PDO::FETCH_ASSOC);
													$cantResult = $statement->rowCount();
													if($cantResult>0){
														echo '<option value="" data-max="0">Seleccione Sucursal</option>';
														foreach($results as $row){
															?>
																<option value="<?php echo $row['sk_id_sucursal'] ?>" data-max="<?php echo $row['sk_stock']; ?>"><?php echo $row['s_name']." (".$row['sk_stock'].")"; ?></option>
															<?php
														}
													}else{
														echo '<option value="" data-max="0">No hay Stock</option>';
													}
												 ?>
											</select>
										</div>
									</div>

									<div class="form-group col-md-4">
										<label for="" class="control-label col-md-4"><i class="fa fa-building-o"></i> Hasta: </label>
										<div class="col-md-8">
											<select name="to" id="selTraspasoTo" class="form-control col-md-4" required>
												<option value="">Seleccione Sucursal</option>
												<?php 
													$statement = $pdo->prepare("SELECT * FROM tbl_sucursales WHERE s_online = ?;");
													$statement->execute(array(0));
													$results = $statement->fetchAll(PDO::FETCH_ASSOC);
													foreach($results as $row){
														?>
															<option value="<?php echo $row['s_id'] ?>"><?php echo $row['s_name']; ?></option>
														<?php
													}
												 ?>
											</select>
										</div>
									</div>

									<div class="form-group col-md-4">
										<label for="" class="control-label col-md-4"> <i class="fa fa-cart-plus"></i> Stock: </label>
										<div class="col-md-8">
											<input type="number" id="inpStockTrasp" name="stock" class="form-control" value="0" min="0" max="" required>
										</div>
									</div>

								<?php
							}else{
								?>
									<div class="form-group col-md-12">
										<?php 
											//$sucursal_id = $_SESSION['user']['sucursal'];
											$statement = $pdo->prepare("SELECT * FROM tbl_sucursales WHERE s_id = ?");
											$statement->execute(array($sucursal_id));
											$results = $statement->fetchAll(PDO::FETCH_ASSOC);
											foreach ($results as $row) {
												$s_id = $row['s_id'];
												$s_name = $row['s_name'];
											}

											//obtengo el registro stock:
											$statement = $pdo->prepare("SELECT * FROM tbl_stock WHERE sk_id_sucursal = ? AND sk_id_producto = ?;");
											$statement->execute(array($s_id, $idProducto));
											$results = $statement->fetchAll(PDO::FETCH_ASSOC);
											foreach($results as $row){
												$sk_id = $row['sk_id'];
												$sk_stock = $row['sk_stock'];
											}


										 ?>
										<label for="" class="control-label col-md-offset-1" style="margin-left: 1.2em;"><i class="fa fa-building-o"></i> Desde la sucursal: <span style="font-weight: initial;"><?php echo $s_name." (Stock en esta sucursal: ".($sk_stock==0? "0" : $_sk_stock).")"; ?></span></label>
									</div>
									<div class="form-group col-md-4">
										<label for="" class="control-label col-md-3"><i class="fa fa-building-o"></i> Hasta: </label>
										<div class="col-md-8">
											<select name="to" id="selTraspasoTo" class="form-control col-md-4" required>
												<option value="">Seleccione Sucursal</option>
												<?php 
													$statement = $pdo->prepare("SELECT * FROM tbl_sucursales WHERE s_online = ?;");
													$statement->execute(array(0));
													$results = $statement->fetchAll(PDO::FETCH_ASSOC);
													foreach($results as $row){
														if($row['s_id']!=$sucursal_id){

														?>
															<option value="<?php echo $row['s_id'] ?>"><?php echo $row['s_name']; ?></option>
														<?php
														}
													}
												 ?>
											</select>
										</div>
									</div>

									<div class="form-group col-md-4">
										<label for="" class="control-label col-md-3"><i class="fa fa-cart-plus"></i> Stock: </label>
										<div class="col-md-8">
											<input type="hidden" name="from" value="<?php echo $idProducto; ?>">
											<input type="number" id="inpStockTrasp" name="stock" class="form-control" value="0" min="0" <?php echo 'max="'.($sk_stock==0? "0" : $_sk_stock).'"'; ?> required>
										</div>
									</div>
								<?php
							}

						 ?>


						<div class="col-md-12">
							<div class="form-group">
								<div class="col-sm-12 text-center">
									<!--<input type="hidden" id="idProducto" name="idProducto" value="<?php echo $_GET['id']; ?>">-->
									<button type="submit" class="btn btn-success pull-left" name="form1" <?php #if($_SESSION['user']['role']=='Empleado'){echo 'disabled';} ?>>Traspasar</button>
								</div>
							</div>
						</div>

					</div>
				</div>
			</form>
			
		</div>
	</div>
</section>

<?php require_once('footer.php'); ?>