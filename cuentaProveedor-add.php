<?php require_once('header.php'); ?>

<?php

$id_proveedor = isset($_GET['id'])? $_GET['id'] : "";

if(isset($_POST['form1'])) {
	$valid = 1;


    if(empty($_POST['factura'])) {
        $valid = 0;
        $error_message .= "Debe ingresar Factura o Remito.<br>";
    }

    if(empty($_POST['monto'])) {
        $valid = 0;
        $error_message .= "Debe ingresar un monto.<br>";
    }

    $factura = isset($_POST['factura']) ? $_POST['factura'] : "";
	$monto = isset($_POST['monto']) ? $_POST['monto'] : "";
	$fecha_factura = isset($_POST['fecha']) ? $_POST['fecha'] : "";
	$fecha_creacion = time();
	$idProveedor = isset($_POST['id_proveedor']) ? $_POST['id_proveedor'] : "";
	

	$usuario_name = $_SESSION['user']['full_name'];


    if($valid == 1) {

		//Saving data into the main table tbl_cliente
		$statement = $pdo->prepare("INSERT INTO cuentasProveedores (
														`id`, 
														`idProveedor`, 
														`factura`, 
														`fecha_factura`, 
														`monto`, 
														`fecha`, 
														`usuario`, 
														`estado`) VALUES (NULL,?,?,?,?,?,?,?);");
		$statement->execute(array(
										strip_tags($idProveedor),
										strip_tags($factura),
										strip_tags($fecha_factura),
										strip_tags($monto),
										strip_tags($fecha_creacion),
										strip_tags($usuario_name),
										'0'										
									));

		$idCuenta = $pdo->lastInsertId();

		//INSERT INTO `detalleProveedores`(`id`, `idCuenta`, `pago`, `formaPago`, `fecha`) VALUES ([value-1],[value-2],[value-3],[value-4],[value-5])
		$statementDetalles = $pdo->prepare("INSERT INTO `detalleProveedores`(
																		`id`, 
																		`idCuenta`, 
																		`pago`, 
																		`formaPago`, 
																		`fecha`) VALUES (NULL,?,?,NULL,?);");
		$statementDetalles->execute(array($idCuenta, 0, $fecha));
		

	
    	$success_message .= 'Cuenta agregada correctamente. <br>';
    }
}
?>

<section class="content-header">
	<div class="content-header-left">
		<h1>Nueva Cuenta</h1>
	</div>
	<div class="content-header-right">
		<a href="proveedores.php" class="btn btn-primary btn-sm">Ver Todos</a>
	</div>
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

			<form class="form-horizontal" action="" method="post">
				<input type="hidden" name="id_proveedor" value="<?php echo $id_proveedor; ?>">

				<div class="box box-info">
					<div class="box-body">
						<h3>Datos de Cuenta:</h3>
						<div class="form-group">
							<label for="" class="col-sm-3 control-label"><i class="fa fa-file-text-o"></i> N° Factura / Remito <span>*</span></label>
							<div class="col-sm-4">
								<input type="text" name="factura" class="form-control" placeholder=" 12345">
							</div>
						</div>

						<div class="form-group">
							<label for="" class="col-sm-3 control-label"><i class="fa fa-usd"></i> Monto <span>*</span></label>
							<div class="col-sm-4">
								<input type="text" name="monto" class="form-control" placeholder="0.00">
							</div>
						</div>
						
							
						<div class="form-group">
							<label for="" class="col-sm-3 control-label"><i class="fa fa-calendar"></i> Fecha </label>
							<div class="col-sm-4">
								<input type="text" name="fecha" class="datepicker_format form-control" placeholder="31/12/2020">
							</div>
						</div>
						
						
					
						<div class="form-group">
							<label for="" class="col-sm-3 control-label"></label>
							<div class="col-sm-6">
								<button type="submit" class="btn btn-success pull-left" name="form1"> <i class="fa fa-send"></i> Guardar</button>
							</div>
						</div>
					</div>
				</div>

			</form>


		</div>
	</div>

</section>

<?php require_once('footer.php'); ?>

<script type="text/javascript">

		
</script>