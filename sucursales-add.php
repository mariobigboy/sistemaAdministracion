<?php require_once('header.php'); ?>

<?php
if(isset($_POST['form1'])) {
	$valid = 1;

    if(empty($_POST['s_address']) || empty($_POST['s_name'])) {
        $valid = 0;
        $error_message .= "La dirección o el nombre  no pueden estar vacíos.<br>";
    } else {
    	// Duplicate Category checking
    	$statement = $pdo->prepare("SELECT * FROM tbl_sucursales WHERE s_address=?");
    	$statement->execute(array($_POST['s_address']));
    	$total = $statement->rowCount();
    	if($total)
    	{
    		$valid = 0;
        	$error_message .= "Ya existe una sucursal con dicha dirección.<br>";
    	}
    }

    if($valid == 1) {

		// Saving data into the main table tbl_size
		$statement = $pdo->prepare("INSERT INTO tbl_sucursales (
														s_name, 
														s_address, 
														s_hours, 
														s_lat, 
														s_long, 
														s_phones,
														s_condicion_iva,
														s_cuit_cuil, 
														s_active
													) VALUES (?, ?, ?, ?, ?, ?)");
		$statement->execute(array($_POST['s_name'],
									$_POST['s_address'],
									$_POST['s_hours'],
									$_POST['s_lat'],
									$_POST['s_long'],
									$_POST['s_phones'],
									$_POST['s_condicion_iva'],
									$_POST['s_cuit_cuil'],
									$_POST['s_active']));
	
    	$success_message = 'Sucursal agregada correctamente.';
    }
}
?>

<section class="content-header">
	<div class="content-header-left">
		<h1>Nueva sucursal</h1>
	</div>
	<div class="content-header-right">
		<a href="sucursales.php" class="btn btn-primary btn-sm">Ver todas</a>
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

				<div class="box box-info">
					<div class="box-body">
						<div class="form-group">
							<label for="" class="col-sm-2 control-label"><i class="fa fa-location-arrow"></i> Nombre de sucursal <span>*</span></label>
							<div class="col-sm-4">
								<input type="text" class="form-control" name="s_name" placeholder="Nombre Local">
							</div>
						</div>
						
						<div class="form-group">
							<label for="" class="col-sm-2 control-label"><i class="fa fa-location-arrow"></i> Dirección de sucursal <span>*</span></label>
							<div class="col-sm-4">
								<input type="text" class="form-control" name="s_address" placeholder="Av. Bicentenario 123">
							</div>
						</div>
						
						<div class="form-group">
							<label for="" class="col-sm-2 control-label"><i class="fa fa-phone"></i> Teléfono </label>
							<div class="col-sm-4">
								<input type="text" class="form-control" name="s_phones" placeholder="3875123456">
							</div>
						</div>

						<div class="form-group">
							<label for="" class="col-sm-2 control-label"><i class="fa fa-clock-o"></i> Horarios </label>
							<div class="col-sm-4">
								<input type="text" class="form-control" name="s_hours" placeholder="Lu - Sáb, de 09:00 a 21:00">
							</div>
						</div>
						
						<div class="form-group">
							<label for="" class="col-sm-2 control-label"><i class="fa fa-map-marker"></i> Latitud </label>
							<div class="col-sm-4">
								<input type="text" class="form-control" name="s_lat" placeholder="-24.7859000">
							</div>
						</div>

						<div class="form-group">
							<label for="" class="col-sm-2 control-label"><i class="fa fa-map-marker"></i> Longitud </label>
							<div class="col-sm-4">
								<input type="text" class="form-control" name="s_long" placeholder="-65.4116600">
							</div>
						</div>

						<div class="form-group">
		                    <label for="" class="col-sm-2 control-label"><i class="fa fa-line-chart"></i> Condición IVA </label>
		                    <div class="col-sm-4">
		                        <input type="text" class="form-control" name="s_condicion_iva" placeholder="I.V.A. Responsable Inscripto" >
		                    </div>
		                </div>

		                <div class="form-group">
		                    <label for="" class="col-sm-2 control-label"><i class="fa fa-id-card-o"></i> CUIT / CUIL </label>
		                    <div class="col-sm-4">
		                        <input type="text" class="form-control" name="s_cuit_cuil" placeholder="00-00000000-0" >
		                    </div>
		                </div>

						<div class="form-group">
							<label for="" class="col-sm-3 control-label">¿Activo?</label>
							<div class="col-sm-8">
								<select name="s_active" class="form-control" style="width:auto;">
									<option value="1" selected>Sí</option>
									<option value="0">No</option>
								</select> 
							</div>
						</div>

						<div class="form-group">
							<label for="" class="col-sm-2 control-label"></label>
							<div class="col-sm-6">
								<button type="submit" class="btn btn-success pull-left" name="form1">Guardar</button>
							</div>
						</div>


					</div>
				</div>

			</form>


		</div>
	</div>

</section>

<?php require_once('footer.php'); ?>