<?php require_once('header.php'); ?>

<?php
if(isset($_POST['form1'])) {
	$valid = 1;

    if(empty($_POST['s_address']) || empty($_POST['s_name'])) {
        $valid = 0;
        $error_message .= "La dirección o el nombre no pueden estar vacíos.<br>";
    } else {
		// Duplicate Size checking
    	// current size name that is in the database
    	$statement = $pdo->prepare("SELECT * FROM tbl_sucursales WHERE s_id=?");
		$statement->execute(array($_REQUEST['id']));
		$result = $statement->fetchAll(PDO::FETCH_ASSOC);
		foreach($result as $row) {
			//$current_size_name = $row['size_name'];
            $s_address = $row['s_address'];
            

		}

		$statement = $pdo->prepare("SELECT * FROM tbl_sucursales WHERE s_address=? and s_address!=?");
    	$statement->execute(array($_POST['s_address'],$s_address));
    	$total = $statement->rowCount();							
    	if($total) {
    		$valid = 0;
        	$error_message .= 'Ya existe una sucursal con este nombre.<br>';
    	}
    }

    if($valid == 1) {    	
		// updating into the database
		$statement = $pdo->prepare("UPDATE tbl_sucursales SET 
                                                            s_name=?, 
                                                            s_address=?, 
                                                            s_hours=?, 
                                                            s_lat=?, 
                                                            s_long=?, 
                                                            s_phones=?, 
                                                            s_active=?,
                                                            s_condicion_iva=?,
                                                            s_cuit_cuil=?
                                                             WHERE s_id=?;");
		$statement->execute(array(
                        $_POST['s_name'],
                        $_POST['s_address'], 
                        $_POST['s_hours'], 
                        $_POST['s_lat'], 
                        $_POST['s_long'], 
                        $_POST['s_phones'], 
                        $_POST['s_active'], 
                        $_POST['s_condicion_iva'],
                        $_POST['s_cuit_cuil'],
                        $_REQUEST['id']));

    	$success_message = 'Se actualizó correctamente la sucursal.';
    }
}
?>

<?php
if(!isset($_REQUEST['id'])) {
	header('location: logout.php');
	exit;
} else {
	// Check the id is valid or not
	$statement = $pdo->prepare("SELECT * FROM tbl_sucursales WHERE s_id=?");
	$statement->execute(array($_REQUEST['id']));
	$total = $statement->rowCount();
	$result = $statement->fetchAll(PDO::FETCH_ASSOC);
	if( $total == 0 ) {
		header('location: logout.php');
		exit;
	}
}
?>

<section class="content-header">
	<div class="content-header-left">
		<h1>Editar Sucursal</h1>
	</div>
	<div class="content-header-right">
		<a href="sucursales.php" class="btn btn-primary btn-sm">Ver Todas</a>
	</div>
</section>


<?php							
foreach ($result as $row) {
	$s_name = $row['s_name'];
    $s_address = $row['s_address'];
    $s_hours = $row['s_hours'];
    $s_lat = $row['s_lat'];
    $s_long = $row['s_long'];
    $s_phones = $row['s_phones'];
    $s_active = $row['s_active'];

    $s_condicion_iva = $row['s_condicion_iva'];
    $s_cuit_cuil = $row['s_cuit_cuil'];
}
?>

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
                    <label for="" class="col-sm-2 control-label"><i class="fa fa-location-card"></i> Nombre de sucursal <span>*</span></label>
                    <div class="col-sm-4">
                        <input type="text" class="form-control" name="s_name" placeholder="Av. Bicentenario" value="<?php echo $s_name; ?>">
                    </div>
                </div>
                <div class="form-group">
                    <label for="" class="col-sm-2 control-label"><i class="fa fa-location-arrow"></i> Dirección de sucursal <span>*</span></label>
                    <div class="col-sm-4">
                        <input type="text" class="form-control" name="s_address" placeholder="Av. Bicentenario 123" value="<?php echo $s_address; ?>">
                    </div>
                </div>
                
                <div class="form-group">
                    <label for="" class="col-sm-2 control-label"><i class="fa fa-phone"></i> Teléfono </label>
                    <div class="col-sm-4">
                        <input type="text" class="form-control" name="s_phones" placeholder="3875123456" value="<?php echo $s_phones; ?>">
                    </div>
                </div>

                <div class="form-group">
                    <label for="" class="col-sm-2 control-label"><i class="fa fa-clock-o"></i> Horarios </label>
                    <div class="col-sm-4">
                        <input type="text" class="form-control" name="s_hours" placeholder="Lu - Sáb, de 09:00 a 21:00" value="<?php echo $s_hours; ?>">
                    </div>
                </div>
                
                <div class="form-group">
                    <label for="" class="col-sm-2 control-label"><i class="fa fa-map-marker"></i> Latitud </label>
                    <div class="col-sm-4">
                        <input type="text" class="form-control" name="s_lat" placeholder="-24.7859000" value="<?php echo $s_lat; ?>">
                    </div>
                </div>

                <div class="form-group">
                    <label for="" class="col-sm-2 control-label"><i class="fa fa-map-marker"></i> Longitud </label>
                    <div class="col-sm-4">
                        <input type="text" class="form-control" name="s_long" placeholder="-65.4116600" value="<?php echo $s_long; ?>">
                    </div>
                </div>

                <div class="form-group">
                    <label for="" class="col-sm-2 control-label"><i class="fa fa-line-chart"></i> Condición IVA </label>
                    <div class="col-sm-4">
                        <input type="text" class="form-control" name="s_condicion_iva" placeholder="I.V.A. Responsable Inscripto" value="<?php echo $s_condicion_iva; ?>">
                    </div>
                </div>

                <div class="form-group">
                    <label for="" class="col-sm-2 control-label"><i class="fa fa-id-card-o"></i> CUIT / CUIL </label>
                    <div class="col-sm-4">
                        <input type="text" class="form-control" name="s_cuit_cuil" placeholder="00-00000000-0" value="<?php echo $s_cuit_cuil; ?>">
                    </div>
                </div>

                <div class="form-group">
                    <label for="" class="col-sm-3 control-label">¿Activo?</label>
                    <div class="col-sm-8">
                        <select name="s_active" class="form-control" style="width:auto;">
                            <option value="1" <?php if($s_active == '1'){ echo 'selected';} ?>>Sí</option>
                            <option value="0" <?php if($s_active == '0'){ echo 'selected';} ?>>No</option>
                        </select> 
                    </div>
                </div>

                <div class="form-group">
                	<label for="" class="col-sm-2 control-label"></label>
                    <div class="col-sm-6">
                      <button type="submit" class="btn btn-success pull-left" name="form1">Actualizar</button>
                    </div>
                </div>
                

                <!-- 
                    $s_address = $row['s_address'];
                    $s_hours = $row['s_hours'];
                    $s_lat = $row['s_lat'];
                    $s_long = $row['s_long'];
                    $s_phones = $row['s_phones'];
                    $s_active = $row['s_active']; 
                -->
            </div>

        </div>

        </form>



    </div>
  </div>

</section>

<div class="modal fade" id="confirm-delete" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                <h4 class="modal-title" id="myModalLabel">Confirmación de eliminación</h4>
            </div>
            <div class="modal-body">
                ¿Realmente quiere borrar la sucursal? (Esto implica que los productos asociados a ella no estén relacionados a una sucursal)
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default" data-dismiss="modal">Cancelar</button>
                <a class="btn btn-danger btn-ok">Eliminar</a>
            </div>
        </div>
    </div>
</div>

<?php require_once('footer.php'); ?>