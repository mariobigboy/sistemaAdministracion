<?php 
require_once('header.php'); 
$idProveedor = isset($_GET['id']) ? $_GET['id'] : "";
$statement = $pdo->prepare("SELECT nombre FROM proveedores WHERE id = ?");
							$statement->execute(array(
								$idProveedor
							));
							$result = $statement->fetchAll(PDO::FETCH_ASSOC);

?>

<section class="content-header">
	<div class="content-header-left">
		<h1>Detalle de Cuenta: <?php echo $result[0]['nombre']; ?></h1>
	</div>
	<div class="content-header-right">
		<a href="javascript:history.back();" class="btn btn-primary btn-sm"> Volver Atrás</a>
		<a href="cuentaProveedor-add.php?id=<?php echo $idProveedor; ?>" class="btn btn-primary btn-sm"> Nueva Cuenta</a>
		
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
								
								<th>Factura / Remito Nro</th>
								<th>Fecha</th>
								<th>Deuda Inicial</th>
								<th>Pagos</th>
								<th>Saldo</th>
								<th>Estado</th>
								
							</tr>
						</thead>
						<tbody>
							<?php
							$i=0;

							$statement = $pdo->prepare("SELECT 
								cp.id as idCuenta, 
								cp.factura, 
								cp.monto, 
								cp.estado as estadoCuenta, 
								DATE_FORMAT(CONVERT_TZ(FROM_UNIXTIME(cp.fecha),'+00:00','-03:00'), '%d/%m/%Y %H:%i') fecha_format,
								SUM(dp.pago) as pagos
								FROM cuentasProveedores as cp 
								INNER JOIN detalleProveedores as dp ON cp.id = dp.idCuenta
								WHERE cp.idProveedor= ? 
								GROUP BY cp.id;");
							$statement->execute(array($idProveedor));
							$result = $statement->fetchAll(PDO::FETCH_ASSOC);
							foreach ($result as $row) {
								$i++;
								?>
								<tr data-id="<?php echo $row['idCuenta']; ?>" data-factura="<?php echo $row['factura']; ?>">
									<td><?php echo $i; ?></td>
									<td><?php echo $row['idCuenta']; ?></td>
									<td><?php echo $row['factura']; ?></td>
									<td><?php echo $row['fecha_format']; ?></td>
									<td><?php echo $row['monto']; ?></td>
									<td><?php echo $row['pagos']; ?></td>
									
									<td><?php echo $row['monto'] - $row['pagos']; ?></td>
									
								
									<td>
										<?php if($row['estadoCuenta'] == 0) {echo "<img src='img/red.png'>";} else {echo "<img src='img/green.png'>";} ?>
									</td> 
									
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
				<h4 class="modal-title" id="myLabelModal" style="color: #000;"><i class="fa fa-number"></i> Pagos Cuenta #<i id="numFactura"></i></h4>
				<input type="hidden" name="idCliente" id="idCliente" value="<?php echo $idProveedor; ?>">
				<input type="hidden" name="idFacturaImp" id="idFacturaImp">
				<input type="hidden" name="estado_cuenta" id="estado_cuenta" value="<?php echo $row['estadoCuenta']; ?>">
			</div>
			<div class="modal-body">
				<h4>Deuda Inicial: $<i id="deuda_inicial"></i></h4>
				<h4 style="color:red;">Saldo Restante: $<i id="saldoRestante"></i></h4>
				<!--<button id="btnFactura">Imprimir Factura Cancelada</button>-->
				<hr>
				<h3>Pagos Efectuados</h3>
				<div class="col-12 text-center">
					<table class="table table-striped" id="tablaPagos">
						<thead>
							<th>#</th>
							<th>Fecha</th>
							<th>Pago</th>
							<th>Pagado Por</th>
							<th>Medio de Pago</th>
							<!--<th>Saldo</th>-->
						</thead>
						<tbody></tbody>

					</table>
				</div>

				<div class="col-12 text-center" id="panel_add_pago">
					<form action="#" class="form-horizontal">
						<div class="form-group">
							<label for="pago" class="label-control col-md-4">Ingresar un pago $</label>
							<div class="col-md-4">
								<input type="hidden" name="usuario" id="idUsuarioCobrador" value="<?php echo $_SESSION['user']['full_name']; ?>">
								<input type="hidden" name="idCuenta" id="idCuenta" value="">
								<input class="form-control" type="number" name="pago" id="pago" min="0"  placeholder="0">
							</div>

						</div>
						<div class="form-group">
								<label for="selMetodoPago" class="label-control col-md-4">Método de Pago:</label>
								<div class="col-md-4">
									<select class="form-control" name="" id="selMetodoPago">
										<option value="-1">Seleccione Método de Pago</option>
										<option value="Efectivo">Efectivo</option>
										<option value="Tarjeta">Tarjeta</option>
										<option value="Cheque">Cheque</option>
									</select>
								</div>
							</div>
					</form>
					
					<!--<strong>Ingresar un Pago:    $</strong>--> <br><br>
					<p style="color: red;">Se utilizará el usuario <?php echo $_SESSION['user']['full_name']; ?> para registrar el pago!</p>
				</div>
			</div>
			<div class="modal-footer">
				<button id="btnCerrar" type="button" class="btn btn-default" data-dismiss="modal" style="display:none;">Cerrar</button>
				<button id="btnCancelar" type="button" class="btn btn-default" data-dismiss="modal"> Cancelar</button>
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
		var factura = $(this).data('factura');
		var idCuenta = id_factura;
		
		$('#idCuenta').val(idCuenta);
		$('#numFactura').text(factura);


		//visualmente mostramos:
		$('#panel_add_pago').show();
		$('#btnAgregarDetallePago').show();
		$('#btnCancelar').show();
		$('#btnCerrar').hide();

		$('#tablaPagos tbody').empty();
		//$('#cover_top').fadeIn(500);
		$.ajax({
			url: 'apiGetPagosProveedor.php',
			method: 'POST',
			data: [{'name': 'id', 'value' : id_factura }],
			success: function(data){
				if (data.length>0) {
					var cuentaProveedores = data[0];  //objeto tipo json
					var detalleProveedores = data[1]; //array de objetos tipo json

					var deuda_inicial = parseFloat(cuentaProveedores.monto); //monto inicial
					var total_pagos = 0; //inicializamos los pagos en 0 (cero)

					var linea = '';

					//sumamos pagos ya realizados y almacenamos el resultado en total_pagos:

					detalleProveedores.forEach(function(o,i){
						/*
							o = {
						      "id": "3",
						      "idCuenta": "3",
						      "pago": "1",
						      "formaPago": "2",
						      "fecha": "1573077496",
						      "fecha_format": "06/11/2019 18:58"
						    }
						*/
						total_pagos+=parseFloat(o.pago);
						if(o.pago>0){
							linea = '<tr><td>'+(i)+'</td><td>'+o.fecha_format+'</td><td>'+o.pago+'</td><td>'+cuentaProveedores.usuario+'</td><td>'+o.formaPago+'</td></tr>';
							$('#tablaPagos tbody').append(linea);
						}
					});
						
					//saldo a pagar:
					var saldo = deuda_inicial - total_pagos;
					//console.log(saldo, deuda_inicial, total_pagos);
					if(saldo == 0){
						$('#panel_add_pago').hide();
						$('#btnAgregarDetallePago').hide();
						$('#btnCancelar').hide();
						$('#btnCerrar').show();
						$('#estado_cuenta').val("1");
					}

					if(detalleProveedores.length == 1){
						linea = '<tr><td colspan="5">No se registran pagos a la fecha.</td></tr>';
						$('#tablaPagos tbody').append(linea);
					}

					$('#deuda_inicial').text(deuda_inicial);
					$('#saldoRestante').text(saldo);
					$('#pago').val(saldo);
					$('#pago').attr('max',saldo);

				}else{
					linea = '<tr><td>0</td><td>No se registran pagos a la fecha.</td></tr>';
					$('#tablaPagos tbody').append(linea);
				}

				if (saldo==0) {
					$('#btnFactura').show();
				}else{
					$('#btnFactura').hide();
				}

				
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

	/*$('#btnFactura').on('click', function(){
		var idFactura = $('#idFacturaImp').val();
		window.open('imprimirFacturaPagada.php?id='+idFactura,'_blank');
	});*/

	$('#btnAgregarDetallePago').on('click', function(e){
		e.preventDefault();
		var datos = [
				{name: 'idCuenta', value: $('#idCuenta').val()},
				{name: 'pago', value: $('#pago').val()},
				{name: 'usuario', value: $('#idUsuarioCobrador').val()},
				{name: 'metodoPago', value: $('#selMetodoPago').val()},
				{name: 'estado_cuenta', value: $('#estado_cuenta').val()},
				{name: 'id_proveedor', value: $('#idCliente').val()}
			];
		if($('#selMetodoPago').val()=="-1"){
			alertify.error("seleccione un método de pago", 3);
		}else{
			//llamamos a api:
			$.ajax({
				url: 'apiRegistrarPagoProveedor.php',
				data: datos,
				method: 'POST',
				success: function(data){
					if(data.success){
						alertify.success("Pago registrado correctamente!");
						document.location = ''
					}else{
						alertify.error("Error al procesar pago.");
					}
				},
				error: function(err){
					console.log(err);
					alertify.error("Ocurrió un error. Comuníquese con el administrador.");
				}

			});
		}
		/*var idFactura = $('#numFactura').text();
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
		}*/
		


	});

	
</script>