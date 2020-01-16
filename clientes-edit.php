<?php require_once('header.php'); ?>

<?php
if(isset($_POST['form1'])) {
	$valid = 1;


    if(empty($_POST['c_apellido'])) {
        $valid = 0;
        $error_message .= "Debe ingresar un Apellido.<br>";
    }

    if(empty($_POST['c_nombre'])) {
        $valid = 0;
        $error_message .= "Debe ingresar al menos un nombre.<br>";
    }

    /*
    if(empty($_POST['c_tipo_doc'])) {
        $valid = 0;
        $error_message .= "Debe elegir tipo de documento.<br>";
    }

    if(empty($_POST['c_nro_doc'])) {
        $valid = 0;
        $error_message .= "Debe ingresar número de documento.<br>";
    }

    if(empty($_POST['c_genero'])) {
        $valid = 0;
        $error_message .= "Debe elegir el género para generar CUIL/CUIT.<br>";
    }

    if(empty($_POST['c_email'])) {
        $valid = 0;
        $error_message .= "Debe ingresar un email válido.<br>";
    }

    if(empty($_POST['c_cuit'])) {
        $valid = 0;
        $error_message .= "Debe ingresar CUIT/CUIL.<br>";
    }
    */

    /*if(empty($_POST['c_id_provincia'])) {
        $valid = 0;
        $error_message .= "Debe seleccionar una provincia.<br>";
    }

    if(empty($_POST['c_id_localidad'])) {
        $valid = 0;
        $error_message .= "Debe seleccionar una localidad.<br>";
    }

    if(empty($_POST['c_cp'])) {
        $valid = 0;
        $error_message .= "Debe ingresar Código Postal.<br>";
    }*/


    if($valid == 1) {

    	

		//Saving data into the main table tbl_cliente
		/*$statement = $pdo->prepare("INSERT INTO tbl_cliente (
										c_id,
										c_apellido,
										c_nombre,
										c_tipo_doc,
										c_nro_doc,
										c_genero,
										c_email,
										c_tel,
										c_cel,
										c_razon_social,
										c_apodo,
										c_id_cond_iva,
										c_cuit,
										c_id_provincia,
										c_id_localidad,
										c_cp,
										c_calle,
										c_calle_nro,
										c_barrio,
										c_activo
									) VALUES (NULL,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?);");
		$statement->execute(array(
										$_POST['c_apellido'],
										$_POST['c_nombre'],
										$_POST['c_tipo_doc'],
										$_POST['c_nro_doc'],
										$_POST['c_genero'],
										$_POST['c_email'],
										$_POST['c_tel'],
										$_POST['c_cel'],
										$_POST['c_razon_social'],
										$_POST['c_apodo'],
										$_POST['c_id_cond_iva'],
										$_POST['c_cuit'],
										$_POST['c_id_provincia'],
										$_POST['c_id_localidad'],
										$_POST['c_cp'],
										$_POST['c_calle'],
										$_POST['c_calle_nro'],
										$_POST['c_barrio'],
										$_POST['c_activo']
									));*/

		/*$statement = $pdo->prepare("INSERT INTO tbl_cliente (
										c_id,
										c_apellido,
										c_nombre,
										c_tipo_doc,
										c_nro_doc,
										c_genero,
										c_email,
										c_tel,
										c_cel,
										c_razon_social,
										c_apodo,
										c_id_cond_iva,
										c_cuit,
										c_id_provincia,
										c_id_localidad,
										c_cp,
										c_calle,
										c_calle_nro,
										c_barrio,
										c_activo
									) VALUES (NULL,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?);");
		$statement->execute(array(
										isset($_POST['c_apellido'])? $_POST['c_apellido'] : '',
										isset($_POST['c_nombre'])? $_POST['c_nombre'] : '',
										isset($_POST['c_tipo_doc'])? $_POST['c_tipo_doc'] : '',
										isset($_POST['c_nro_doc'])? $_POST['c_nro_doc'] : '',
										isset($_POST['c_genero'])? $_POST['c_genero'] : '',
										isset($_POST['c_email'])? $_POST['c_email'] : '',
										isset($_POST['c_tel'])? $_POST['c_tel'] : '',
										isset($_POST['c_cel'])? $_POST['c_cel'] : '',
										isset($_POST['c_razon_social'])? $_POST['c_razon_social'] : '',
										isset($_POST['c_apodo'])? $_POST['c_apodo'] : '',
										isset($_POST['c_id_cond_iva'])? $_POST['c_id_cond_iva'] : '',
										isset($_POST['c_cuit'])? $_POST['c_cuit'] : '',
										isset($_POST['c_id_provincia'])? $_POST['c_id_provincia'] : '',
										isset($_POST['c_id_localidad'])? $_POST['c_id_localidad'] : '',
										isset($_POST['c_cp'])? $_POST['c_cp'] : '',
										isset($_POST['c_calle'])? $_POST['c_calle'] : '',
										isset($_POST['c_calle_nro'])? $_POST['c_calle_nro'] : '',
										isset($_POST['c_barrio'])? $_POST['c_barrio'] : '',
										isset($_POST['c_activo'])? $_POST['c_activo'] : ''
									));*/
		$statement = $pdo->prepare("UPDATE `tbl_cliente` SET 
														`c_apellido` = ?,
														`c_nombre` = ?,
														`c_tipo_doc` = ?,
														`c_nro_doc` = ?,
														`c_genero` = ?,
														`c_email` = ?,
														`c_tel` = ?,
														`c_cel` = ?,
														`c_razon_social` = ?,
														`c_apodo` = ?,
														`c_id_cond_iva` = ?,
														`c_cuit` = ?,
														`c_id_provincia` = ?,
														`c_id_localidad` = ?,
														`c_cp` = ?,
														`c_calle` = ?,
														`c_calle_nro` = ?,
														`c_barrio` = ?,
														`c_activo` = ? 
														WHERE c_id = ?;");
		/*
			Array ( [0] => Villaflor [1] => Mario e [2] => 1 [3] => 35123123 [4] => M [5] => mariobigboy@gmail.com [6] => [7] => 93874106731 [8] => [9] => [10] => 5 [11] => 20351231237 [12] => [13] => [14] => 4400 [15] => Mar Ártico 1395 [16] => [17] => [18] => 1 [19] => 25 ) 1
		*/
        $arr = array(
										isset($_POST['c_apellido'])? $_POST['c_apellido'] : " ",
										isset($_POST['c_nombre'])? $_POST['c_nombre'] : " ",
										isset($_POST['c_tipo_doc'])? $_POST['c_tipo_doc'] : " ",
										isset($_POST['c_nro_doc'])? $_POST['c_nro_doc'] : 1,
										isset($_POST['c_genero'])? $_POST['c_genero'] : " ",
										isset($_POST['c_email'])? $_POST['c_email'] : " ",
										isset($_POST['c_tel'])? $_POST['c_tel'] : " ",
										isset($_POST['c_cel'])? $_POST['c_cel'] : " ",
										isset($_POST['c_razon_social'])? $_POST['c_razon_social'] : " ",
										isset($_POST['c_apodo'])? $_POST['c_apodo'] : " ",
										isset($_POST['c_id_cond_iva'])? $_POST['c_id_cond_iva'] : " ",
										isset($_POST['c_cuit'])? $_POST['c_cuit'] : " ",
										isset($_POST['c_id_provincia'])? $_POST['c_id_provincia'] : 0,
										isset($_POST['c_id_localidad'])? $_POST['c_id_localidad'] : 0,
										isset($_POST['c_cp'])? $_POST['c_cp'] : " ",
										isset($_POST['c_calle'])? $_POST['c_calle'] : " ",
										isset($_POST['c_calle_nro'])? $_POST['c_calle_nro'] : " ",
										isset($_POST['c_barrio'])? $_POST['c_barrio'] : " ",
										isset($_POST['c_activo'])? $_POST['c_activo'] : "0",
										$_GET['id'],
								);
		$statement->execute($arr);
	
    	$success_message = 'Cliente agregado correctamente.';
    }
}
?>


<?php 
	if(!isset($_REQUEST['id'])){
		header('location: logout.php');
		exit;
	}else{
		$statement = $pdo->prepare("SELECT * FROM tbl_cliente WHERE c_id=?");
		$statement->execute(array($_REQUEST['id']));
		$total = $statement->rowCount();
		$result = $statement->fetchAll(PDO::FETCH_ASSOC);
		if($total == 0 ){
			header('location: logout.php');
			exit;
		}
	}
 ?>

<section class="content-header">
	<div class="content-header-left">
		<h1>Editar Cliente</h1>
	</div>
	<div class="content-header-right">
		<a href="clientes.php" class="btn btn-primary btn-sm">Ver Todos</a>
		<?php #echo print_r($arr); ?>
	</div>
</section>

<?php 
	//$statement = $pdo->prepare("SELECT * FROM tbl_cliente WHERE c_id=?;");
	//$statement->execute(array($_REQUEST['id']));
	//$result = $statement->fetchAll(PDO::FETCH_ASSOC);
	foreach($result as $row){
		$c_apellido = $row['c_apellido'];
		$c_nombre = $row['c_nombre'];
		$c_tipo_doc = $row['c_tipo_doc'];
		$c_nro_doc = $row['c_nro_doc'];
		$c_genero = $row['c_genero'];
		$c_email = $row['c_email'];
		$c_tel = $row['c_tel'];
		$c_cel = $row['c_cel'];
		$c_razon_social = $row['c_razon_social'];
		$c_apodo = $row['c_apodo'];
		$c_id_cond_iva = $row['c_id_cond_iva'];
		$c_cuit = $row['c_cuit'];
		$c_id_provincia = $row['c_id_provincia'];
		$c_id_localidad = $row['c_id_localidad'];
		$c_cp = $row['c_cp'];
		$c_calle = $row['c_calle'];
		$c_calle_nro = $row['c_calle_nro'];
		$c_barrio = $row['c_barrio'];
		$c_activo = $row['c_activo'];
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
						<h3>Datos Cliente:</h3>
						<div class="form-group">
							<label for="" class="col-sm-3 control-label"><i class="fa fa-user"></i> Apellidos <span>*</span></label>
							<div class="col-sm-4">
								<input type="text" name="c_apellido" class="form-control" placeholder="Picapiedras" value="<?php echo $c_apellido; ?>">
							</div>
						</div>

						<div class="form-group">
							<label for="" class="col-sm-3 control-label"><i class="fa fa-user"></i> Nombres <span>*</span></label>
							<div class="col-sm-4">
								<input type="text" name="c_nombre" class="form-control" placeholder="Pedro" value="<?php echo $c_nombre; ?>">
							</div>
						</div>
						
						<div class="form-group">
							<label for="" class="col-sm-3 control-label"><i class="fa fa-id-card-o"></i> Tipo documento <span>*</span></label>
							<div class="col-sm-4">
								<select name="c_tipo_doc" id="selTiposDni" class="form-control">
									<option value="" selected>Seleccione un tipo</option>
									<?php 
										$statement = $pdo->prepare("SELECT * FROM tbl_tipo_docs");
										$statement->execute();
										$result = $statement->fetchAll(PDO::FETCH_ASSOC);
										foreach($result as $row){

									?>
											<option value="<?php echo $row['t_id']; ?>" <?php if($row['t_id']==$c_tipo_doc){echo ' selected';} ?>><?php echo $row['t_name']; ?></option>
									<?php
										}
									 ?>
								</select>
							</div>
						</div>

						<div class="form-group">
							<label for="" class="col-sm-3 control-label"><i class="fa fa-id-card-o"></i> Nro Documento <span>*</span></label>
							<div class="col-sm-4">
								<input type="text" name="c_nro_doc" class="form-control" placeholder="20123456" value="<?php echo $c_nro_doc; ?>">
							</div>
						</div>
						
						<div class="form-group">
							<label for="" class="col-sm-3 control-label"><i class="fa fa-venus-mars"></i> Género <span>*</span></label>
							<div class="col-sm-8">
								<select name="c_genero" class="form-control" style="width:auto;">
									<option value="">Seleccione Género</option>
									<option value="M" <?php if($c_genero=="M"){echo ' selected';} ?>>Masculino</option>
									<option value="F" <?php if($c_genero=="F"){echo ' selected';} ?>>Femenino</option>
									<option value="O" <?php if($c_genero=="O"){echo ' selected';} ?>>Otro</option>
								</select> 
							</div>
						</div>

						<div class="form-group">
							<label for="" class="col-sm-3 control-label"><i class="fa fa-at"></i> Email <span>*</span></label>
							<div class="col-sm-4">
								<input type="text" name="c_email" class="form-control" placeholder="pedropicapiedras@gmail.com" value="<?php echo $c_email; ?>">
							</div>
						</div>

						<div class="form-group">
							<label for="" class="col-sm-3 control-label"><i class="fa fa-phone"></i> Teléfono </label>
							<div class="col-sm-4">
								<input type="text" name="c_tel" class="form-control" placeholder="4-221234" value="<?php echo $c_tel; ?>">
							</div>
						</div>

						<div class="form-group">
							<label for="" class="col-sm-3 control-label"><i class="fa fa-mobile"></i> Celular </label>
							<div class="col-sm-4">
								<input type="text" name="c_cel" class="form-control" placeholder="(387) 154123123" value="<?php echo $c_cel; ?>">
							</div>
						</div>

						<div class="form-group">
							<label for="" class="col-sm-3 control-label"><!--<i class="fa fa-phone"></i>--> Razón Social </label>
							<div class="col-sm-4">
								<input type="text" name="c_razon_social" class="form-control" placeholder="Razón Social" value="<?php echo $c_razon_social; ?>">
							</div>
						</div>

						<div class="form-group">
							<label for="" class="col-sm-3 control-label"><i class="fa fa-user-o"></i> Apodo / Nombre fantasía </label>
							<div class="col-sm-4">
								<input type="text" name="c_apodo" class="form-control" placeholder="Pedrito" value="<?php echo $c_apodo; ?>">
							</div>
						</div>

						<div class="form-group">
							<label for="" class="col-sm-3 control-label"><i class="fa fa-line-chart"></i> Condición IVA </label>
							<div class="col-sm-4">
								<select name="c_id_cond_iva" id="sel_Cond_Iva" class="form-control">
									<option value="" selected>Seleccione un tipo</option>
									<?php 
										$statement = $pdo->prepare("SELECT * FROM tbl_condicion_iva;");
										$statement->execute();
										$result = $statement->fetchAll(PDO::FETCH_ASSOC);
										foreach($result as $row){

									?>
											<option value="<?php echo $row['c_id']; ?>" <?php if($row['c_id']==$c_id_cond_iva){echo ' selected';} ?>><?php echo $row['c_name']; ?></option>
									<?php
										}
									 ?>
								</select>
							</div>
						</div>
						
						<div class="form-group">
							<label for="" class="col-sm-3 control-label"><i class="fa fa-id-card-o"></i> CUIT / CUIL <span>*</span></label>
							<div class="col-sm-4">
								<input type="text" name="c_cuit" class="form-control" placeholder="20201234567" value="<?php echo $c_cuit; ?>">
							</div>
						</div>
						
						<hr>
						<h3>Datos de Domicilio de Cliente</h3>
						
						<div class="form-group">
							<label for="" class="col-sm-3 control-label"><i class="fa fa-globe"></i> Provincia <span>*</span></label>
							<div class="col-sm-4">
								<select name="c_id_provincia" class="form-control selProvincias">
									<option value="" selected>Seleccione una provincia</option>
									<?php 
										$statement = $pdo->prepare("SELECT * FROM tbl_provincia;");
										$statement->execute();
										$result = $statement->fetchAll(PDO::FETCH_ASSOC);
										foreach($result as $row){

									?>
											<option value="<?php echo $row['p_id']; ?>" <?php if($row['p_id']==$c_id_provincia){echo ' selected';} ?>><?php echo $row['p_name']; ?></option>
									<?php
										}
									 ?>
								</select>
							</div>
						</div>

						<div class="form-group">
							<label for="" class="col-sm-3 control-label"><i class="fa fa-globe"></i> Localidad <span>*</span></label>
							<div class="col-sm-4">
								<select name="c_id_localidad" class="form-control selLocalidades">
									<option value="" selected>Seleccione una Localidad</option>
									<?php 
										$statement = $pdo->prepare("SELECT * FROM tbl_localidad WHERE l_p_id=?;");
										$statement->execute(array($c_id_provincia));
										$result = $statement->fetchAll(PDO::FETCH_ASSOC);
										foreach ($result as $row) {
									?>
											<option value="<?php echo $row['l_id'] ?>" <?php if($row['l_id']==$c_id_localidad){ echo ' selected';} ?>><?php echo $row['l_name'] ?></option>		
									<?php
											
										}
									?>
								</select>
							</div>
						</div>

						<div class="form-group">
							<label for="" class="col-sm-3 control-label"><i class="fa fa-globe"></i> C.P. <span>*</span></label>
							<div class="col-sm-4">
								<input type="text" name="c_cp" class="form-control" placeholder="4400" value="<?php echo $c_cp; ?>">
							</div>
						</div>

						<div class="form-group">
							<label for="" class="col-sm-3 control-label"><i class="fa fa-road"></i> Calle </label>
							<div class="col-sm-4">
								<input type="text" name="c_calle" class="form-control" placeholder="ej: Av. Bicentenario" value="<?php echo $c_calle; ?>">
							</div>
						</div>

						<div class="form-group">
							<label for="" class="col-sm-3 control-label"><i class="fa fa-map-signs"></i> Nro </label>
							<div class="col-sm-4">
								<input type="text" name="c_calle_nro" class="form-control" placeholder="ej: Av. Bicentenario" value="<?php echo $c_calle_nro; ?>">
							</div>
						</div>

						<div class="form-group">
							<label for="" class="col-sm-3 control-label"><i class="fa fa-map-o"></i> Barrio </label>
							<div class="col-sm-4">
								<input type="text" name="c_barrio" class="form-control" placeholder="ej: Centro" value="<?php echo $c_barrio; ?>">
							</div>
						</div>

						<!--<div class="form-group">
							<label for="" class="col-sm-3 control-label">¿Es Destacado?</label>
							<div class="col-sm-8">
								<select name="p_is_featured" class="form-control" style="width:auto;">
									<option value="0">No</option>
									<option value="1">Sí</option>
								</select> 
							</div>
						</div>-->
						

						<hr>
						<h3>Usuario e-shop:</h3>

						<div class="form-group">
							<label for="" class="col-sm-3 control-label"><i class="fa fa-at"></i> Usuario </label>
							<div class="col-sm-4">
								<input type="text" name="user_email" class="form-control" placeholder="ejemplo@ejemplo.com" value="<?php echo $c_email; ?>">
							</div>
						</div>
						<div class="form-group">
							<label for="" class="col-sm-3 control-label"><i class="fa fa-lock"></i> Contraseña </label>
							<div class="col-sm-4">
								<input type="password" name="user_pass" class="form-control" placeholder="*******">
							</div>
							<label for="" class="col-sm-3 control-label text-left">(Documento por Defecto)</label>
						</div>

						<hr>
						
						<div class="form-group">
							<label for="" class="col-sm-3 control-label"><i class="fa fa-toggle-on"></i> ¿Activo? </label>
							<div class="col-sm-8">
								<select name="c_activo" class="form-control" style="width:auto;">
									<option value="1" <?php if($c_activo == '1'){ echo ' selected';} ?>>Sí</option>
									<option value="0" <?php if($c_activo == '0'){ echo ' selected';} ?>>No</option>
								</select> 
							</div>
						</div>

						<div class="form-group">
							<label for="" class="col-sm-3 control-label"></label>
							<div class="col-sm-6">
								<button type="submit" class="btn btn-success pull-left" name="form1">Guardar</button>
							</div>

						</div>

					</div>
				</div>

			</form>
			<?php 
				if (($_SESSION['user']['role']=="Super Admin") || ($_SESSION['user']['role']=="Admin")) {
					
			 ?>
			<hr>
			 <!--<a href="#" class="btn btn-danger col-lg-12" data-href="clientes-delete.php?id=<?php echo $_GET['id']; ?>" data-toggle="modal" data-target="#confirm-delete">Eliminar</a>-->
			<?php } ?>

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
                <p>¿Está seguro de eliminar este item?</p>
                <p style="color:red;">¡Ten cuidado! Este cliente se eliminará de la base de datos y no podrá ser recuperado.</p>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default" data-dismiss="modal">Cancelar</button>
                <a class="btn btn-danger btn-ok">Eliminar</a>
            </div>
        </div>
    </div>
</div>

<?php require_once('footer.php'); ?>