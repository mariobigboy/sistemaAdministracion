<?php require_once('header.php'); 
$id = isset($_GET['id']) ? $_GET['id'] : 0;

if($id!=0){

		//obtenemos orden:
	$statement = $pdo->prepare("SELECT p.*, REPLACE(REPLACE(p.obs,CHAR(10),' '),CHAR(13),' - ') as reemplazo, c.c_apellido, c.c_nombre, FROM_UNIXTIME(p.fecha, '%d/%m/%Y %H:%i') fecha_format FROM `pedido` AS p INNER JOIN factura AS f ON f.num_factura = p.idFactura INNER JOIN tbl_cliente AS c ON c.c_id = f.id_cliente WHERE p.id = ? ;");
	$statement->execute(array($id));
	$resultado = $statement->fetchAll(PDO::FETCH_ASSOC);

	foreach($resultado as $row){
		$id_orden = $row['id'];
		$nombre = $row['c_nombre'];
		$apellido = $row['c_apellido'];
		$fecha = $row['fecha_format'];
		$usuario = $row['usuarioSucursal'];
		$obs = str_replace('-', '\n', $row['reemplazo']);
		$detalles = $row['detalles'];
		$suc = $row['sucursal'];
		$factura = $row['idFactura'];
		$estado = $row['estado'];

	}
	

	switch ($suc) {
		case '1':
		$sucursal= "Home Design Salta";
		break;
		case '2':
		$sucursal="Muebles & Deco";
		break;
		case '3':
		$sucursal = "Home de la Av";
		break;
		case '4':
		$sucursal = "Infantil Salta";
		break;
		case '5':
		$sucursal= "Home Online";
		break;

		default:
											# code...
		break;
	}


}
?>

<section class="content-header">
	<div class="content-header-left">
		<h1>Órden de Trabajo</h1>
		<input type="hidden" name="idPedido" id="idPedido" value="<?php echo $id; ?>">
		<input type="hidden" name="user" id="user" value="<?php echo $_SESSION['user']['full_name']; ?>">
	</div>
	<div class="content-header-right">
		<a  class="btn btn-primary btn-sm" id="btnVerOrden">  Imprimir Orden </a>
		<?php if ($estado == 0) { ?>
			<a  class="btn btn-success " id="btnAceptarPedido"> <i class="fa fa-plus"></i> Comenzar Pedido </a>
		<?php } else{?>
			<a  class="btn btn-success " id="btnHistorial"> <i class="fa fa-chart"></i> Historial </a>
			<a  class="btn btn-success " id="btnModificarEstado"> <i class="fa fa-plus"></i> Agregar </a>
		<?php } ?>
	</div>
</section>
<section class="content">
	<div class="row">
		<?php 
		switch ($estado) {
			case '0':
			$est = "Encargado";
			break;
			case '1':
			$est = "Recibido";
			break;
			case '2':
			$est = "Producción";
			break;
			case '3':
			$est = "Terminado";
			break;
			case '4':
			$est = "Enviado";
			break;
			case '5':
			$est = "Pausado";
			break;
			default:
										# code...
			break;
		}
		if ($estado==0 || $estado ==5) {
			$imag= "img/200/red.png";
		}elseif($estado == 1 || $esatdo == 2){
			$imag= "img/200/orange.png";
		}else{
			$imag= "img/200/green.png";
		}

		?>

		<div class="col-md-4 col-sm-6 col-xs-12">
			<div class="info-box">
				<span class="info-box-icon bg-grey"><i><img src="<?php echo $imag; ?>"></i></span>
				<div class="info-box-content">
					<span class="info-box-text">Estado de la Órden</span>
					<span class="info-box-number"><?php echo $est; ?></span>
				</div>
			</div>
		</div>
		

		<div class="col-md-4 col-sm-6 col-xs-12">
			<div class="info-box">
				<span class="info-box-icon bg-green"><i class="fa fa-arrow-right"></i></span>
				<div class="info-box-content">
					<span class="info-box-text">Orden de Trabajo Nº</span>
					<span class="info-box-number"><?php echo "00".$id; ?></span>
				</div>
			</div>
		</div>

		<div class="col-md-4 col-sm-6 col-xs-12">
			<div class="info-box">
				<span class="info-box-icon bg-green"><i class="fa fa-sticky-note"></i></span>
				<div class="info-box-content">
					<span class="info-box-text">Factura Nº</span>
					<span class="info-box-number"><?php echo $factura; ?></span>
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
					<?php echo "<tr><td>".$fecha."</td><td>".$usuario."</td><td>".$sucursal."</td></tr>"; ?>

				</tbody>
			</table>
		</div>
	</div>
</section>

<section class="content">
	<div class="row">
		<div class="col-md-12">
			<div class="box box-info">
				<div class="box-body table-responsive">
					<h3>Trabajos a Realizar</h3>
					<table id="tablaOrdenes" class="table table-bordered table-striped">
						<thead>
							<tr>
								<th >#</th>
								<th>Descripción Item</th>
								<th >Cantidad</th>

							</tr>
						</thead>
						<tbody>
							<?php 
							$vec = explode(",", $detalles);
							$cont=1;
							$cantidad = 0;
							for ( $i = 0; $i < sizeof($vec); $i++) {
								$ide=$vec[$i];
								$statement = $pdo->prepare("SELECT nombre, cantidad FROM `detalle` WHERE id_detalle = '$ide' ;");
								$statement->execute();
								$resultado = $statement->fetchAll(PDO::FETCH_ASSOC);
								$cantidad += $resultado[0]['cantidad'];
								
								echo "<tr><td>".$cont."</td><td>".$resultado[0]['nombre']."</td><td>".$resultado[0]['cantidad']."</td></tr>"; 
								$cont++;
							}
							?>

						</tbody>
						<tfoot>
							<th ><td style="background-color: orange; font-style: bold;">Total Unidades</td><td style="background-color: orange; font-style: bold;"><?php echo $cantidad; ?></td></th>
						</tfoot>
					</table>
				</div>
			</div>

			<div class="box box-info">
				<div class="box-body table-responsive">
					<h3>INSTRUCCIONES / OBSERVACIONES</h3>
					<div id="cuadro" style="border: solid 2px #000000; border-radius: 3px; padding: 2em; background-color: #f5e507; font-size: 15px;">
						<?php echo $obs; ?>
						
					</div>
				</div>
			</div>

			<div class="box box-info">
				<div class="box-body table-responsive">
					<h3>Imágenes</h3>
					<div class="row">
						<div class="col-md-12">
							<?php 


							$statement = $pdo->prepare("SELECT * FROM `imgPedidos` WHERE idPedido = '$id';");
							$statement->execute();
							$resultado = $statement->fetchAll(PDO::FETCH_ASSOC);
							$filas = $statement->rowCount();
							if ($filas > 0) {
								$cont=1;

								foreach ($resultado as $row) {

									echo "<a href='".$row['ruta']."' data-lightbox='roadtrip'><img src='".$row['ruta']."' style='width: 200px'></a>";
								}
								$cont++;
							}
							?>
						</div>
					</div>

					
					
				</div>
			</div>
		</div>
	</div>
</section>

<div class="modal fade" id="historialModal" tabindex="-1" role="dialog" aria-labelledby="myLabelModal" aria-hidden="true">
	<div class="modal-dialog">
		<div class="modal-content">
			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
				<h4 class="modal-title" id="myLabelModal" style="color: #000;"><i class="fa fa-number"></i> Historial OT # <?php echo "$id"; ?><i id="numFactura"></i></h4>
				
			</div>
			<div class="modal-body">
				
				<h3>Procesos</h3>
				<div class="col-12 text-center">
					<table class="table table-striped" id="tablaPagos">
						<thead>
							<th>#</th>
							<th>Fecha</th>
							<th>Estado</th>
							<th>Usuario</th>
							<th>Observaciones</th>
						</thead>
						<tbody>
							<?php 


							$statement = $pdo->prepare("SELECT estado,FROM_UNIXTIME(fecha, '%d/%m/%Y %H:%i') as fecha_format, usuario, REPLACE(REPLACE(obs,CHAR(10),' '),CHAR(13),' - ') as reemplazo FROM `estadoPedido` WHERE idPedido = '$id' ;");
							$statement->execute();
							$resultado = $statement->fetchAll(PDO::FETCH_ASSOC);
							$filas = $statement->rowCount();
							if ($filas > 0) {
								$cont=1;

								foreach ($resultado as $row) {
									switch ($row['estado']) {
										case '0':
										$es = "Encargado";
										break;
										case '1':
										$es = "Recibido";
										break;
										case '2':
										$es = "Producción";
										break;
										case '3':
										$es = "Terminado";
										break;
										case '4':
										$es = "Enviado";
										break;
										case '5':
										$es = "Pausado";
										break;
										case '6':
										$es = "Observación";
										break;
										default:
												# code...
										break;
									}
									echo "<tr><td>".$cont."</td><td>".$row['fecha_format']."</td><td>".$es."</td><td>".$row['usuario']."</td><td>".$row['reemplazo']."</td></tr>";
								}
								$cont++;
							}
							?>
							
						</tbody>

					</table>
				</div>

				
			</div>
			<div class="modal-footer">
				<button type="button" class="btn btn-default" data-dismiss="modal"> Cerrar</button>
				
			</div>
		</div>
	</div>
</div>

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
	$('#btnModificarEstado').on('click', function(){
		$('#agregarlModal').modal('show');
	});
	$('#btnGuardarEstado').on('click', function(){
		var idOt = $('#idPedido').val();
		var estado = $('#fEstado').val();
		var obs = $('#observ').val();
		var user = $('#user').val();
		if (estado !="") {
			if(estado==6 && obs==""){
				alertify.warning('Debe completar el campo Comentario');

			}else{

				alertify.warning('Un momento por favor...');
				var datos = [{'name': 'acc' , 'value': 'modificarEstado'}, {'name': 'idOrden', 'value':  idOt}, {'name' : 'user', 'value' : user}, {'name' : 'estado', 'value' : estado}, {'name' : 'obs', 'value' : obs}];
				$.ajax({
					url: 'apiOrdenes.php',
					method: 'POST',
					data: datos,
					success: function(data){
						if (data.success==true) {
							alertify.success('OT modificada correctamente!', 2);
							setTimeout(function(){
								window.location.href="";
							},2000);
						}
					},
					error: function(error){
								//$('#cover_top').fadeOut(500);
								console.log(error);
								alertify.error('Ops algo salió mal. comprueba la conexión!');
							},

						}); 

			}
			
		}else{

			alertify.warning('Complete por lo menos el estado');
		}
	});
	$('#btnVerOrden').on('click', function(){
		var id = $('#idPedido').val();
		var url = "imprimirOrden.php?id="+id;
		window.open(url, '_blank');
	});

	lightbox.option({
		'resizeDuration': 200,
		'wrapAround': true
	});
	
	$('#btnAceptarPedido').on('click', function(){
		alertify.confirm('ATENCION!', 'Al confirmar, ésta OT queda abierta y se marca como "EN PRODUCCIÓN". ¿Desea Continuar?', function(){ alertify.warning('Un momento por favor...');
			var idOt = $('#idPedido').val();
			var user = $('#user').val();
			var datos = [{'name': 'acc' , 'value': 'abrirOt'}, {'name': 'idOrden', 'value':  idOt}, {'name' : 'user', 'value' : user}];
			$.ajax({
				url: 'apiOrdenes.php',
				method: 'POST',
				data: datos,
				success: function(data){
					if (data.success==true) {
						alertify.success('OT abierta correctamente!', 2);
						setTimeout(function(){
							window.location.href="";
						},2000);
					}
				},
				error: function(error){
								//$('#cover_top').fadeOut(500);
								console.log(error);
								alertify.error('Ops algo salió mal. comprueba la conexión!');
							},

						}); 
		}
		, function(){ alertify.error('Acción Cancelada')});
	});
	$('#btnHistorial').on('click', function(){
		$('#historialModal').modal('show');
	});
	


	
	
	
</script>