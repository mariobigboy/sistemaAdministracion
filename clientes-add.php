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

    // if(empty($_POST['c_tipo_doc'])) {
    //     $valid = 0;
    //     $error_message .= "Debe elegir tipo de documento.<br>";
    // }

    // if(empty($_POST['c_nro_doc'])) {
    //     $valid = 0;
    //     $error_message .= "Debe ingresar número de documento.<br>";
    // }

    // if(empty($_POST['c_genero'])) {
    //     $valid = 0;
    //     $error_message .= "Debe elegir el género.<br>";
    // }

    // if(empty($_POST['c_email'])) {
    //     $valid = 0;
    //     $error_message .= "Debe ingresar un email válido.<br>";
    // }

    // if(empty($_POST['c_cuit'])) {
    //     $valid = 0;
    //     $error_message .= "Debe ingresar CUIT/CUIL.<br>";
    // }

    // if(empty($_POST['c_id_provincia'])) {
    //     $valid = 0;
    //     $error_message .= "Debe seleccionar una provincia.<br>";
    // }

    // if(empty($_POST['c_id_localidad'])) {
    //     $valid = 0;
    //     $error_message .= "Debe seleccionar una localidad.<br>";
    // }

    // if(empty($_POST['c_cp'])) {
    //     $valid = 0;
    //     $error_message .= "Debe ingresar Código Postal.<br>";
    // }

    /*if(isset($_POST['account_exist'])){
    	$account_exist = $_POST['account_exist'];
    	$user_email = $_POST['user_email'];
    	$user_pass = $_POST['user_pass'];
    	$valid = 0;
    	$error_message .= "account_exist: ".$account_exist.'<br>';
    	$error_message .= "user_email: ".$user_email.'<br>';
    	$error_message .= "user_pass: ".$user_pass.'<br>';
    }*/

    //chequeamos que el dni no exista:
    // $statement = $pdo->prepare("SELECT * FROM tbl_cliente WHERE c_nro_doc=?;");
    // $statement->execute(array($_POST['c_nro_doc']));
    // $result = $statement->rowCount();
    // if($result>0){
    // 	$valid = 0; 
    // 	$error_message .= "Ya existe un usuario con dicho nro de documento. <br>";
    // }

    //chequeamos que el email no exista:
    // $statement = $pdo->prepare("SELECT * FROM tbl_cliente WHERE c_email=?;");
    // $statement->execute(array($_POST['c_email']));
    // $result = $statement->rowCount();
    // if($result>0){
    // 	$valid = 0; 
    // 	$error_message .= "Ya existe un usuario con dicho email. <br>";
    // }


    $c_nro_doc = isset($_POST['c_nro_doc']) ? $_POST['c_nro_doc'] : "";
	$c_email = isset($_POST['c_email']) ? $_POST['c_email'] : "";
	$c_tel = isset($_POST['c_tel']) ? $_POST['c_tel'] : "";
	$c_cel = isset($_POST['c_cel']) ? $_POST['c_cel'] : "";
	$c_razon_social = isset($_POST['c_razon_social']) ? $_POST['c_razon_social'] : "";
	$c_id_cond_iva = isset($_POST['c_id_cond_iva']) ? $_POST['c_id_cond_iva'] : "";
	$c_cuit = isset($_POST['c_cuit']) ? $_POST['c_cuit'] : "";
	$c_id_provincia = isset($_POST['c_id_provincia']) ? $_POST['c_id_provincia'] : "";
	$c_id_localidad = isset($_POST['c_id_localidad']) ? $_POST['c_id_localidad'] : "";
	$c_cp = isset($_POST['c_cp']) ? $_POST['c_cp'] : "";
	$c_calle = isset($_POST['c_calle']) ? $_POST['c_calle'] : "";
	$c_calle_nro = isset($_POST['c_calle_nro']) ? $_POST['c_calle_nro'] : "";
	$c_barrio = isset($_POST['c_barrio']) ? $_POST['c_barrio'] : "";


    if($valid == 1) {

		//Saving data into the main table tbl_cliente
		$statement = $pdo->prepare("INSERT INTO tbl_cliente (
										c_id,
										c_apellido,
										c_nombre,
										c_nro_doc,
										c_email,
										c_tel,
										c_cel,
										c_razon_social,
										c_id_cond_iva,
										c_cuit,
										c_id_provincia,
										c_id_localidad,
										c_cp,
										c_calle,
										c_calle_nro,
										c_barrio
									) VALUES (NULL,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?);");
		$statement->execute(array(
										strip_tags($_POST['c_apellido']),
										strip_tags($_POST['c_nombre']),
										strip_tags($c_nro_doc),
										strip_tags($c_email),
										strip_tags($c_tel),
										strip_tags($c_cel),
										strip_tags($c_razon_social),
										strip_tags($c_id_cond_iva),
										strip_tags($c_cuit),
										strip_tags($c_id_provincia),
										strip_tags($c_id_localidad),
										strip_tags($c_cp),
										strip_tags($c_calle),
										strip_tags($c_calle_nro),
										strip_tags($c_barrio)
									));

		

		// $id_cliente = $pdo->lastInsertId();
		// //print_r($id_cliente);
		
		// //variables para nuevo usuario en e-shop:

		// $account_exist = isset($_POST['account_exist']) ? $_POST['account_exist'] : 0;
		// $user_email = $_POST['c_email'];
		// $user_pass = $_POST['user_pass'];

		// if($account_exist==1){
		// 	//asocio el cliente con el usuario e-shop:

		// 	//busco el usuario con mismo email:
		// 	$statement = $pdo->prepare("SELECT * FROM tbl_customer WHERE cust_email = ?;");
		// 	$statement->execute(array($user_email));
		// 	$result = $statement->fetchAll(PDO::FETCH_ASSOC);

		// 	foreach($result as $row){
		// 		$id_customer = $row['cust_id'];
		// 	}

			
		// 	//actualizamos el customer:
		// 	$statement = $pdo->prepare("UPDATE tbl_customer SET cust_id_cliente = ? WHERE cust_email = ?");
		// 	$statement->execute(array($id_cliente, $user_email));
			
		// 	//actualizamos el cliente:
		// 	$statement = $pdo->prepare("UPDATE tbl_cliente SET c_id_customer = ? WHERE c_email = ?");
		// 	$statement->execute(array($id_customer, $user_email));
			


		// 	$success_message .= "Cliente asociado correctamente al usuario de e-shop. <br>";
		// }else{
		// 	//creo el usuario e-shop:
		// 	$token = md5(time());
  //       	$cust_datetime = date('Y-m-d h:i:s');
  //       	$cust_timestamp = time();

		// 	$statement = $pdo->prepare("INSERT INTO tbl_customer (
		// 											cust_name, 
		// 											cust_email, 
		// 											cust_phone, 
		// 											cust_address, 
		// 											cust_zip, 
		// 											cust_password, 
		// 											cust_token, 
		// 											cust_datetime, 
		// 											cust_timestamp, 
		// 											cust_status, 
		// 											cust_id_cliente
		// 											) VALUES (?,?,?,?,?,?,?,?,?,?,?)");

		// 	$address = strip_tags($_POST['c_calle']).' '.strip_tags($_POST['c_calle_nro']).' - '.strip_tags($_POST['c_barrio']);

		// 	$statement->execute(array(
		// 				strip_tags($_POST['c_nombre']),
		// 				strip_tags($_POST['c_email']),
		// 				strip_tags($_POST['c_cel']),
		// 				$address,
		// 				strip_tags($_POST['c_cp']),
		// 				md5($user_pass),
		// 				$token,
		// 				$cust_datetime,
		// 				$cust_timestamp,
		// 				1,
		// 				$id_cliente
		// 				));

		// 	$id_customer = $pdo->lastInsertId();
		// 	//rint_r($id_customer);

		// 	//Actualizamos el c_id_customer del cliente para asociarlo a la cuenta e-shop
		// 	$statement = $pdo->prepare("UPDATE tbl_cliente SET c_id_customer = ? WHERE c_id = ?");
		// 	$statement->execute($id_customer, $id_cliente);

		// 	$success_message .= "Cliente y usuario e-shop creados correctamente. <br>";
		// }
        
	
    	$success_message .= 'Cliente agregado correctamente. <br>';
    }
}
?>

<section class="content-header">
	<div class="content-header-left">
		<h1>Nuevo Cliente</h1>
	</div>
	<div class="content-header-right">
		<a href="clientes.php" class="btn btn-primary btn-sm">Ver Todos</a>
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
						<h3>Datos Cliente:</h3>
						<div class="form-group">
							<label for="" class="col-sm-3 control-label"><i class="fa fa-user"></i> Apellidos <span>*</span></label>
							<div class="col-sm-4">
								<input type="text" name="c_apellido" class="form-control" placeholder="Picapiedras">
							</div>
						</div>

						<div class="form-group">
							<label for="" class="col-sm-3 control-label"><i class="fa fa-user"></i> Nombres <span>*</span></label>
							<div class="col-sm-4">
								<input type="text" name="c_nombre" class="form-control" placeholder="Pedro">
							</div>
						</div>
						
							
						<div class="form-group">
							<label for="" class="col-sm-3 control-label"><i class="fa fa-id-card-o"></i> Nro Documento </label>
							<div class="col-sm-4">
								<input type="text" name="c_nro_doc" class="form-control" placeholder="20123456">
							</div>
						</div>
						
						<!-- <div class="form-group">
							<label for="" class="col-sm-3 control-label"><i class="fa fa-venus-mars"></i> Género </label>
							<div class="col-sm-8">
								<select name="c_genero" class="form-control" style="width:auto;">
									<option value="">Seleccione Género</option>
									<option value="M">Masculino</option>
									<option value="F">Femenino</option>
									<option value="O">Otro</option>
								</select> 
							</div>
						</div> -->

						<div class="form-group">
							<label for="" class="col-sm-3 control-label"><i class="fa fa-at"></i> Email </label>
							<div class="col-sm-4">
								<input type="text" name="c_email" class="form-control" placeholder="pedropicapiedras@gmail.com">
							</div>
							<label id="loader-email" for="" class="col-sm-2" style="padding: 0;" hidden>
								<i class="fa fa-spinner fa-spin" style="margin-top:10px;"></i>
							</label>
						</div>

						<!-- <div class="form-group">
							<label for="" class="col-sm-3 control-label"><i class="fa fa-phone"></i> Teléfono </label>
							<div class="col-sm-4">
								<input type="text" name="c_tel" class="form-control" placeholder="4-221234">
							</div>
						</div> -->

						<div class="form-group">
							<label for="" class="col-sm-3 control-label"><i class="fa fa-mobile"></i> Celular </label>
							<div class="col-sm-4">
								<input type="text" name="c_cel" class="form-control" placeholder="(387) 154123123">
							</div>
						</div>

						<div class="form-group">
							<label for="" class="col-sm-3 control-label"><!--<i class="fa fa-phone"></i>--> Razón Social </label>
							<div class="col-sm-4">
								<input type="text" name="c_razon_social" class="form-control" placeholder="Razón Social">
							</div>
						</div>

						<!-- <div class="form-group">
							<label for="" class="col-sm-3 control-label"><i class="fa fa-user-o"></i> Apodo / Nombre fantasía </label>
							<div class="col-sm-4">
								<input type="text" name="c_apodo" class="form-control" placeholder="Pedrito">
							</div>
						</div> -->

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
											<option value="<?php echo $row['c_id']; ?>"><?php echo $row['c_name']; ?></option>
									<?php
										}
									 ?>
								</select>
							</div>
						</div>
						
						<div class="form-group">
							<label for="" class="col-sm-3 control-label"><i class="fa fa-id-card-o"></i> CUIT / CUIL </label>
							<div class="col-sm-4">
								<input type="text" name="c_cuit" class="form-control" placeholder="20201234567">
							</div>
						</div>
						
						<hr>
						<h3>Datos de Domicilio de Cliente</h3>
						
						<div class="form-group">
							<label for="" class="col-sm-3 control-label"><i class="fa fa-globe"></i> Provincia </label>
							<div class="col-sm-4">
								<input name="c_id_provincia" class="form-control" value="Salta" >
									
							</div>
						</div>

						<div class="form-group">
							<label for="" class="col-sm-3 control-label"><i class="fa fa-globe"></i> Localidad </label>
							<div class="col-sm-4">
								<input name="c_id_localidad" class="form-control" value="Salta">
									
							</div>
						</div>

						<div class="form-group">
							<label for="" class="col-sm-3 control-label"><i class="fa fa-globe"></i> C.P.</label>
							<div class="col-sm-4">
								<input type="text" name="c_cp" class="form-control" value="4400">
							</div>
						</div>

						<div class="form-group">
							<label for="" class="col-sm-3 control-label"><i class="fa fa-road"></i> Calle </label>
							<div class="col-sm-4">
								<input type="text" name="c_calle" class="form-control" placeholder="ej: Av. Bicentenario">
							</div>
						</div>

						<div class="form-group">
							<label for="" class="col-sm-3 control-label"><i class="fa fa-map-signs"></i> Nro </label>
							<div class="col-sm-4">
								<input type="text" name="c_calle_nro" class="form-control" placeholder="ej: Av. Bicentenario">
							</div>
						</div>

						<div class="form-group">
							<label for="" class="col-sm-3 control-label"><i class="fa fa-map-o"></i> Barrio </label>
							<div class="col-sm-4">
								<input type="text" name="c_barrio" class="form-control" placeholder="ej: Centro">
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
						

						<div id="account-e-shop">
							<hr>
							<!-- <h3>Usuario E-Shop:</h3>
							<input type="hidden" name="account_exist" value="0">
							<div class="form-group">
								<label for="" class="col-sm-3 control-label"><i class="fa fa-at"></i> Usuario </label>
								<div class="col-sm-4">
									<input type="text" name="user_email" class="form-control" placeholder="ejemplo@ejemplo.com">
								</div>
							</div>
							<div class="form-group">
								<label for="" class="col-sm-3 control-label"><i class="fa fa-lock"></i> Contraseña </label>
								<div class="col-sm-4">
									<input type="password" name="user_pass" class="form-control" placeholder="*******">
								</div>
								<label for="" class="col-sm-3 text-left" style="margin-top: 7px;">(Documento por Defecto)</label>
							</div>
						</div>

						<hr>
						
						<div class="form-group">
							<label for="" class="col-sm-3 control-label"><i class="fa fa-toggle-on"></i> ¿Activo? </label>
							<div class="col-sm-8">
								<select name="c_activo" class="form-control" style="width:auto;">
									<option value="1">Sí</option>
									<option value="0">No</option>
								</select> 
							</div>
						</div> -->

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
	// $(".selProvincias").on('change',function(){
	// 		$('.selLocalidades').attr('disabled', 'true');
	// 		var id=$(this).val();
	// 		var dataString = 'id='+ id;
	// 		$.ajax
	// 		({
	// 			type: "POST",
	// 			url: "get-localidades.php",
	// 			data: dataString,
	// 			cache: false,
	// 			success: function(html)
	// 			{
	// 				$(".selLocalidades").html(html);
	// 				$('.selLocalidades').removeAttr('disabled');
	// 			}
	// 		});			
	// 	});

	// 	$('select[name="c_genero"]').on('change', function(){
	// 		var $este = $(this);
	// 		var $dni = $('input[name=c_nro_doc]').val();
	// 		$('input[name=c_cuit]').val(get_cuil_cuit($dni ,$este.val()));
	// 	});

		
	// 	$('input[name="c_email"]').on('change', function(){
	// 		var email = $(this).val();
	// 		$('#loader-email').fadeIn();
	// 		$.ajax({
	// 			url: 'checkEmail.php',
	// 			data: 'email='+email,
	// 			method: 'GET',
	// 			success: function(data){
	// 				console.log(data);
	// 				if(data.existe==0){
	// 					$('input[name="user_email"]').val(email);
	// 					$('input[name=account_exist]').val(0);
	// 					$('#account-e-shop').fadeIn();
	// 				}else{
						
	// 					$('input[name=account_exist]').val(1);
	// 					$('#account-e-shop').fadeOut();
	// 				}
	// 				$('#loader-email').fadeOut();
	// 			},
	// 			error: function(error){
	// 				console.log("error: " + error);
	// 				$('#loader-email').fadeOut();
	// 			}
	// 		});
	// 	});

	// 	$('input[name="c_nro_doc"]').on('change', function(){
	// 		$('input[name="user_pass"]').val($(this).val());
	// 	});

		
</script>