<?php require_once('header.php'); ?>

<?php

	if(isset($_POST['form1'])){
		$stock_total = 0;
		for ($i=0; $i < sizeof($_POST['id_productos']); $i++) { 
			//echo $i."\n";
			$id_producto = $_POST['id_productos'][$i];
			$id_sucursal = $_POST['id_sucursales'][$i];
			$stock = $_POST['stocks'][$i];

			//sumo total stock:
			$stock_total += $stock;

			$statment = $pdo->prepare("SELECT * FROM tbl_stock WHERE sk_id_producto = ? AND sk_id_sucursal = ?;");
			$statment->execute(array($id_producto, $id_sucursal));
			$cant = $statment->rowCount();

			if($cant>0){ 
				//si existe: UPDATE
				$newStat = $pdo->prepare("UPDATE tbl_stock SET sk_stock = ? WHERE sk_id_producto = ? AND sk_id_sucursal = ?;");
				$newStat->execute(array($stock, $id_producto, $id_sucursal));

				//aquí hacer log UPDATE stock
				$success_message .= "¡Stock actualizado correctamente!";

			}else{
				//sino INSERT
				$newStat = $pdo->prepare("INSERT INTO tbl_stock (sk_stock, sk_id_producto, sk_id_sucursal) VALUES (?,?,?);");
				$newStat->execute(array($stock, $id_producto, $id_sucursal));

				//aqui hacer log INSERT  stock
				$success_message .= "¡Stock actualizado correctamente!";
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
			$id_usuario = $_SESSION['user']['id'];
			$id_sucursal = $_SESSION['user']['sucursal'];
			$idProducto = $id_producto;
			$detalle = "ALTA STOCK";
			$fecha = time();
			$statement = $pdo->prepare("INSERT INTO tbl_logs (id_usuario, id_sucursal, id_producto, detalle, fecha) VALUES (?,?,?,?,?);"); 
			$statement->execute(array($id_usuario, $id_sucursal, $idProducto, $detalle, $fecha ));


		}

		//actualizo cantidad de stock en producto:
		$statProd = $pdo->prepare("UPDATE tbl_product SET p_qty = ? WHERE p_id = ?;");
		$statProd->execute(array($stock_total, $id_producto));


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
						<h4>Editar Stock</h4>
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
											<label for="" class="col-sm-3 control-label"><i class="fa fa-building-o"></i> <?php echo $row['s_name']; ?> </label>
											<div class="col-sm-4">
												<input type="hidden" name="id_productos[]" value="<?php echo $row['p_id']; ?>">
												<input type="hidden" name="id_sucursales[]" value="<?php echo $row['sk_id_sucursal']; ?>">
												<input type="number" class="form-control" name="stocks[]" min="0" value="<?php echo $row['sk_stock']; ?>" required>
											</div>
										</div>
									<?php
								}
							 ?>
						</div>

						<div class="form-group">
							<label for="" class="col-sm-3 control-label"></label>
							<div class="col-sm-6">
								<input type="hidden" id="idProducto" name="idProducto" value="<?php echo $_GET['id']; ?>">
								<button name="form1" type="submit" id="btnGuardarStock" class="btn btn-success pull-left" >Guardar</button>
							</div>
						</div>
					</div>
				</div>
			</form>
			
			<div class="box box-info">
				<div class="box-body" >
					<div class="form-group">
						<label for="" class="col-sm-3 control-label">Añadir Stock en Sucursal: </label>
						<div class="col-sm-4">
							<select name="" id="stockSucursales" class="form-control">
								<option value="">Seleccione Sucursal</option>
								<?php 
									$statement = $pdo->prepare("SELECT * FROM tbl_sucursales WHERE s_online=?");
									$statement->execute(array(0));
									$result = $statement->fetchAll(PDO::FETCH_ASSOC);
									foreach($result as $row){
										if(!in_array($row['s_id'], $arrIds)){

										?>
											<option value="<?php echo $row['s_id']; ?>"><?php echo $row['s_name']; ?></option>
										<?php
										}
									}
								 ?>
							</select>
							
						</div>
						<div class="col-sm-2">
							<button class="btn btn-primary" id="btnAddStock" >Añadir</button>
						</div>
					</div><!-- .form-group -->
				</div>
			</div>
			

		</div>		
		
	</div>
</section>

<?php require_once('footer.php'); ?>