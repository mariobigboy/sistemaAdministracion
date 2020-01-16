<?php require_once('header.php'); ?>

<?php
$id = isset($_GET['id']) ? $_GET['id'] : "";

if(isset($_POST['form1'])) {
	$valid = 1;


    if(empty($_POST['full_name'])) {
        $valid = 0;
        $error_message .= "Debe ingresar un nombre de Usuario.<br>";
    }

    if(empty($_POST['email'])) {
        $valid = 0;
        $error_message .= "Debe ingresar un email válido.<br>";
    }

    if(empty($_POST['sucursal'])) {
        $valid = 0;
        $error_message .= "Debe elegir una sucursal.<br>";
    }

    if(empty($_POST['role'])) {
        $valid = 0;
        $error_message .= "Debe elegir un rol.<br>";
    }


    if($valid == 1) {
    	$statement = $pdo->prepare("UPDATE tbl_user SET full_name=?, email=?, role=?, sucursal= ?, status = ? WHERE id = ?");

    	$statement->execute(array(
			strip_tags($_POST['full_name']),
			strip_tags($_POST['email']),
			strip_tags($_POST['role']),
			strip_tags($_POST['sucursal']),
			strip_tags($_POST['status']),
			strip_tags($id)
			
		));
		//$result = $statement->fetchAll(PDO::FETCH_ASSOC);

		$success_message = 'Usuario actualizado correctamente.';
    }
}
?>



<section class="content-header">
	<div class="content-header-left">
		<h1>Editar Usuarios</h1>
	</div>
	<div class="content-header-right">
		<a href="usuarios.php" class="btn btn-primary btn-sm">Ver Todos</a>
	</div>
</section>

<?php 
	$statement = $pdo->prepare("SELECT * FROM tbl_user WHERE id=?;");
	$statement->execute(array($id));
	$result = $statement->fetchAll(PDO::FETCH_ASSOC);
	foreach($result as $row){
		$full_name = $row['full_name'];
		$email = $row['email'];
		$role = $row['role'];
		$sucursal = $row['sucursal'];
		$status = $row['status'];
		
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
						<h3>Datos Usuario:</h3>
						<div class="form-group">
							<label for="" class="col-sm-3 control-label"><i class="fa fa-user"></i> Nombre <span>*</span></label>
							<div class="col-sm-4">
								<input type="text" name="full_name" class="form-control"  value="<?php echo $full_name; ?>">
							</div>
						</div>

						<div class="form-group">
							<label for="" class="col-sm-3 control-label"><i class="fa fa-user"></i> Email <span>*</span></label>
							<div class="col-sm-4">
								<input type="text" name="email" class="form-control" placeholder="Pedro" value="<?php echo $email; ?>">
							</div>
						</div>
						
						<div class="form-group">
							<label for="" class="col-sm-3 control-label"><i class="fa fa-user"></i> Rol <span>*</span></label>
							<div class="col-sm-4">
								<select name="role" id="EditUserRole" class="form-control">
									
									<?php 
										switch ($role) {
											case 'Admin':
												$op = "<option value='Admin' selected>Administrador</option><option value='Publisher'>Diseñador</option><option value='Empleado'>Empleado</option>";
												break;
											case 'Publisher':
												$op = "<option value='Publisher' selected>Diseñador</option><option value='Empleado'>Empleado</option><option value='Admin'>Administrador</option>";
												break;
											case 'Empleado':
												$op = "<option value='Empleado' selected>Empleado</option><option value='Publisher'>Diseñador</option><option value='Admin'>Administrador</option>";
												break;
											
											default:
												$op = "<option value='' selected>Seleccione un Rol</option><option value='Admin'>Administrador</option><option value='Publisher'>Diseñador</option><option value='Empleado'>Empleado</option>";
												break;
										}
										echo $op;

									?>
									
								</select>
							</div>
						</div>

						
						<div class="form-group">
							<label for="" class="col-sm-3 control-label"><i class="fa fa-building"></i> Sucursal <span>*</span></label>
							<div class="col-sm-8">
								<select name="sucursal" id="select_sucursal" class="form-control" style="width:auto;" required="required">
									
									<?php 
										
										$statement1 = $pdo->prepare("SELECT * FROM tbl_sucursales");
												$statement1->execute();
												$result1 = $statement1->fetchAll(PDO::FETCH_ASSOC);
												$c="<option value='98' id='98'>Todas</option>";
												foreach($result1 as $row1){
													if ($row1['s_id']!= $sucursal) {
														$c.= "<option value=".$row1['s_id']." id=".$row1['s_id'].">".$row1['s_name']."</option>";
													}else{
														$c.= "<option value=".$row1['s_id']." id=".$row1['s_id']." selected>".$row1['s_name']."</option>";
													}
												}
										echo $c;

									?>
									
								</select> 
							</div>
						</div>


						<div class="form-group">
							<label for="" class="col-sm-3 control-label"><i class="fa fa-toggle-on"></i> ¿Activo? </label>
							<div class="col-sm-8">
								<select name="status" class="form-control" style="width:auto;">
									<option value="Active" <?php if($status == 'Active'){ echo ' selected';} ?>>Sí</option>
									<option value="Disabled" <?php if($status == 'Disabled'){ echo ' selected';} ?>>No</option>
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


		</div>
	</div>

</section>

<?php require_once('footer.php'); ?>
<script type="text/javascript">
							$(document).ready(function(){
								var rol = $('#EditUserRole').val();
								if (rol=='Admin') {
									$('#1').prop('disabled', true);
									$('#2').prop('disabled', true);
									$('#3').prop('disabled', true);
									$('#4').prop('disabled', true);
									$('#5').prop('disabled', true);
								}else if(rol=='Publisher'){
									$('#98').val('97');
									$('#1').prop('disabled', true);
									$('#2').prop('disabled', true);
									$('#3').prop('disabled', true);
									$('#4').prop('disabled', true);
									$('#5').prop('disabled', true);

								}else{
									$('#98').prop('disabled', true);
									$('#1').show();
									$('#2').show();
									$('#3').show();
									$('#4').show();
									$('#5').show();
								}
								$('#EditUserRole').on('change', function(){
									var rol = $('#EditUserRole').val();
									if (rol=='Admin') {
										$('#98').text("Todas");
										$('#98').prop('disabled', false);
										$('#98').val('98');
										$('#98').attr('selected', true);
										$('#1').attr('selected', false);
										$('#2').attr('selected', false);
										$('#3').attr('selected', false);
										$('#4').attr('selected', false);
										$('#5').attr('selected', false);
										$('#1').prop('disabled', true);
										$('#2').prop('disabled', true);
										$('#3').prop('disabled', true);
										$('#4').prop('disabled', true);
										$('#5').prop('disabled', true);
									}else if(rol=='Publisher'){
										$('#98').text("Todas");
										$('#98').prop('disabled', false);
										$('#98').val('97');
										$('#98').attr('selected', true);
										$('#1').attr('selected', false);
										$('#2').attr('selected', false);
										$('#3').attr('selected', false);
										$('#4').attr('selected', false);
										$('#5').attr('selected', false);
										$('#1').prop('disabled', true);
										$('#2').prop('disabled', true);
										$('#3').prop('disabled', true);
										$('#4').prop('disabled', true);
										$('#5').prop('disabled', true);
									}else{
										$('#98').text("Seleccione una Sucursal");
										$('#98').prop('disabled', false);
										$('#98').val("");
										$('#98').attr('selected', false);
										$('#1').prop('disabled', false);
										$('#2').prop('disabled', false);
										$('#3').prop('disabled', false);
										$('#4').prop('disabled', false);
										$('#5').prop('disabled', false);
									}
								})
							});
						</script>