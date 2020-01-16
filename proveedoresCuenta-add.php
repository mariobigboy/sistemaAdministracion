<?php require_once('header.php'); ?>

<?php


	/*if(isset($_POST['form1'])) {
		$valid = 1;


	    if(empty($_POST['nombre'])) {
	        $valid = 0;
	        $error_message .= "Debe ingresar Nombre o Razón Social.<br>";
	    }

	    if(empty($_POST['cuil'])) {
	        $valid = 0;
	        $error_message .= "Debe ingresar al menos un nombre.<br>";
	    }

	    $nombre = isset($_POST['nombre']) ? $_POST['nombre'] : "";
		$cuil = isset($_POST['cuil']) ? $_POST['cuil'] : "";
		$tel = isset($_POST['tel']) ? $_POST['tel'] : "";
		$dir = isset($_POST['dir']) ? $_POST['dir'] : "";
		$correo = isset($_POST['correo']) ? $_POST['correo'] : "";
		


	    if($valid == 1) {

			//Saving data into the main table tbl_cliente
			$statement = $pdo->prepare("INSERT INTO proveedores (
											nombre,
											cuil,
											tel,
											direccion,
											correo
										) VALUES (?,?,?,?,?);");
			$statement->execute(array(
											strip_tags($nombre),
											strip_tags($cuil),
											strip_tags($tel),
											strip_tags($dir),
											strip_tags($correo),
											
										));

			

		
	    	$success_message .= 'Proveedor agregado correctamente. <br>';
	    }
	}*/
?>

<section class="content-header">
	<div class="content-header-left">
		<h1>Nueva Cuenta</h1>
	</div>
	<div class="content-header-right">
		<a href="proveedores-add.php" class="btn btn-primary btn-sm"><i class="fa fa-plus"></i> Nuevo Proveedor</a>
		<a href="proveedoresCuentas.php" class="btn btn-primary btn-sm">Ver Todos</a>
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
						<h3>Seleccione Proveedor:</h3>
						<div class="form-group">
							<label for="" class="col-sm-3 control-label"><i class="fa fa-user"></i> Nombre <span>*</span></label>
							<div class="col-sm-4">
								<input id="txtBuscar" type="text" name="nombre" class="form-control" placeholder="Buscador">
							</div>
						</div>

						<div id="resultSearch" class="form-group" style="display:none;margin-top:-20px;">
							<ul class="col-sm-4 col-sm-offset-3">
								<li class="list-group-item">
							  		<a href="#">Test</a>
								</li>
							  
							</ul>

						</div>

						<!-- <div class="form-group">
							<label for="" class="col-sm-3 control-label"><i class="fa fa-user"></i> Cuil <span>*</span></label>
							<div class="col-sm-4">
								<input type="text" name="cuil" class="form-control" placeholder="XXXXXXXXXX">
							</div>
						</div>
						
							
						<div class="form-group">
							<label for="" class="col-sm-3 control-label"><i class="fa fa-id-card-o"></i> Teléfono </label>
							<div class="col-sm-4">
								<input type="text" name="tel" class="form-control" placeholder="387 0000 00 00">
							</div>
						</div>
						
						<div class="form-group">
							<label for="" class="col-sm-3 control-label"><i class="fa fa-at"></i> Email </label>
							<div class="col-sm-4">
								<input type="text" name="correo" class="form-control" placeholder="pedropicapiedras@gmail.com">
							</div>
							<label id="loader-email" for="" class="col-sm-2" style="padding: 0;" hidden>
								<i class="fa fa-spinner fa-spin" style="margin-top:10px;"></i>
							</label>
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


	$(document).ready(function(){
		$('#txtBuscar').keyup(function(){
			$this = $(this);
			if($this.val().length>=3){
				$.ajax({
					url: 'apiSearchProveedor.php',
					method: 'GET',
					data: 'q='+$this.val(),
					success: function(data){
						$('#resultSearch > ul').empty();

						if(data.length>0){
							$('#resultSearch').show();

							for(var i = 0; i < data.length; i++){
								var obj = data[i];
								$('#resultSearch > ul').append('<a href="verCuentasProveedor.php?id='+obj.id+'"><li class="list-group-item" style="background-color: #8ae3ba">Ingresar Cta de: '+obj.nombre+'</li></a>'); 
							}
						}else{
							$('#resultSearch > ul').append('<li class="list-group-item">No se encontraron proveedores.</li>'); 
							$('#resultSearch').show();
						}
					},
					error: function(error){
						console.log(error);
					}

				});
			}else{
				$('#resultSearch').hide();
			}
		});
	});
</script>