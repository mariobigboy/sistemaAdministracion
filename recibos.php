<?php 
require_once('header.php'); 
$usuario = $_SESSION['user']['full_name'];
$sucursal = $_SESSION['user']['sucursal'];
?>

<section class="content-header">
	<div class="content-header-left">
		<h1>Recibos</h1>
	</div>
	<div class="content-header-right">
		<a  class="btn btn-primary btn-sm" id="btnNuevoRecibo"> Nuevo Recibo</a>
		<!-- <a href="clientes-add.php" class="btn btn-primary btn-sm"> <i class="fa fa-plus"></i> Nuevo Cliente</a> -->
		<!--<a href="libs/codebar/index.php" class="btn btn-warning btn-sm" target="_blank"> <i class="fa fa-barcode"></i> Imprimir codebars</a>-->

	</div>
</section>

<section class="content">
	<div class="row">
		<?php //echo $usuario; ?>
		<div class="col-md-12">
			<div class="box box-info">
				<div class="box-body table-responsive">
					<!-- code -->
					<table id="tablaClientesCuentas" class="table table-bordered table-striped">
						<thead>
							<tr>
								<th>#</th>
								<th>Recibo N°</th>
								<th>Cliente</th>
								<th>Usuario</th>
								<th>Monto</th>
								
								<th>Fecha</th>
								<th></th>
								
								<!--<th>Categoría</th>-->
								<!-- <th width="80">Acción</th> -->
							</tr>
						</thead>
						<tbody>
							<?php
							$i=0;

							if ($usuario == 'SuperAdmin' || $usuario == "Admin") {
								$statement = $pdo->prepare("SELECT *, DATE_FORMAT(CONVERT_TZ(FROM_UNIXTIME(fecha),'+00:00','-03:00'), '%d/%m/%Y %H:%i') fecha_format FROM recibos ORDER by id DESC;");
							$statement->execute();
							$result = $statement->fetchAll(PDO::FETCH_ASSOC);
							}else{
								$statement = $pdo->prepare("SELECT *, DATE_FORMAT(CONVERT_TZ(FROM_UNIXTIME(fecha),'+00:00','-03:00'), '%d/%m/%Y %H:%i') fecha_format FROM recibos WHERE usuario = '$usuario' ORDER by id DESC;");
							$statement->execute();
							$result = $statement->fetchAll(PDO::FETCH_ASSOC);
							}
							foreach ($result as $row) {
								$i++;
								?>
								<tr data-id="<?php echo $row['id']; ?>">
									<td><?php echo $i; ?></td>
									<td><?php echo "00".$row['id']; ?></td>
									<td><?php echo $row['cliente']; ?></td>
									<td><?php echo $row['usuario']; ?></td>
									<td><?php echo $row['monto']; ?></td>
									<td><?php echo $row['fecha_format']; ?></td>
									<td><a class="btn btn-info" target="_blank" href="imprimirRecibo.php?id=<?php echo $row['id']; ?>"> ver</a></td>
									
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

<div class="modal fade" id="exampleModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="exampleModalLabel">Nuevo Recibo</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
        <form id="formRecibo">
        	<input type="hidden" name="acc" value="guardarRecibo">
        	<input type="hidden" name="usuario" id="usuario" value="<?php echo $usuario; ?>">
        	<input type="hidden" name="sucursal" id="sucursal" value="<?php echo $sucursal; ?>">
          <div class="form-group">
            <label for="recipient-name" class="col-form-label">Recibí de:</label>
            <input type="text" class="form-control" name="cliente" id="cliente" placeholder="Nombre y Apellido" required>
          </div>
          <div class="form-group">
            <label for="concepto" class="col-form-label">En concepto de:</label>
            <textarea class="form-control" name="concepto" id="concepto" placeholder="Descripción" required></textarea>
          </div>
          <div class="form-group">
            <label for="monto" class="col-form-label">La cantidad de:</label>
            <input type="number" min="0" class="form-control" name="monto" id="monto" placeholder="$" required>
          </div>
        </form>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancelar</button>
        <button type="button" class="btn btn-primary" id="btnGuardarRecibo">Guardar</button>
      </div>
    </div>
   </div>
</div>

<?php require_once('footer.php'); ?>

<script type="text/javascript">
	$('#btnNuevoRecibo').on('click', function(){
		$('#exampleModal').modal('show');
	});
	$('#btnGuardarRecibo').on('click', function(){
		var monto = $('#monto').val();
		var concepto = $('#concepto').val();
		var cliente = $('#cliente').val();
		if (monto!="" && concepto!="" && cliente !="") {
			var datos = $('#formRecibo').serializeArray();
			$.ajax({
							url: 'apiComprobantes.php',
							method: 'POST',
							data: datos,
							success: function(data){
								if (data.success=true) {
									alertify.success('Comprobante guardado con éxito...', 2);
									window.location.reload();
								}
							},
							error: function(error){
								//$('#cover_top').fadeOut(500);
								console.log(error);
								alertify.error('Ops algo salió mal. Intenta Nuevamente!');
							},

						}); 
		}else{
			alertify.warning('Complete todos los campos!');
		}
	});
	// $('#btnFactura').hide();
	// $('#tablaClientesCuentas tbody').on('click', 'tr', function(){
	// 	var id_factura = $(this).data('id');
	// 	$('#tablaPagos tbody').empty();
	// 	//$('#cover_top').fadeIn(500);
	// 	$.ajax({
	// 		url: 'apiGetPagos.php',
	// 		method: 'POST',
	// 		data: [{'name': 'id', 'value' : id_factura }],
	// 		success: function(data){
	// 			if (data.length>0) {
	// 				console.log(data);
	// 				$('#numFactura').text(id_factura);
	// 				var linea="";
	// 				var sumaPagos = 0;
	// 				for(var i = 0; i < data.length; i++){
	// 					var o = data[i];
	// 					if (o.deuda > 0) {
	// 						var deuda = o.deuda;
	// 						var saldo = deuda;
	// 						console.log(saldo);
	// 						$('#saldo').text(deuda);

	// 					}
						
	// 					if (o.pago > 0) {
	// 						//sumaPagos +=  o.pago;
	// 						saldo -= o.pago;
	// 						console.log('pagoo: '+o.pago);
	// 						console.log('saldo'+saldo);
	// 						linea += '<tr><td>'+i+'</td><td>'+o.fecha_format+'</td><td>'+o.pago+'</td><td>'+o.usuario+'</td><td>'+saldo+'</td></tr>';
	// 					}
	// 				}
	// 				$('#saldoRestante').text(saldo);
	// 				$('#pago').val(saldo);
	// 				$('#pago').attr('max',saldo);

	// 			}else{
	// 					linea = '<tr><td>0</td><td>No se registran pagos a la fecha.</td></tr>';
	// 				}
	// 				if (saldo==0) {
	// 					$('#btnFactura').show();
	// 				}else{
	// 					$('#btnFactura').hide();
	// 				}
	// 				$('#tablaPagos tbody').append(linea);
	// 				$('#idFacturaImp').val(id_factura);
	// 				$('#pagosModal').modal('show');
	// 		},
	// 		error: function(error){
	// 			//$('#cover_top').fadeOut(500);
	// 			console.log(error);
	// 			alertify.error('Ops algo salió mal. comprueba la conexión!');
	// 		},

	// 	});
	// });

	// $('#btnFactura').on('click', function(){
	// 	var idFactura = $('#idFacturaImp').val();
	// 	window.open('imprimirFacturaPagada.php?id='+idFactura,'_blank');
	// });

	// $('#btnAgregarDetallePago').on('click', function(){
	// 	var idFactura = $('#numFactura').text();
	// 	var pago = parseFloat($('#pago').val());
	// 	var idCliente = $('#idCliente').val();
	// 	var idUsuarioCobrador = $('#idUsuarioCobrador').val();
	// 	//console.log(idFactura+'  '+pago);   parseFloat($('#saldoRestante').text())
		
	// 	if (parseFloat($('#saldoRestante').text()) != 0) {
	// 		if (pago <= parseFloat($('#saldoRestante').text()) && pago >0) {
	// 		alertify.confirm('Confirmar pago', 'Va a registrar un pago de $'+pago+' para Factura Nro: '+idFactura+'. ¿Está seguro?', function(){ 
	// 					alertify.warning('Registrando pago...');
	// 					$.ajax({
	// 						url: 'apiRegistrarPagos.php',
	// 						method: 'POST',
	// 						data: [{'name': 'id', 'value' : idFactura }, {'name': 'pago', 'value' : pago }, {'name': 'idCliente', 'value' : idCliente }, {'name': 'usuario', 'value' : idUsuarioCobrador }],
	// 						success: function(data){
	// 							if (data.success=true) {
	// 								alertify.success('Pago realizado con éxito...', 2);
	// 								window.location.reload();
	// 							}
	// 						},
	// 						error: function(error){
	// 							//$('#cover_top').fadeOut(500);
	// 							console.log(error);
	// 							alertify.error('Ops algo salió mal. comprueba la conexión!');
	// 						},

	// 					}); 

	// 			}
	// 	, function(){ alertify.error('Cancelado')});
	// 	}else{
	// 		alertify.warning('El pago no puede ser superior a la deuda');
	// 	}
	// 	}else{
	// 		alertify.warning('La Factura está cancelada!!');
	// 	}
		


	// });

	
</script>