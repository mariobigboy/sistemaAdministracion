<?php require_once('header.php'); ?>

<?php


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

    

    /*if(isset($_POST['account_exist'])){
    	$account_exist = $_POST['account_exist'];
    	$user_email = $_POST['user_email'];
    	$user_pass = $_POST['user_pass'];
    	$valid = 0;
    	$error_message .= "account_exist: ".$account_exist.'<br>';
    	$error_message .= "user_email: ".$user_email.'<br>';
    	$error_message .= "user_pass: ".$user_pass.'<br>';
    }*/

    
    //chequeamos que el email no exista:
    $statement = $pdo->prepare("SELECT * FROM tbl_user WHERE email=?;");
    $statement->execute(array($_POST['email']));
    $result = $statement->rowCount();
    if($result>0){
    	$valid = 0; 
    	$error_message .= "Ya existe un usuario con dicho email. <br>";
    }


    if($valid == 1) {

		//Saving data into the main table tbl_cliente
		$statement = $pdo->prepare("INSERT INTO tbl_user (
										id,
										full_name,
										email,
										password,
										photo,
										role,
										sucursal,
										status
									) VALUES (NULL,?,?,?,?,?,?,?);");
		$statement->execute(array(
										strip_tags($_POST['full_name']),
										strip_tags($_POST['email']),
										MD5('empleadosHome'),
										strip_tags('user-14.png'),
										strip_tags($_POST['role']),
										strip_tags($_POST['sucursal']),
										strip_tags($_POST['status']),
										
									));

		$id_usuario = $pdo->lastInsertId();
		//print_r($id_cliente);
		
		$success_message .= 'Usuario creado correctamente. <br>';
    }
}
?>

<section class="content-header">
	<div class="content-header-left">
		<h1>Nuevo Usuario</h1>
	</div>
	<div class="content-header-right">
		<a href="usuarios.php" class="btn btn-primary btn-sm">Ver Todos</a>
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
						<h3>Datos Usuario:</h3>
						<div class="form-group">
							<label for="" class="col-sm-3 control-label"><i class="fa fa-user"></i> Nombre <span>*</span></label>
							<div class="col-sm-4">
								<input type="text" name="full_name" class="form-control" placeholder="Nombre/Alias">
							</div>
						</div>

						<div class="form-group">
							<label for="" class="col-sm-3 control-label"><i class="fa fa-mail"></i> Email <span>*</span></label>
							<div class="col-sm-4">
								<input type="text" name="email" class="form-control" placeholder="empleado@home.com">
							</div>
						</div>
						
						<div class="form-group">
							<label for="" class="col-sm-3 control-label"><i class="fa fa-id-card-o"></i> Sucursal <span>*</span></label>
							<div class="col-sm-4">
								<select name="sucursal" id="sucursal" class="form-control">
									<option value="" selected>Seleccione Sucursal</option>
									<?php 
										$statement = $pdo->prepare("SELECT * FROM tbl_sucursales");
										$statement->execute();
										$result = $statement->fetchAll(PDO::FETCH_ASSOC);
										foreach($result as $row){

									?>
											<option value="<?php echo $row['s_id']; ?>"><?php echo $row['s_name']; ?></option>
									<?php
										}
									 ?>
								</select>
							</div>
						</div>

						<div class="form-group">
							<label for="" class="col-sm-3 control-label"><i class="fa fa-user-o"></i> Rol <span>*</span></label>
							<div class="col-sm-4">
								<select name="role" id="role" class="form-control">
									<option value="" selected>Seleccione un Rol</option>
									<option value="Empleado">Empleado/a</option>
									<option value="Publisher">Diseñador/a</option>
									<option value="Fabrica">Empleado/a Fábrica</option>
								</select>
							</div>
						</div>
						
						<div class="form-group">
							<label for="" class="col-sm-3 control-label"><i class="fa fa-toggle-on"></i> Activo </label>
							<div class="col-sm-8">
								<select name="status" class="form-control" style="width:auto;">
									<option value="Active">Activo</option>
									<option value="Disabled">Desactivado</option>
									
								</select> <br>
								<i>La contraseña por defecto de cualquier nuevo usuario es: <strong>empleadosHome</strong></i>
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