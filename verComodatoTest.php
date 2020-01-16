<?php 
	//verComodato.php
	require_once('header.php'); 
	$id = isset($_GET['id']) ? $_GET['id'] : 0;

	$folder_images = 'img/200/';

if($id!=0){

		//obtenemos orden:
	$statement = $pdo->prepare("SELECT 
								tcli.c_nombre nombre, 
								tcli.c_apellido apellido, 

								tprod.p_name nombre_producto,
								
								tcom.id id,
								tcom.id_cliente id_cliente,
								tcom.id_sucursal id_sucursal,
								tcom.cantidad cantidad,
								tcom.fecha_emision fecha_desde_unix,
								tcom.fecha_devolucion fecha_hasta_unix,
								DATE_FORMAT(CONVERT_TZ(FROM_UNIXTIME(tcom.fecha_emision),'+00:00','-03:00'), '%d/%m/%Y') fecha_emision_format,
								DATE_FORMAT(CONVERT_TZ(FROM_UNIXTIME(tcom.fecha_devolucion),'+00:00','-03:00'), '%d/%m/%Y') fecha_devolucion_format,
								tcom.estado estado,
								tcom.orden orden,
								tcom.observaciones,
								tcom.sucursal_devuelto,
								tcom.devuelto_por,

								tusr.full_name user_fullname

								FROM tbl_comodato tcom 
								INNER JOIN tbl_cliente tcli ON tcli.c_id = tcom.id_cliente
								INNER JOIN tbl_product tprod ON tprod.p_id = tcom.id_producto
								INNER JOIN tbl_user tusr ON tusr.id = tcom.id_user WHERE tcom.orden = ?;");
	$statement->execute(array($id));
	$resultado = $statement->fetchAll(PDO::FETCH_ASSOC);

	foreach($resultado as $row){
		$id = $row['id'];
		$id_cliente = $row['id_cliente'];
		$fecha_emision = $row['fecha_emision'];
		$fecha_devolucion = $row['fecha_devolucion'];
		$fecha_emision_format = $row['fecha_emision_format'];
		$fecha_devolucion_format = $row['fecha_devolucion_format'];
		$estado = $row['estado'];
		$orden = $row['orden'];
		$user_fullname = $row['user_fullname'];
		$id_sucursal = $row['id_sucursal'];
		$sucursal_devuelto = $row['sucursal_devuelto'];
		$devuelto_por_id = $row['devuelto_por'];

		$obs = $row['observaciones'];

		$icon_estado = 'green.png';
		switch ($estado) {
			case 'Devuelto':
				$icon_estado = 'green.png';
				break;
			case 'Prestado':
				$icon_estado = 'orange.png';
				break;
			case 'Demorado':
				$icon_estado = 'red.png';
			default:
				# code...
				$icon_estado = 'green.png';
				break;
		}
	}
	

	

}
?>

<section class="content-header">
	<div class="content-header-left">
		<h1>Órden de Trabajo</h1>
		<!--<input type="hidden" name="idPedido" id="idPedido" value="<?php echo $id; ?>">-->
		<input type="hidden" name="nro_orden_comodato" id="nro_orden_comodato" value="<?php echo $orden; ?>">
		<input type="hidden" name="user" id="user" value="<?php echo $_SESSION['user']['full_name']; ?>">
	</div>
	<div class="content-header-right">
		<a href="comodato.php" class="btn btn-primary btn-sm" > <i class="fa fa-list-ul"></i> Ver Todos </a>
		<a class="btn btn-primary btn-sm" id="btnVerOrden"> <i class="fa fa-print"></i> Imprimir Orden </a>
		
	</div>
</section>
<section class="content">
	<div class="row">
		
		

		<div class="col-md-4 col-sm-6 col-xs-12">
			<div class="info-box">
				<span class="info-box-icon bg-grey"><i><img src="<?php echo $folder_images.$icon_estado; ?>"></i></span>
				<div class="info-box-content">
					<span class="info-box-text">Estado del préstamo</span>
					<span class="info-box-number"><?php echo $estado; ?></span>
				</div>
			</div>
		</div>
		

		<div class="col-md-4 col-sm-6 col-xs-12">
			<div class="info-box">
				<span class="info-box-icon bg-green"><i class="fa fa-arrow-right"></i></span>
				<div class="info-box-content">
					<span class="info-box-text">Cliente Nº</span>
					<span class="info-box-number"><?php echo $id_cliente; ?></span>
				</div>
			</div>
		</div>

		<div class="col-md-4 col-sm-6 col-xs-12">
			<div class="info-box">
				<span class="info-box-icon bg-green"><i class="fa fa-sticky-note"></i></span>
				<div class="info-box-content">
					<span class="info-box-text">Orden de comodato Nº</span>
					<span class="info-box-number"><?php echo $orden; ?></span>
				</div>
			</div>
		</div>
		

	</div>
	<div class="box box-info">
		<div class="box-body table-responsive">
			<table id="tablaOrdenes" class="table table-bordered table-striped">
				<thead>
					<tr>

						<th>Fecha Emisión</th>
						<th >Pedido por</th>
						<!--<th width="60">Precio Anterior</th>-->
						<th >Sucursal</th>

					</tr>
				</thead>
				<tbody>
					<?php 
						$stat = $pdo->prepare("SELECT s_name FROM tbl_sucursales WHERE s_id = ?;");
						$stat->execute(array($id_sucursal));
						$res = $stat->fetchAll(PDO::FETCH_ASSOC);
						foreach($res as $rw){
							$sucursal_name = $rw['s_name'];
						}
						echo "<tr><td>".$fecha_emision_format."</td><td>".$user_fullname."</td><td>".$sucursal_name."</td></tr>"; 

					?>

				</tbody>
				
			</table>
		</div>
	</div>

	<!-- abro if -->
	<?php 

		if($sucursal_devuelto>0){


	 ?>
		<div class="box box-info">
			<div class="box-body table-responsive">
				<table id="tablaOrdenes" class="table table-bordered table-striped">
					<thead>
						<tr>

							<th>Fecha Devolución</th>
							<th >Devuelto por</th>
							<!--<th width="60">Precio Anterior</th>-->
							<th >Devuelto en Sucursal</th>

						</tr>
					</thead>
					<tbody>
						<?php 
							//get the sucursal
							$stat = $pdo->prepare("SELECT s_name FROM tbl_sucursales WHERE s_id = ?;");
							$stat->execute(array($sucursal_devuelto));
							$res = $stat->fetchAll(PDO::FETCH_ASSOC);
							foreach($res as $rw){
								$sucursal_name = $rw['s_name'];
							}

							//get the de user:
							$stat = $pdo->prepare("SELECT full_name FROM tbl_user WHERE id = ?;");
							$stat->execute(array($devuelto_por_id));
							$res = $stat->fetchAll(PDO::FETCH_ASSOC);
							foreach($res as $rw){
								$full_name_devolucion = $rw['full_name'];
							}

							echo "<tr><td>".$fecha_devolucion_format."</td><td>".$full_name_devolucion."</td><td>".$sucursal_name."</td></tr>"; 

						?>

					</tbody>
					
				</table>
			</div>
		</div>
	<?php } ?> <!-- cierro if -->
</section>

<section class="content">
	<div class="row">
		<div class="col-md-12">
			<div class="box box-info">
				<div class="box-body table-responsive">
					<h3>Productos Prestados</h3>
					<table id="tablaOrdenes" class="table table-bordered table-striped">
						<thead>
							<tr>
								<th>#</th>
								<th>Descripción Item</th>
								<th>Cantidad</th>

							</tr>
						</thead>
						<tbody>
							<?php 
							
								$cont = 0;
								foreach($resultado as $row) {
									$cont++;
									echo "<tr><td>".$cont."</td><td>".$row['nombre_producto']."</td><td>".$row['cantidad']."</td></tr>"; 
								}
							?>

						</tbody>
						<tfoot>
							<th colspan="3">
								
								<br>
								
							</th>
						</tfoot>
						<!--<tfoot>
							<th ><td style="background-color: orange; font-style: bold;">Total Unidades</td><td style="background-color: orange; font-style: bold;"><?php #echo $cantidad; ?></td></th>
						</tfoot>-->
					</table>
				</div>
			</div>

			<?php 
				if($estado != 'Devuelto'){

			?>

			<div class="box box-info">
				<div class="box-body">
					<form action="" class="form-horizontal">
						<div class="form-group">
							
							<label for="idSucursal" class="col-sm-3 control-label"><i class="fa fa-building"></i> Seleccione Sucursal a devolver</label>
							
							<div class="col-sm-6">
								<select id="idSucursal" name="idSucursal" class="form-control" required>
									<?php if (($_SESSION['user']['role']== "Admin") || ($_SESSION['user']['role']== "Super Admin")) { 

										$statement = $pdo->prepare("SELECT * FROM tbl_sucursales WHERE s_active = 1");
										$statement->execute();
										$result = $statement->fetchAll(PDO::FETCH_ASSOC);
										echo "<option value=''>Seleccione</option>";
										foreach($result as $row){

											?>

											<option value="<?php echo $row['s_id']; ?>"><?php echo $row['s_name']; ?></option>
											<?php
										}

									}else{
										$ide= $_SESSION['user']['sucursal'];
										$statement = $pdo->prepare("SELECT * FROM tbl_sucursales WHERE s_id = '$ide'");
										$statement->execute();
										$result = $statement->fetchAll(PDO::FETCH_ASSOC);
										foreach($result as $row){

											?>
											<option value="<?php echo $row['s_id']; ?>"><?php echo $row['s_name']; ?></option>
										<?php }	 
									} ?>
								</select>
							</div>

							<button class="btn btn-success" id="btnDevolver"> <i id="icon_devolver" class="fa fa-refresh"></i> Devolver</button> 
						</div>
					</form>
				</div>
			</div>
			<?php
				}
			?>

			<div class="box box-info">
				<div class="box-body table-responsive">
					<h3>OBSERVACIONES</h3>
					<div id="cuadro" style="border: solid 2px #000000; border-radius: 3px; padding: 2em; background-color: #f5e507; font-size: 15px;">
						<?php echo $obs; ?>
					</div>
				</div>
			</div>

			
		</div>
	</div>
</section>



<div class="modal fade" id="agregarlModal" tabindex="-1" role="dialog" aria-labelledby="myLabelModal" aria-hidden="true">
	<div class="modal-dialog">
		<div class="modal-content">
			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
				<h4 class="modal-title" id="myLabelModal" style="color: #000;"><i class="fa fa-number"></i>Modificar Estado<i id="numFactura"></i></h4>
				
			</div>
			<div class="modal-body">
				
				<div class="row">
					<div class="col-md-12">
						<h3>Nuevo Proceso o Comentario</h3>

						<form class="form-group">
							<select id="fEstado" name="fEstado" class="form-control">
								<option value="">-Seleccione Estado-</option>
								<option value="6">Observación / Comentario</option>
								<option value="2">En Producción</option>
								<option value="3">Terminado</option>
								<option value="4">Enviado</option>
								<option value="5">Pausado / Cancelado</option>

							</select>
							<br>
							<label>Comentario</label>
							<textarea name="observ" id="observ" class="form-control"></textarea>
						</form>
					</div>
				</div>
				

				
			</div>
			<div class="modal-footer">
				<button type="button" class="btn btn-default" data-dismiss="modal"> Cerrar</button>
				<button type="button" class="btn btn-primary" id="btnGuardarEstado"> Guardar</button>
				
			</div>
		</div>
	</div>
</div>


<?php require_once('footer.php'); ?>
<script src="lightb/js/lightbox.js"></script>

<script type="text/javascript">
	
	
	$('#btnVerOrden').on('click', function(){
		var orden = $('#nro_orden_comodato').val();
		var url = "imprimirComodato.php?orden="+orden;
		window.open(url, '_blank');
	});

	lightbox.option({
		'resizeDuration': 200,
		'wrapAround': true
	});
	
	
	/*$('#btnHistorial').on('click', function(){
		$('#historialModal').modal('show');
	});*/
	

	$('#btnDevolver').click(function(e){
		e.preventDefault();

		if($('#idSucursal').val()!=''){
			$('#idSucursal').attr('disabled','disabled');
			$('#icon_devolver').addClass('fa-spin');
			$(this).addClass('disabled');
			
			//aquí ajax:

			$.ajax({
				url: 'apiComodato.php',
				data: [
						{name: 'acc', value: 'devolver'}, 
						{name: 'id_sucursal', value: $('#idSucursal').val()},
						{name: 'nro_orden_comodato', value: $('#nro_orden_comodato').val()}
					],
				method: 'POST',
				success: function(data){
					if(data.success==true){
						alertify.success("¡Productos devueltos correctamente!",2, function(){
							document.location = '';
						});
					}else{
						alertify.error("Error: "+data.msg);
					}

					$('#idSucursal').removeAttr('disabled');
					$('#icon_devolver').removeClass('fa-spin');
					$(this).removeClass('disabled');
				},
				error: function(err){
					alertify.error("Ocurrió un error desconocido.");
					console.log(err);
					$('#idSucursal').removeAttr('disabled');
					$('#icon_devolver').removeClass('fa-spin');
					$(this).removeClass('disabled');
				}
			});

		}else{
			alertify.error("Seleccione una sucursal");
		}

	});
	
	
	
</script>