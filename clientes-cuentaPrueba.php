<?php 
require_once('header.php'); 
$idCliente = isset($_GET['id']) ? $_GET['id'] : "";

?>

<section class="content-header">
	<div class="content-header-left">
		<h1>Detalle de Cuenta</h1>
	</div>
	<div class="content-header-right">
		<a href="javascript:history.back()" class="btn btn-primary btn-sm"> Volver Atrás</a>
		<!-- <a href="clientes-add.php" class="btn btn-primary btn-sm"> <i class="fa fa-plus"></i> Nuevo Cliente</a> -->
		<!--<a href="libs/codebar/index.php" class="btn btn-warning btn-sm" target="_blank"> <i class="fa fa-barcode"></i> Imprimir codebars</a>-->
	</div>
</section>

<section class="content">
	<div class="row">
		<div class="col-md-12">
			<div class="box box-info">
				<div class="box-body table-responsive">
					<!-- code -->
					<table id="tablaClientesCuentas" class="table table-bordered table-striped">
						<thead>
							<tr>
								<th>N°</th>
								<th>ID</th>
								<th width="200">Cliente</th>
								<th>Documento</th>
								<th>Factura Nro</th>
								<th>Fecha</th>
								<th>Cta Creada Por</th>
								<th>Deuda Inicial</th>
								<th>Pagos</th>
								<th>A Cobrar</th>
								<th>Estado</th>
								<!--<th>Categoría</th>-->
								<!-- <th width="80">Acción</th> -->
							</tr>
						</thead>
						<tbody>
							<?php
							$i=0;

							$statement = $pdo->prepare("SELECT c.c_id, c.c_apellido, c.c_nombre, c.c_nro_doc, cc.id_factura, cc.tipo, f.usuario, DATE_FORMAT(CONVERT_TZ(FROM_UNIXTIME(cc.fecha),'+00:00','-03:00'), '%d/%m/%Y %H:%i') fecha_format , cc.deuda, SUM(cc.pago) as pagos FROM cuenta_corriente as cc INNER JOIN tbl_cliente as c ON cc.id_cliente = c.c_id INNER JOIN factura as f ON f.num_factura = cc.id_factura WHERE c.c_id= '$idCliente' GROUP BY cc.id_factura ;");
							$statement->execute();
							$result = $statement->fetchAll(PDO::FETCH_ASSOC);
							foreach ($result as $row) {
								$i++;
								?>
								<tr data-id="<?php echo $row['id_factura']; ?>">
									<td><?php echo $i; ?></td>
									<td><?php echo $row['c_id']; ?></td>
									<td><?php echo $row['c_apellido'].' '.$row['c_nombre']; ?></td>
									<!--<td><?php #echo $row['p_old_price']; ?></td>-->
									<td><?php echo $row['c_nro_doc']; ?></td>
									<td><?php echo $row['id_factura']; ?></td>
									<td><?php echo $row['fecha_format']; ?></td>
									<td><?php echo $row['usuario']; ?></td>
									<td><?php echo $row['deuda']; ?></td>
									<td><?php echo $row['pagos']; ?></td>
									<td><?php echo ($row['deuda'] - $row['pagos']); ?></td>
									<td>
										<?php if($row['tipo'] == 0) {echo "<img src='img/red.png'>";} else {echo "<img src='img/green.png'>";} ?>
									</td> 
									<!--<td><?php #echo $row['tcat_name']; ?><br><?php #echo $row['mcat_name']; ?><br><?php #echo $row['ecat_name']; ?></td>-->
									<!-- <td>										
										<a href="historial-cuenta.php?id=<?php //echo $row['c_id']; ?>" class="btn btn-primary btn-xs">Ver</a>
										
									</td> -->
								</tr>
								<?php
							}
							?>							
						</tbody>

					</table>
				</div>
			</div>
		</div>
	</div>
</section>

<div class="modal fade" id="pagosModal" tabindex="-1" role="dialog" aria-labelledby="myLabelModal" aria-hidden="true">
	<div class="modal-dialog">
		<div class="modal-content">
			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
				<h4 class="modal-title" id="myLabelModal" style="color: #000;"><i class="fa fa-number"></i> Pagos de la Factura #<i id="numFactura"></i></h4>
				<input type="hidden" name="idCliente" id="idCliente" value="<?php echo $idCliente; ?>">
				<input type="hidden" name="idFacturaImp" id="idFacturaImp">
			</div>
			<div class="modal-body">
				<h4>Deuda Inicial: $<i id="saldo"></i></h4>
				<h4 style="color:red;">Saldo Restante: $<i id="saldoRestante"></i></h4>
				<button id="btnFactura">Imprimir Factura Cancelada</button>
				<hr>
				<h3>Pagos Efectuados</h3>
				<div class="col-12 text-center">
					<table class="table table-striped" id="tablaPagos">
						<thead>
							<th>#</th>
							<th>Fecha</th>
							<th>Pago</th>
							<th>Cobrado Por</th>
							<th>Saldo</th>
						</thead>
						<tbody></tbody>

					</table>
				</div>

				<div class="col-12 text-center">
					<input type="hidden" name="usuario" id="idUsuarioCobrador" value="<?php echo $_SESSION['user']['full_name']; ?>">
					<strong>Ingresar un Pago:    $</strong><input type="number" name="pago" id="pago" min="0"  placeholder="0"  style="width: 6em;"> <br><br>
					<p style="color: red">Se utilizará el usuario <?php echo $_SESSION['user']['full_name']; ?> para registrar el cobro!</p>
				</div>
			</div>
			<div class="modal-footer">
				<button type="button" class="btn btn-default" data-dismiss="modal"> Cancelar</button>
				<a id="btnAgregarDetallePago" class="btn btn-warning"> Agregar</a>
			</div>
		</div>
	</div>
</div>




<?php require_once('footer.php'); ?>

<script type="text/javascript">
	$('#btnFactura').hide();
	$('#tablaClientesCuentas tbody').on('click', 'tr', function(){
		var id_factura = $(this).data('id');
		$('#tablaPagos tbody').empty();
		//$('#cover_top').fadeIn(500);
		$.ajax({
			url: 'apiGetPagos.php',
			method: 'POST',
			data: [{'name': 'id', 'value' : id_factura }],
			success: function(data){
				if (data.length>0) {
					console.log(data);
					$('#numFactura').text(id_factura);
					var linea="";
					var sumaPagos = 0;
					for(var i = 0; i < data.length; i++){
						var o = data[i];
						if (o.deuda > 0) {
							var deuda = o.deuda;
							var saldo = deuda;
							console.log(saldo);
							$('#saldo').text(deuda);

						}
						
						if (o.pago > 0) {
							//sumaPagos +=  o.pago;
							saldo -= o.pago;
							console.log('pagoo: '+o.pago);
							console.log('saldo'+saldo);
							linea += '<tr><td>'+i+'</td><td>'+o.fecha_format+'</td><td>'+o.pago+'</td><td>'+o.usuario+'</td><td>'+saldo+'</td></tr>';
						}
					}
					$('#saldoRestante').text(saldo);
					$('#pago').val(saldo);
					$('#pago').attr('max',saldo);

				}else{
						linea = '<tr><td>0</td><td>No se registran pagos a la fecha.</td></tr>';
					}
					if (saldo==0) {
						$('#btnFactura').show();
					}else{
						$('#btnFactura').hide();
					}
					$('#tablaPagos tbody').append(linea);
					$('#idFacturaImp').val(id_factura);
					$('#pagosModal').modal('show');
			},
			error: function(error){
				//$('#cover_top').fadeOut(500);
				console.log(error);
				alertify.error('Ops algo salió mal. comprueba la conexión!');
			},

		});
	});

	$('#btnFactura').on('click', function(){
		var idFactura = $('#idFacturaImp').val();
		window.open('imprimir_factura_test.php?id='+idFactura,'_blank');
	});

	$('#btnAgregarDetallePago').on('click', function(){
		var idFactura = $('#numFactura').text();
		var pago = parseFloat($('#pago').val());
		var idCliente = $('#idCliente').val();
		var idUsuarioCobrador = $('#idUsuarioCobrador').val();
		//console.log(idFactura+'  '+pago);   parseFloat($('#saldoRestante').text())
		
		if (parseFloat($('#saldoRestante').text()) != 0) {
			if (pago <= parseFloat($('#saldoRestante').text()) && pago >0) {
			alertify.confirm('Confirmar pago', 'Va a registrar un pago de $'+pago+' para Factura Nro: '+idFactura+'. ¿Está seguro?', function(){ 
						alertify.warning('Registrando pago...');
						$.ajax({
							url: 'apiRegistrarPagos.php',
							method: 'POST',
							data: [{'name': 'id', 'value' : idFactura }, {'name': 'pago', 'value' : pago }, {'name': 'idCliente', 'value' : idCliente }, {'name': 'usuario', 'value' : idUsuarioCobrador }],
							success: function(data){
								if (data.success=true) {
									alertify.success('Pago realizado con éxito...', 2);
									window.location.reload();
								}
							},
							error: function(error){
								//$('#cover_top').fadeOut(500);
								console.log(error);
								alertify.error('Ops algo salió mal. comprueba la conexión!');
							},

						}); 

				}
		, function(){ alertify.error('Cancelado')});
		}else{
			alertify.warning('El pago no puede ser superior a la deuda');
		}
		}else{
			alertify.warning('La Factura está cancelada!!');
		}
		


	});

	
</script>